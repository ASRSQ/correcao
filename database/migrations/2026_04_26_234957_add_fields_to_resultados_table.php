<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resultados', function (Blueprint $table) {

            // 🔥 adiciona aluno_id
            $table->foreignId('aluno_id')
                  ->nullable()
                  ->after('prova_id')
                  ->constrained('alunos')
                  ->nullOnDelete();

            // 🔥 adiciona qtd_questoes
            $table->integer('qtd_questoes')
                  ->after('aluno_id');

            // 🔥 remove aluno_nome (se existir)
            if (Schema::hasColumn('resultados', 'aluno_nome')) {
                $table->dropColumn('aluno_nome');
            }
        });
    }

    public function down(): void
    {
        Schema::table('resultados', function (Blueprint $table) {

            // recria aluno_nome (caso volte)
            $table->string('aluno_nome')->nullable();

            $table->dropForeign(['aluno_id']);
            $table->dropColumn(['aluno_id', 'qtd_questoes']);
        });
    }
};