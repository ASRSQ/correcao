<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'nome',
        'prova_id'
    ];

    public function prova()
    {
        return $this->belongsTo(Prova::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}