<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional si sigue la convención plural)
    protected $table = 'productos';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'sku',
        'precio',
        'stock',
        'activo',
        'categoria_id',
    ];

    // Casts para convertir tipos automáticamente
    protected $casts = [
        'activo' => 'boolean',
        'precio' => 'float',
        'stock' => 'integer',
    ];

    // Relación: un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
