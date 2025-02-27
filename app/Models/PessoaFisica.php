<?php

namespace App\Models;

use geekcom\ValidatorDocs\Rules\Cpf;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class PessoaFisica extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'pessoa_fisica';

    protected $fillable = [
        'pessoa_id',
        'cpf',
        'data_nascimento'
    ];

    protected $casts = [
        'pessoa_id' => 'int',
        'data_nascimento' => 'date'
    ];

    protected function cpf(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['cpf'],
            set: fn ($value) => ['cpf' => sanitizeCpf($value)]
        );
    }

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }
}
