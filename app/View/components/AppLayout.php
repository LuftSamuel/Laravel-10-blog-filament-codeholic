<?php

namespace App\View\Components;

use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function __construct(public ?string $metaTitle = null, public ?string $metaDescription = null)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        DB::statement("SET SQL_MODE=''");
        $categorias = Categoria::query()
        ->join('categoria_post', 'categorias.id', '=', 'categoria_post.id_categoria')
        ->select('categorias.titulo', 'categorias.descripcion', DB::raw('count(*) as total'))
        ->groupBy('categorias.id')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

        return view('layouts.app', compact('categorias'));
    }
}
