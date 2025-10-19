<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //consultar todos los registro que existen en la tabla de productos
        return Producto::query()
        ->with('categoria')
        ->orderBy('id','desc')
        ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:productos,nombre',
            'sku' => 'required|string|max:255|unique:productos,sku',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'activo' => 'boolean',
            'categoria_id' => 'required|exists:categorias,id',
        ]);
        //agregar o crear el registro como tal en la base de datos
        $producto = Producto::create($data);
        //devolver una respuesta al cliente
        return response()->json($producto);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
        return $producto->load('categoria');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        //
        $data = $request->validate([
            'nombre' => 'sometimes|required|string|max:255|unique:productos,nombre,' . $producto->id,
            'sku' => 'sometimes|required|string|max:255|unique:productos,sku,' . $producto->id,
            'stock' => 'sometimes|required|integer|min:0',
            'precio' => 'sometimes|required|numeric|min:0',
            'activo' => 'sometimes|boolean',
            'categoria_id' => 'sometimes|required|exists:categorias,id',
        ]);

        $producto->update($data);
        return response()->json($producto);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
        $producto->delete();
        //devolver una respuesta al cliente
        return response()->json(['message'=>'Producto eliminado correctamente'], 200);
    }
}
