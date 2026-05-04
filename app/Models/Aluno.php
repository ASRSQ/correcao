<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aluno extends Model
{
    use HasFactory;

    protected $table = 'alunos';

    protected $fillable = [
    'nome',
    'matricula',
    'serie_id', // ✅ corrigido
    'escola_id',
];
public function serie()
{
    return $this->belongsTo(Serie::class); // ✅ novo
}
    // 🔗 Relacionamentos
    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    // 🔗 Acesso indireto (opcional, mas útil)
    public function cidade()
    {
        return $this->hasOneThrough(
            Cidade::class,
            Escola::class,
            'id',          // FK em escolas
            'id',          // FK em cidades
            'escola_id',   // FK em alunos
            'cidade_id'    // FK em escolas
        );
    }
}