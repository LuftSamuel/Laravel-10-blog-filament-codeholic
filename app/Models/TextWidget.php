<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TextWidget extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'imagen',
        'titulo',
        'contenido',
        'activo'
    ];

    public static function obtenerTitulo(string $key)
    {
        $widget = Cache::get('text-widget-' . $key, function() use($key){
            return TextWidget::query()
            ->where('key','=',$key)
            ->where('activo','=',1)
            ->first();
        });
        // $widget = TextWidget::query()
        // ->where('key','=',$key)
        // ->where('activo','=',1)
        // ->first();

        if($widget){
            return $widget->titulo;
        }

        return '';
    }

    public static function obtenerContenido(string $key)
    {
        $widget = Cache::get('text-widget-' . $key, function() use($key){
            return TextWidget::query()
            ->where('key','=',$key)
            ->where('activo','=',1)
            ->first();
        });
        // $widget = TextWidget::query()
        // ->where('key','=',$key)
        // ->where('activo','=',1)
        // ->first();

        if($widget){
            return $widget->contenido;
        }

        return '';
    }
}
