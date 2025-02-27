<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Pasta extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'pasta';

    protected $fillable = [
        'nome',
    ];

    protected function nome(): Attribute
    {
        return new Attribute(
            get: fn($value, $attributes) => convertToCamelCase($attributes['nome']),
            set: fn ($value) => ['nome' => mb_strtoupper($value)]
        );
    }

    public function perfilPasta(): HasOne
    {
        return $this->hasOne(PerfilPasta::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function calcularTamanhoMegaBytes(): float
    {
        $tamanho = $this->documentos->map->arquivos->flatten()->sum('tamanho');
        return round($tamanho / 1000, 2);
    }

    protected function tamanho(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $this->calcularTamanhoMegaBytes()
        );
    }
}
