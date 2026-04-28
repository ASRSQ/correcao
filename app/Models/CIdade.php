<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cidade extends Model
{
    use HasFactory;

    protected $table = 'cidades';

    protected $fillable = [
        'nome',
    ];

    // 🔗 Relacionamentos
    public function escolas()
    {
        return $this->hasMany(Escola::class);
    }
}