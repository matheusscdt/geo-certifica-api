<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class MensagemTemplate extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'mensagem_template';

    protected $fillable = [
        'perfil_id',
        'nome',
        'assunto',
        'mensagem'
    ];

    protected function nome(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['nome'],
            set: fn ($value) => ['nome' => mb_strtoupper($value)]
        );
    }

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class);
    }
}
