<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    protected $fillable = ['prova_id', 'aluno_nome', 'acertos', 'erros', 'respostas'];
    public function prova()
{
    return $this->belongsTo(Prova::class);
}
}

