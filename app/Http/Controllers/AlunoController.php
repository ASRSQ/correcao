<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escola;
use App\Models\Serie;
use App\Models\Aluno;
use App\Models\Cidade;

class AlunoController extends Controller
{
    public function create()
{
    $cidades = Cidade::all();

    return view(
        'alunos.create',
        compact('cidades')
    );
}
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'matricula' => 'required',
            'cidade_id' => 'required',
            'escola_id' => 'required',
            'serie_id' => 'required',
        ]);

        Aluno::create($request->all());

        return redirect()->back();
    }

    public function edit(string $id)
    {
        $aluno = Aluno::findOrFail($id);

        $cidades = Cidade::all();
        $escolas = Escola::all();
        $series = Serie::all();

        return view(
            'alunos.edit',
            compact(
                'aluno',
                'cidades',
                'escolas',
                'series'
            )
        );
    }

    public function update(
        Request $request,
        string $id
    ) {

        $aluno = Aluno::findOrFail($id);

        $request->validate([
            'nome' => 'required',
            'matricula' => 'required',
            'cidade_id' => 'required',
            'escola_id' => 'required',
            'serie_id' => 'required',
        ]);

        $aluno->update($request->all());

        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $aluno = Aluno::findOrFail($id);

        $aluno->delete();

        return redirect()->back();
    }
 
    public function serie(Escola $escola,Serie $serie) {

    $alunos = Aluno::where(
        'escola_id',
        $escola->id
    )
    ->where(
        'serie_id',
        $serie->id
    )
    ->get();

    return view(
        'alunos.serie',
        compact(
            'escola',
            'serie',
            'alunos'
        )
    );
}
}
