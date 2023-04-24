<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = ['titulo', 'descripcion'];

    public function posts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'categoria_post', 'id_categoria', 'id_post');
    }

    public function postsActivos(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'categoria_post', 'id_categoria', 'id_post')
            ->where('activo', '=', 1)
            ->whereDate('publicarse_en', '<', Carbon::now());
    }
}
