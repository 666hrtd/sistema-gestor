<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'faltas', 'calificacion'])]
class Alumno extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Letra calculada automáticamente a partir de la calificación
    protected function equivalencia(): Attribute
    {
        return Attribute::get(function () {
            $c = (float) $this->calificacion;

            if ($c >= 10) return 'AU';
            if ($c >= 9) return 'DE';
            if ($c >= 8) return 'SA';

            return 'NA';
        });
    }
}