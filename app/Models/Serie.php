<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{


    protected $fillable = ['nome'];

    public function alunos()
    {
        return $this->hasMany(Aluno::class);
    }

    public function provas()
    {
        return $this->hasMany(Prova::class);
    }
}
