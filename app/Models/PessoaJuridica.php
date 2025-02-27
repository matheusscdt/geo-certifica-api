<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class PessoaJuridica extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'pessoa_juridica';

    protected $fillable = [
        'pessoa_id',
        'cnpj'
    ];

    protected $casts = [
        'pessoa_id' => 'int'
    ];

    protected function cnpj(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['cnpj'],
            set: fn ($value) => ['cnpj' => sanitizeCnpj($value)]
        );
    }

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }
}
