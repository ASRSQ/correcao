<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prova extends Model
{
     protected $fillable = ['nome', 'qtd_questoes', 'qtd_alternativas'];

    public function gabaritos()
    {
        return $this->hasMany(Gabarito::class);
    }
    
}
