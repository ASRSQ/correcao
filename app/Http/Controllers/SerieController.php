<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use App\Models\Escola;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    public function index()
    {
        $series = Serie::all();

        return view(
            'series.index',
            compact('series')
        );
    }
    public function area( Escola $escola,Serie $serie) {

    return view(
        'series.show',
        compact('escola', 'serie')
    );
}

    public function escola(Escola $escola)
    {
        $series = Serie::whereHas(
            'alunos',
            function ($query) use ($escola) {

                $query->where(
                    'escola_id',
                    $escola->id
                );

            }
        )->get();

        return view(
            'series.escola',
            compact('escola', 'series')
        );
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required'
        ]);

        Serie::create($request->all());

        return redirect()
            ->route('series.index');
    }



    public function edit(string $id)
    {
        $serie = Serie::findOrFail($id);

        return view(
            'series.edit',
            compact('serie')
        );
    }

    public function update(
        Request $request,
        string $id
    ) {

        $serie = Serie::findOrFail($id);

        $request->validate([
            'nome' => 'required'
        ]);

        $serie->update($request->all());

        return redirect()
            ->route('series.index');
    }

    public function destroy(string $id)
    {
        $serie = Serie::findOrFail($id);

        $serie->delete();

        return redirect()
            ->route('series.index');
    }
}