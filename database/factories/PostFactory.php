<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $titulo = fake()->realText(50);

        return [
            'titulo' => $titulo,
            'descripcion' => Str::slug($titulo),
            'miniatura' => fake()->imageUrl,
            'post' => fake()->realText(5000),
            'activo' => fake()->boolean,
            'publicarse_en' => fake()->dateTime,
            'id_usuario' => 1
        ];
    }
}
