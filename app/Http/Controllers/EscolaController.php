<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Escola;
use Illuminate\Http\Request;

class EscolaController extends Controller
{
    public function index()
    {
        $escolas = Escola::with('cidade')->get();

        return view(
            'escolas.index',
            compact('escolas')
        );
    }

    public function cidade(Cidade $cidade)
    {
        $escolas = Escola::where(
            'cidade_id',
            $cidade->id
        )
        ->withCount('alunos')
        ->get();

        return view(
            'escolas.cidade',
            compact('cidade', 'escolas')
        );
    }

public function create(Request $request)
{
    $cidades = Cidade::all();

    $cidade = null;

    $escolas = collect();

    if ($request->cidade_id) {

        $cidade = Cidade::find(
            $request->cidade_id
        );

        $escolas = Escola::where(
            'cidade_id',
            $request->cidade_id
        )->get();

    }

    return view(
        'escolas.create',
        compact(
            'cidades',
            'cidade',
            'escolas'
        )
    );
}

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'cidade_id' => 'required'
        ]);

        $escola = Escola::create(
            $request->all()
        );

        return redirect()->route(
            'cidade.escolas',
            $escola->cidade_id
        );
    }

    public function edit(Escola $escola)
    {
        $cidades = Cidade::all();

        return view(
            'escolas.edit',
            compact('escola', 'cidades')
        );
    }

    public function update(
        Request $request,
        Escola $escola
    ) {

        $escola->update(
            $request->all()
        );

        return redirect()->route(
            'cidade.escolas',
            $escola->cidade_id
        );
    }

    public function destroy(Escola $escola)
    {
        $cidadeId = $escola->cidade_id;

        $escola->delete();

        return redirect()->route(
            'cidade.escolas',
            $cidadeId
        );
    }
}