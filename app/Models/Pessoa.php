<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Pessoa extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'pessoa';

    protected $fillable = [
        'nome'
    ];

    protected function nome(): Attribute
    {
        return new Attribute(
            get: fn($value, $attributes) => convertToCamelCase($attributes['nome']),
            set: fn ($value) => ['nome' => mb_strtoupper($value)]
        );
    }

    public function pessoaFisica(): HasOne
    {
        return $this->hasOne(PessoaFisica::class);
    }

    public function pessoaJuridica(): HasOne
    {
        return $this->hasOne(PessoaJuridica::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
