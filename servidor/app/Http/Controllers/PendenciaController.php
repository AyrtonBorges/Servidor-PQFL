<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PendenciaController extends Controller
{
    public function index()
    {
        $pendencias = DB::table('pendencias_de_sincronizacao')
            ->where('status', 'pendente')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($pendencias);
    }

    public function aprovar($id)
    {
        $pendencia = DB::table('pendencias_de_sincronizacao')->find($id);

        if (!$pendencia) {
            return response()->json(['error' => 'Pendência não encontrada'], 404);
        }

        $dadosNovos = json_decode($pendencia->dados_novos, true);

        try {
            if ($pendencia->tabela === 'tecnicos_de_uma_visita') {
                // ✅ Lida com chave composta: Tecnico_idTecnico + Visita_id
                if (isset($dadosNovos[0])) {
                    foreach ($dadosNovos as $item) {
                        if (!isset($item['Tecnico_idTecnico'], $item['Visita_id'])) continue;

                        DB::table('tecnicos_de_uma_visita')
                            ->where('Tecnico_idTecnico', $item['Tecnico_idTecnico'])
                            ->where('Visita_id', $item['Visita_id'])
                            ->update($item);
                    }
                } elseif (isset($dadosNovos['Tecnico_idTecnico'], $dadosNovos['Visita_id'])) {
                    DB::table('tecnicos_de_uma_visita')
                        ->where('Tecnico_idTecnico', $dadosNovos['Tecnico_idTecnico'])
                        ->where('Visita_id', $dadosNovos['Visita_id'])
                        ->update($dadosNovos);
                } else {
                    return response()->json(['error' => 'Chaves compostas não encontradas nos dados.'], 400);
                }
            } else {
                // 🧠 Atualização padrão com 'id' como chave
                if (is_array($dadosNovos) && isset($dadosNovos[0])) {
                    foreach ($dadosNovos as $item) {
                        if (!isset($item['id'])) continue;
                        DB::table($pendencia->tabela)
                            ->where('id', $item['id'])
                            ->update($item);
                    }
                } elseif (is_array($dadosNovos) && isset($dadosNovos['id'])) {
                    DB::table($pendencia->tabela)
                        ->where('id', $dadosNovos['id'])
                        ->update($dadosNovos);
                } else {
                    Log::warning("Formato inválido nos dados_novos da pendência ID $pendencia->id");
                    return response()->json(['error' => 'Formato dos dados inválido.'], 400);
                }
            }

            DB::table('pendencias_de_sincronizacao')->where('id', $id)->update([
                'status' => 'aprovado',
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Pendência aprovada e aplicada com sucesso.']);
        } catch (\Exception $e) {
            Log::error("Erro ao aprovar pendência ID $id: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao aplicar pendência.'], 500);
        }
    }

    public function rejeitar($id)
    {
        $pendencia = DB::table('pendencias_de_sincronizacao')->find($id);

        if (!$pendencia) {
            return response()->json(['error' => 'Pendência não encontrada'], 404);
        }

        DB::table('pendencias_de_sincronizacao')->where('id', $id)->update([
            'status' => 'rejeitado',
            'updated_at' => now()
        ]);

        return response()->json(['status' => 'success', 'message' => 'Pendência rejeitada com sucesso.']);
    }

    public function webIndex()
    {
        $pendencias = DB::table('pendencias_de_sincronizacao')
            ->where('status', 'pendente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pendencias.index', compact('pendencias'));
    }
}
