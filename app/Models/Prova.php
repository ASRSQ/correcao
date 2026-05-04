<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prova extends Model
{
     protected $fillable = [
    'nome',
    'qtd_questoes',
    'qtd_alternativas',
    'escola_id',
    'serie_id' // ✅ corrigido
];

    public function gabaritos()
    {
        return $this->hasMany(Gabarito::class);
    }
    public function aluno()
    {
        return $this->belongsTo(\App\Models\Aluno::class);
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }
    
}
