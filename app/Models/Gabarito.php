<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gabarito extends Model
{
     protected $fillable = ['prova_id', 'questao', 'resposta'];
     public function prova()
{
    return $this->belongsTo(Prova::class, 'prova_id');
}

}
