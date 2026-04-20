<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gabarito extends Model
{
     protected $fillable = ['prova_id', 'questao', 'resposta'];
}
