<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para ver un producto específico por ID
     */
    public function test_puede_ver_un_producto(): void
    {
        // Arrange: Crear una categoría y un producto
        $categoria = Categoria::factory()->create();
        $producto = Producto::factory()->create([
            'nombre' => 'Laptop HP',
            'precio' => 1500.00,
            'categoria_id' => $categoria->id
        ]);

        // Act: Hacer petición GET al endpoint
        $response = $this->getJson("/api/productos/{$producto->id}");

        // Assert: Verificar que la respuesta sea exitosa y contenga los datos
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $producto->id,
                     'nombre' => 'Laptop HP',
                     'precio' => 1500.00,
                     'categoria_id' => $categoria->id
                 ]);
    }

    /**
     * Test para eliminar un producto
     */
    public function test_puede_eliminar_un_producto(): void
    {
        // Arrange: Crear una categoría y un producto
        $categoria = Categoria::factory()->create();
        $producto = Producto::factory()->create([
            'categoria_id' => $categoria->id
        ]);

        // Verificar que el producto existe antes de eliminarlo
        $this->assertDatabaseHas('productos', [
            'id' => $producto->id
        ]);

        // Act: Hacer petición DELETE al endpoint
        $response = $this->deleteJson("/api/productos/{$producto->id}");

        // Assert: Verificar que la respuesta sea exitosa
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Producto eliminado correctamente'
                 ]);

        // Verificar que el producto ya no existe en la base de datos
        $this->assertDatabaseMissing('productos', [
            'id' => $producto->id
        ]);
    }

    /**
     * Test para verificar que un producto existe y tiene datos válidos
     */
    public function test_puede_verificar_un_producto(): void
    {
        // Arrange: Crear una categoría y un producto
        $categoria = Categoria::factory()->create([
            'nombre' => 'Electrónica'
        ]);
        
        $producto = Producto::factory()->create([
            'nombre' => 'Mouse Logitech',
            'sku' => 'MOUSE-001',
            'precio' => 25.99,
            'stock' => 50,
            'activo' => true,
            'categoria_id' => $categoria->id
        ]);

        // Act: Obtener el producto de la base de datos
        $productoVerificado = Producto::find($producto->id);

        // Assert: Verificar que el producto existe
        $this->assertNotNull($productoVerificado);
        
        // Verificar que los datos son correctos
        $this->assertEquals('Mouse Logitech', $productoVerificado->nombre);
        $this->assertEquals('MOUSE-001', $productoVerificado->sku);
        $this->assertEquals(25.99, $productoVerificado->precio);
        $this->assertEquals(50, $productoVerificado->stock);
        $this->assertTrue($productoVerificado->activo);
        
        // Verificar que tiene una categoría asociada
        $this->assertNotNull($productoVerificado->categoria);
        $this->assertEquals('Electrónica', $productoVerificado->categoria->nombre);
        
        // Verificar que el producto está en la base de datos
        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Mouse Logitech',
            'sku' => 'MOUSE-001'
        ]);
    }

    /**
     * Test adicional: Verificar que retorna 404 cuando el producto no existe
     */
    public function test_retorna_404_cuando_producto_no_existe(): void
    {
        // Act: Intentar obtener un producto que no existe
        $response = $this->getJson('/api/productos/999');

        // Assert: Verificar que retorna 404
        $response->assertStatus(404);
    }
}
