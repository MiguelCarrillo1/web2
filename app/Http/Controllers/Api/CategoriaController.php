<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Categoria::query()
        ->withCount('productos')
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
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
            'descripcion' => 'nullable|string',
            'activa' => 'boolean'
        ]);

        //Crear el resgistro en la tabla de la base de datos
        $categoria = Categoria::create($data);

        //Devolver una respuesta al cliente
        return response()->json($categoria);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
        return $categoria->load('productos');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
        $data = $request->validate([
            'nombre'=>'sometimes|required|string|max:255|unique:categorias,nombre,'.$categoria->id,
            'descripcion'=>'nullable|string',
            'activa'=>'boolean',
        ]);

        $categoria->update($data);
        return response()->json($categoria);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        //
        $categoria->delete();
        return response()->json(['message' => 'Categoría eliminada correctamente.'], 200);
    }
}
