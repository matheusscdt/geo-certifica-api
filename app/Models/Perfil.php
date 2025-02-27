<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Perfil extends Model implements Transformable
{
    use TransformableTrait, HasUuids;

    public $table = 'perfil';

    protected $fillable = [
        'nome',
        'proprietario'
    ];

    protected $casts = [
        'proprietario' => 'bool'
    ];

    protected function nome(): Attribute
    {
        return new Attribute(
            get: fn($value, $attributes) => convertToCamelCase($attributes['nome']),
            set: fn ($value) => ['nome' => mb_strtoupper($value)]
        );
    }

    public function perfilAgenda(): HasMany
    {
        return $this->hasMany(PerfilAgenda::class);
    }

    public function perfilPasta(): HasMany
    {
        return $this->hasMany(PerfilPasta::class);
    }

    public function createPerfilGeral()
    {
        return $this->perfilPasta()->pasta()->create([
            'nome' => 'Geral'
        ]);
    }

    public function arquivo(): MorphOne
    {
        return $this->morphOne(Arquivo::class, 'related');
    }

    public function userPerfis(): HasMany
    {
        return $this->hasMany(UserPerfil::class);
    }

    protected function perfilPrincipal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $this->userPerfis->where('user_id', auth()->id())->first()?->perfil_principal
        );
    }

    protected function perfilAtivo(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $this->userPerfis->where('user_id', auth()->id())->first()?->perfil_id == getPerfilId()
        );
    }
}
