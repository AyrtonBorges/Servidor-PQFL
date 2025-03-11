<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produtor;

class ProdutorController extends Controller
{
    /**
     * Lista todos os produtores
     */
    public function index()
    {
        return response()->json(Produtor::all());
    }

    /**
     * Obtém um produtor específico pelo ID
     */
    public function show($id)
    {
        $produtor = Produtor::find($id);
        if (!$produtor) {
            return response()->json(['message' => 'Produtor não encontrado'], 404);
        }
        return response()->json($produtor);
    }

    /**
     * Cria um novo produtor
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'matricula' => 'required|unique:produtor,matricula',
            'cpf_cnpj' => 'required|unique:produtor,cpf_cnpj',
            'inscr_estad_prod' => 'required|unique:produtor,inscr_estad_prod',
            'telefone' => 'required',
            'email_prod' => 'required|email|unique:produtor,email_prod',
            'uf_prod' => 'required',
            'cidade_prod' => 'required',
            'cep_prod' => 'required',
            'bairro_prod' => 'required',
            'end_resid_prod' => 'required',
            'num_resid_prod' => 'required',
            'status_prod' => 'required|integer',
            'nrp_prod' => 'required',
            'naturalidade' => 'required',
            'nome_prod' => 'required',
            'data_nasc_prod' => 'required|date',
            'est_civil_code' => 'required|integer',
            'TipoConta_id' => 'required|integer',
            'rg' => 'nullable',
            'org_emissor_prod' => 'nullable',
            'emissao' => 'nullable|date',
            'variacao_operacao' => 'nullable',
            'foto_produtor' => 'nullable'
        ]);

        $produtor = Produtor::create($data);
        return response()->json($produtor, 201);
    }

    /**
     * Atualiza um produtor existente
     */
    public function update(Request $request, $id)
    {
        $produtor = Produtor::find($id);
        if (!$produtor) {
            return response()->json(['message' => 'Produtor não encontrado'], 404);
        }

        $data = $request->validate([
            'matricula' => 'required|unique:produtor,matricula,' . $id . ',idProdutor',
            'cpf_cnpj' => 'required|unique:produtor,cpf_cnpj,' . $id . ',idProdutor',
            'inscr_estad_prod' => 'required|unique:produtor,inscr_estad_prod,' . $id . ',idProdutor',
            'telefone' => 'required',
            'email_prod' => 'required|email|unique:produtor,email_prod,' . $id . ',idProdutor',
            'uf_prod' => 'required',
            'cidade_prod' => 'required',
            'cep_prod' => 'required',
            'bairro_prod' => 'required',
            'end_resid_prod' => 'required',
            'num_resid_prod' => 'required',
            'status_prod' => 'required|integer',
            'nrp_prod' => 'required',
            'naturalidade' => 'required',
            'nome_prod' => 'required',
            'data_nasc_prod' => 'required|date',
            'est_civil_code' => 'required|integer',
            'TipoConta_id' => 'required|integer',
            'rg' => 'nullable',
            'org_emissor_prod' => 'nullable',
            'emissao' => 'nullable|date',
            'variacao_operacao' => 'nullable',
            'foto_produtor' => 'nullable'
        ]);

        $produtor->update($data);
        return response()->json($produtor);
    }

    /**
     * Remove um produtor do banco
     */
    public function destroy($id)
    {
        $produtor = Produtor::find($id);
        if (!$produtor) {
            return response()->json(['message' => 'Produtor não encontrado'], 404);
        }

        $produtor->delete();
        return response()->json(['message' => 'Produtor removido com sucesso']);
    }
}
