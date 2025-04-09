<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class SyncController extends Controller
{
    public function sync(Request $request)
    {
        // Se a requisição for GET, envia os dados do servidor para o cliente
        if ($request->isMethod('get')) {
            $lastSync = $request->query('last_sync');

            // Tabelas do Laravel que possuem versionamento (deveriam ter updated_at)
            $tablesLaravel = [
                'produtor',
                'tipopropriedade',
                'propriedade',
                'tanque',
                'tecnico',
                'tipodevisita',
                'associado',
                'associacao',
                'estadostatus',
                'modalidade',
                'instrucao',
                'tipo_de_metodologia_utilizada',
                'metodologia_utilizada',
                'periodicidade',
                'respostapossivel',
                'possiveisrepostasdeumapergunta',
                'produtorestadostatus',
                'recomendacao',
                'classe_de_um_tanque',
                // 'historico_classificacao',
                'perguntas_da_classe_de_um_tanque',
                'formulariodeumavisita',
                'perguntadeumformulario'
            ];

            $data = [];

            // Para as tabelas do Laravel: verifica se a coluna updated_at existe antes de filtrar
            foreach ($tablesLaravel as $table) {
                if ($lastSync) {
                    $lastSyncConverted = Carbon::parse($lastSync)->format('Y-m-d H:i:s');
                    error_log('last_sync: ' . $lastSyncConverted);
                    $data[$table] = DB::table($table)
                        ->where('updated_at', '>', $lastSyncConverted)
                        ->get();

                }
            }

            return response()->json($data);
        }
        // Se a requisição for POST, recebe os dados do cliente e os insere/atualiza
        elseif ($request->isMethod('post')) {

            $clientTables = [
                'formulariodeumavisita',
                'visita',
                'respostadeumaperguntadeumavisita',
                'tecnicos_de_uma_visita',
            ];

            $clientData = $request->only($clientTables);

            Log::info('Dados recebidos do cliente: ' . json_encode($clientData));

            foreach ($clientTables as $table) {
                if (!isset($clientData[$table]) || !is_array($clientData[$table])) {
                    continue;
                }

                // AGRUPAMENTO ESPECIAL PARA 'respostadeumaperguntadeumavisita'
                if ($table === 'respostadeumaperguntadeumavisita') {
                    $agrupadasPorVisita = [];

                    foreach ($clientData[$table] as $item) {
                        if (!isset($item['id']) || !isset($item['Visita_id'])) {
                            continue;
                        }

                        $exists = DB::table($table)->where('id', $item['id'])->first();

                        if ($exists) {
                            $visitaId = $item['Visita_id'];

                            $agrupadasPorVisita[$visitaId][] = [
                                'chave_primaria' => $item['id'],
                                'dados_antigos' => (array) $exists,
                                'dados_novos' => $item,
                            ];
                        } else {
                            DB::table($table)->insert($item);
                        }
                    }

                    // Agora salva 1 pendência por Visita
                    foreach ($agrupadasPorVisita as $visitaId => $alteracoes) {
                        DB::table('pendencias_de_sincronizacao')->insert([
                            'tabela' => $table,
                            'chave_primaria' => (string) $visitaId,
                            'dados_antigos' => json_encode(array_column($alteracoes, 'dados_antigos')),
                            'dados_novos' => json_encode(array_column($alteracoes, 'dados_novos')),
                            'status' => 'pendente',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    continue;
                }

                // CHAVE COMPOSTA
                if ($table === 'tecnicos_de_uma_visita') {
                    foreach ($clientData[$table] as $item) {
                        $exists = DB::table($table)
                            ->where('Tecnico_idTecnico', $item['Tecnico_idTecnico'])
                            ->where('Visita_id', $item['Visita_id'])
                            ->first();

                        if ($exists) {
                            DB::table('pendencias_de_sincronizacao')->insert([
                                'tabela' => $table,
                                'chave_primaria' => json_encode([
                                    'Tecnico_idTecnico' => $item['Tecnico_idTecnico'],
                                    'Visita_id' => $item['Visita_id'],
                                ]),
                                'dados_antigos' => json_encode($exists),
                                'dados_novos' => json_encode($item),
                                'status' => 'pendente',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        } else {
                            DB::table($table)->insert($item);
                        }
                    }
                    continue;
                }

                // CHAVE SIMPLES
                foreach ($clientData[$table] as $item) {
                    if (isset($item['id'])) {
                        $exists = DB::table($table)->where('id', $item['id'])->first();

                        if ($exists) {
                            DB::table('pendencias_de_sincronizacao')->insert([
                                'tabela' => $table,
                                'chave_primaria' => (string) $item['id'],
                                'dados_antigos' => json_encode($exists),
                                'dados_novos' => json_encode($item),
                                'status' => 'pendente',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        } else {
                            DB::table($table)->insert($item);
                        }
                    } else {
                        DB::table($table)->insert($item);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Dados do cliente sincronizados com sucesso'
            ]);
        } else {
            return response()->json(['error' => 'Método não suportado'], 405);
        }
    }
}
