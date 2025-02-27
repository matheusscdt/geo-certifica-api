<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Convite extends Model implements Transformable
{
    use TransformableTrait, HasUuids;

    public $table = 'convite';

    protected $fillable = [
        'perfil_id',
        'nome',
        'email',
        'aceite',
        'data_aceite',
        'gestor'
    ];

    protected $casts = [
        'aceite' => 'boolean',
        'data_aceite' => 'datetime',
        'gestor' => 'boolean'
    ];

    protected function nome(): Attribute
    {
        return new Attribute(
            get: fn($value, $attributes) => convertToCamelCase($attributes['nome']),
            set: fn ($value) => ['nome' => mb_strtoupper($value)]
        );
    }

    public function convitePastas(): HasMany
    {
        return $this->hasMany(ConvitePasta::class);
    }

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class);
    }

    public function pastas(): BelongsToMany
    {
        return $this->belongsToMany(Pasta::class, 'convite_pasta');
    }

    protected function urlNaoAceito()
    {
        return new Attribute(
            get: fn ($value, $attributes) => getBaseUrlFrontEnd()."/criar-conta/{$this->id}",
        );
    }
}
