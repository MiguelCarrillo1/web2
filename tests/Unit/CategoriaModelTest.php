<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Categoria;
use App\Models\Producto;

class CategoriaModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para consultar todos los productos que pertenecen a una categoría
     */
    public function test_puede_consultar_productos_de_una_categoria(): void
    {
        // Arrange: Crear una categoría
        $categoria = Categoria::factory()->create([
            'nombre' => 'Tecnología'
        ]);

        // Crear varios productos asociados a esta categoría
        $producto1 = Producto::factory()->create([
            'nombre' => 'Laptop Dell',
            'precio' => 1200.00,
            'categoria_id' => $categoria->id
        ]);

        $producto2 = Producto::factory()->create([
            'nombre' => 'Mouse Inalámbrico',
            'precio' => 30.00,
            'categoria_id' => $categoria->id
        ]);

        $producto3 = Producto::factory()->create([
            'nombre' => 'Teclado Mecánico',
            'precio' => 80.00,
            'categoria_id' => $categoria->id
        ]);

        // Crear otra categoría con productos para verificar que no se mezclan
        $otraCategoria = Categoria::factory()->create([
            'nombre' => 'Ropa'
        ]);

        Producto::factory()->create([
            'nombre' => 'Camisa',
            'categoria_id' => $otraCategoria->id
        ]);

        // Act: Consultar todos los productos de la categoría
        $productos = $categoria->productos;

        // Assert: Verificar que la categoría tiene exactamente 3 productos
        $this->assertCount(3, $productos);

        // Verificar que los productos pertenecen a la categoría correcta
        $this->assertTrue($productos->contains('id', $producto1->id));
        $this->assertTrue($productos->contains('id', $producto2->id));
        $this->assertTrue($productos->contains('id', $producto3->id));

        // Verificar que los nombres de los productos son correctos
        $nombresProductos = $productos->pluck('nombre')->toArray();
        $this->assertContains('Laptop Dell', $nombresProductos);
        $this->assertContains('Mouse Inalámbrico', $nombresProductos);
        $this->assertContains('Teclado Mecánico', $nombresProductos);

        // Verificar que NO contiene productos de otras categorías
        $this->assertFalse($productos->contains('nombre', 'Camisa'));
    }

    /**
     * Test adicional: Verificar que una categoría sin productos retorna colección vacía
     */
    public function test_categoria_sin_productos_retorna_coleccion_vacia(): void
    {
        // Arrange: Crear una categoría sin productos
        $categoria = Categoria::factory()->create([
            'nombre' => 'Categoría Vacía'
        ]);

        // Act: Consultar los productos
        $productos = $categoria->productos;

        // Assert: Verificar que la colección está vacía
        $this->assertCount(0, $productos);
        $this->assertTrue($productos->isEmpty());
    }

    /**
     * Test adicional: Verificar que se pueden filtrar productos activos de una categoría
     */
    public function test_puede_filtrar_productos_activos_de_una_categoria(): void
    {
        // Arrange: Crear una categoría
        $categoria = Categoria::factory()->create();

        // Crear productos activos e inactivos
        Producto::factory()->create([
            'nombre' => 'Producto Activo 1',
            'activo' => true,
            'categoria_id' => $categoria->id
        ]);

        Producto::factory()->create([
            'nombre' => 'Producto Activo 2',
            'activo' => true,
            'categoria_id' => $categoria->id
        ]);

        Producto::factory()->create([
            'nombre' => 'Producto Inactivo',
            'activo' => false,
            'categoria_id' => $categoria->id
        ]);

        // Act: Consultar solo productos activos
        $productosActivos = $categoria->productos()->where('activo', true)->get();

        // Assert: Verificar que solo hay 2 productos activos
        $this->assertCount(2, $productosActivos);
        
        // Verificar que todos los productos retornados están activos
        foreach ($productosActivos as $producto) {
            $this->assertTrue($producto->activo);
        }
    }
}
