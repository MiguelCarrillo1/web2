<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Producto;

class DemoSeeder extends Seeder
{

    public function run(): void
    {
        Categoria::factory()
        ->count(5)
        ->has(Producto::factory()->count(8))
        ->create();
    }
}
