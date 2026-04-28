<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    protected $fillable = [
        'prova_id',
        'aluno_id',
        'qtd_questoes',
        'acertos',
        'erros',
        'respostas'
    ];

    // 🔗 RELACIONAMENTO COM PROVA
    public function prova()
    {
        return $this->belongsTo(Prova::class);
    }

    // 🔗 RELACIONAMENTO COM ALUNO
    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}