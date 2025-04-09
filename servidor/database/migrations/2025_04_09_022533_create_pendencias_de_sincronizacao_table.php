<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendencias_de_sincronizacao', function (Blueprint $table) {
            $table->id();
            $table->string('tabela');
            $table->string('chave_primaria')->nullable(); // para chave simples ou composta
            $table->json('dados_antigos');
            $table->json('dados_novos');
            $table->enum('status', ['pendente', 'aprovado', 'rejeitado'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendencias_de_sincronizacao');
    }
};
