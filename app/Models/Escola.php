<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Escola extends Model
{
    use HasFactory;

    protected $table = 'escolas';

    protected $fillable = [
        'nome',
        'cidade_id',
    ];

    // 🔗 Relacionamentos
    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    public function alunos()
    {
        return $this->hasMany(Aluno::class);
    }
}