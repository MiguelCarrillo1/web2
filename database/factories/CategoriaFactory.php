<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
      protected $model = Categoria::class;

    public function definition(): array
    {
       return [
         'nombre' => $this->faker->unique()->words(2, true),
         'descripcion' => $this->faker->sentence(),
         'activa' => $this->faker->boolean(90), // ← usa "activa"
      ];
    }
}
