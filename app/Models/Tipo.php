<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Tipo extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'tipo';

    protected $fillable = [
        'perfil_id',
        'descricao'
    ];

    protected function descricao(): Attribute
    {
        return new Attribute(
            get: fn($value, $attributes) => convertToCamelCase($attributes['descricao']),
            set: fn ($value) => ['descricao' => mb_strtoupper($value)]
        );
    }

    public function destinatarios(): HasMany
    {
        return $this->hasMany(Destinatario::class);
    }

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class);
    }
}
