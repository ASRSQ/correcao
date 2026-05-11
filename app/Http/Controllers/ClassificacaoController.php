<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Questao;
use App\Models\Prova;

class ClassificacaoController extends Controller
{
    public function index(Prova $prova)
    {
        $categories =
            Category::where('prova_id', $prova->id)
                ->with('subcategories')
                ->get();

        $questoes =
            Questao::where('prova_id', $prova->id)
                ->get()
                ->keyBy('numero');

        return view(
            'provas.classificacao',
            compact(
                'prova',
                'categories',
                'questoes'
            )
        );
    }

    public function storeCategoria(
        Request $request,
        Prova $prova
    ) {

        $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        Category::create([
            'nome' => $request->nome,
            'prova_id' => $prova->id
        ]);

        return back()
            ->with('success', 'Categoria criada');
    }

    public function storeSubcategoria(
        Request $request,
        Prova $prova
    ) {

        $request->validate([
            'nome' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);

        Subcategory::create([
            'nome' => $request->nome,
            'category_id' => $request->category_id
        ]);

        return back()
            ->with('success', 'Habilidade criada');
    }

    public function salvarClassificacao(
        Request $request,
        Prova $prova
    ) {

        if ($request->has('questoes')) {

            foreach ($request->questoes as $numero => $subcategoria) {

                if (!$subcategoria) {
                    continue;
                }

                Questao::updateOrCreate(
                    [
                        'prova_id' => $prova->id,
                        'numero' => $numero
                    ],
                    [
                        'subcategory_id' => $subcategoria
                    ]
                );
            }
        }

        return back()
            ->with('success', 'Classificação salva');
    }
}