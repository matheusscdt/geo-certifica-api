<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class PerfilPasta extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'perfil_pasta';

    public $timestamps = false;

    protected $fillable = [
        'perfil_id',
        'pasta_id',
    ];

    public $casts = [
        'pasta_id' => 'int',
    ];

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class);
    }

    public function pasta(): BelongsTo
    {
        return $this->belongsTo(Pasta::class);
    }
}
