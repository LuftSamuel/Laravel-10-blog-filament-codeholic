<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'titulo', 'descripcion', 'miniatura', 'post', 'activo', 'publicarse_en',
        'id_usuario', 'meta_title', 'meta_description'
    ];

    protected $casts = ['publicarse_en' => 'datetime'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        //https://stackoverflow.com/questions/35628583/laravel-5-says-unknown-column-user-id-in-field-list-but-i-have-author-i
        return $this->belongsTo(User::class, 'id_usuario');
        // esto me daba el problema de que al no especificar el segundo parametro
        // se tomaba el valor user_id por convencion
    }

    public function categorias(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'categoria_post', 'id_post', 'id_categoria');
    }

    public function shortBody(): string
    {
        return Str::words(strip_tags($this->post), 30);
    }

    public function fechaFormateada(): string
    {
        return $this->publicarse_en->format('F jS Y');
    }

    public function obtenerMiniatura(): string
    {
        if (str_starts_with($this->miniatura, 'http')) {
            return $this->miniatura;
        } else {
            return '/storage/' . $this->miniatura;
        }
    }

    public function humanReadTime(): Attribute
    {
        return new Attribute(

            get: function ($value, $attributes) {
                $words = Str::wordCount(strip_tags($attributes['post']));
                $minutes = ceil($words / 200);

                return $minutes . ' ' . str('minute')->plural($minutes) . ', ' . $words . 'words.';
            }
        );
    }
}
