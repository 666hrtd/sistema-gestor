<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['nombre', 'descripcion', 'categoria_id', 'precio', 'existencias', 'estado'])]
class Producto extends Model
{
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }
}