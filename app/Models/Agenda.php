<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Agenda extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'agenda';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf'
    ];

    protected function nome(): Attribute
    {
        return new Attribute(
            get: fn($value, $attributes) => convertToCamelCase($attributes['nome']),
            set: fn ($value) => ['nome' => mb_strtoupper($value)]
        );
    }

    public function userAgenda(): HasOne
    {
        return $this->hasOne(UserAgenda::class);
    }

    public function perfilAgenda(): HasOne
    {
        return $this->hasOne(PerfilAgenda::class);
    }

    public function destinatarios(): HasMany
    {
        return $this->hasMany(Destinatario::class);
    }

    public function existeAgendaPorPerfil($perfilId): bool
    {
        return $this->perfilAgenda->perfil_id === $perfilId;
    }
}
