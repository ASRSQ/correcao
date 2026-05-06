<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Http\Request;

class CidadeController extends Controller
{
    public function index()
    {
        $cidades = Cidade::all();
        return view('cidades.index', compact('cidades'));
    }

    public function create()
    {
        return view('cidades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required'
        ]);

        Cidade::create($request->all());

        return redirect()->route('cidades.index');
    }

    public function edit($id)
    {
        $cidade = Cidade::findOrFail($id);

        return view('cidades.edit', compact('cidade'));
    }

    public function update(Request $request, $id)
    {
        $cidade = Cidade::findOrFail($id);

        $request->validate([
            'nome' => 'required'
        ]);

        $cidade->update($request->all());

        return redirect()->route('cidades.index');
    }

    public function destroy($id)
    {
        $cidade = Cidade::findOrFail($id);

        $cidade->delete();

        return redirect()->route('cidades.index');
    }
}