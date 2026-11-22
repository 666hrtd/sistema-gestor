<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['jugador_x_id', 'jugador_o_id', 'tablero', 'turno', 'estado', 'ganador_id'])]
class Partida extends Model
{
    public function jugadorX(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jugador_x_id')->withTrashed();
    }

    public function jugadorO(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jugador_o_id')->withTrashed();
    }

    public function ganador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ganador_id')->withTrashed();
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimiento::class)->orderBy('id');
    }
}