<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddUpdatedAtToAllTables extends Command
{
    protected $signature = 'db:add-updated-at';
    protected $description = 'Adiciona a coluna updated_at em todas as tabelas do banco';

    public function handle()
    {
        // Obtém todas as tabelas do banco
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $tableName = reset($table);

            // Verifica se a tabela já tem a coluna updated_at
            $columns = DB::select("SHOW COLUMNS FROM `$tableName` LIKE 'updated_at'");

            if (empty($columns)) {
                // Se não tem, adiciona a coluna
                $sql = "ALTER TABLE `$tableName` ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
                DB::statement($sql);
                $this->info("✅ Campo `updated_at` adicionado à tabela: $tableName");
            } else {
                $this->warn("⚠️ A tabela `$tableName` já possui `updated_at`");
            }
        }

        $this->info("🎉 Processo finalizado!");
    }
}
