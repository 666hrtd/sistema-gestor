<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nombre', 'descripcion', 'disponible'])]
class Equipo extends Model
{
    protected function casts(): array
    {
        return [
            'disponible' => 'boolean',
        ];
    }

    public function reservaciones(): HasMany
    {
        return $this->hasMany(Reservacion::class);
    }
}