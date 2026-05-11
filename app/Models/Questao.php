<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questao extends Model
{
   protected $fillable = [
        'prova_id',
        'subcategory_id',
        'numero'
    ];

    public function prova()
    {
        return $this->belongsTo(Prova::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
