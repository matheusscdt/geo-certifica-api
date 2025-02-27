<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class PerfilAgenda extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'perfil_agenda';

    public $timestamps = false;

    protected $fillable = [
        'perfil_id',
        'agenda_id',
    ];

    public $casts = [
        'agenda_id' => 'int',
    ];

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class);
    }

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }
}
