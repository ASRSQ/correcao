<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provas', function (Blueprint $table) {

                $table->foreignId('escola_id')
            ->nullable()
            ->constrained('escolas')
            ->nullOnDelete();

        $table->string('serie')->nullable();
                });
    }

    public function down(): void
    {
        Schema::table('provas', function (Blueprint $table) {

            $table->dropForeign(['escola_id']);
            $table->dropColumn(['escola_id', 'serie']);
        });
    }
};
