<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtor extends Model
{
    use HasFactory;

    protected $table = 'produtor'; // Definir o nome exato da tabela

    protected $primaryKey = 'idProdutor'; // Definir a chave primária

    public $timestamps = false; // Não vamos usar `created_at` e `updated_at`

    protected $fillable = [
        'matricula',
        'cpf_cnpj',
        'inscr_estad_prod',
        'telefone',
        'email_prod',
        'uf_prod',
        'cidade_prod',
        'cep_prod',
        'bairro_prod',
        'end_resid_prod',
        'num_resid_prod',
        'status_prod',
        'nrp_prod',
        'naturalidade',
        'nome_prod',
        'data_nasc_prod',
        'est_civil_code',
        'TipoConta_id',
        'rg',
        'org_emissor_prod',
        'emissao',
        'variacao_operacao',
        'foto_produtor'
    ];
}
