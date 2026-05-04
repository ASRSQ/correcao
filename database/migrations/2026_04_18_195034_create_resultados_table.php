<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
{
    Schema::create('resultados', function (Blueprint $table) {
        $table->id();

        $table->foreignId('prova_id')
              ->constrained()
              ->cascadeOnDelete();

        // 🔥 NOVO PADRÃO
        $table->foreignId('aluno_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->integer('qtd_questoes');

        $table->integer('acertos')->default(0);
        $table->integer('erros')->default(0);

        $table->json('respostas');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados');
    }
};
