<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    /**
     * Lista das tabelas permitidas para sincronização
     */
    private $allowedTables = [
        'produtor',
        'tipopropriedade',
        'propriedade',
        'tanque',
        'tecnico',
        'tipodevisita',
        'formulariodeumavisita',
        'visita',
        'associado',
        'associacao',
        'estadostatus',
        'modalidade',
        'perguntadeumformulario',
        'instrucao',
        'tipo_de_metodologia_utilizada',
        'metodologia_utilizada',
        'periodicidade',
        'respostapossivel',
        'possiveisrepostasdeumapergunta',
        'produtorestadostatus',
        'recomendacao',
        'respostadeumaperguntadeumavisita',
        'tecnicos_de_uma_visita',
        'classe_de_um_tanque',
        'historico_classificacao',
        'perguntas_da_classe_de_um_tanque'
    ];

    /**
     * Sincroniza apenas as tabelas permitidas e retorna registros novos ou atualizados após `last_sync`
     */
    public function sync(Request $request)
    {
        // Valida o parâmetro `last_sync`
        $request->validate([
            'last_sync' => 'required|date'
        ]);

        $lastSync = $request->input('last_sync');
        $updatedData = [];

        foreach ($this->allowedTables as $tableName) {
            // Verifica se a tabela existe no banco
            $exists = DB::select("SHOW TABLES LIKE '$tableName'");

            if (!empty($exists)) {
                // Verifica se a tabela tem a coluna `updated_at`
                $columns = DB::select("SHOW COLUMNS FROM `$tableName` LIKE 'updated_at'");

                if (!empty($columns)) {
                    // Busca registros atualizados após `last_sync`
                    $data = DB::table($tableName)
                        ->where('updated_at', '>', $lastSync)
                        ->get()
                        ->map(function ($item) {
                            // Remove a coluna `updated_at` de cada item
                            unset($item->updated_at);
                            return $item;
                        });

                    if (!$data->isEmpty()) {
                        $updatedData[$tableName] = $data;
                    }
                }
            }
        }

        return response()->json($updatedData);
    }
}
