<?php

namespace App\Http\Controllers;

use App\Models\TextWidget;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function acerca_de(): View
    {
        $widget = TextWidget::query()
        ->where('activo', '=', true)
        ->where('key', '=', 'acerca-de')
        ->first();

        if(!$widget){
            throw new NotFoundHttpException();
        }

        return view('acerca', compact('widget'));
    }
}
