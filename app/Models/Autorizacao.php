<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Autorizacao extends Model implements Transformable
{
    use TransformableTrait, HasUuids;

    public $table = 'autorizacao';

    protected $fillable = [
        'destinatario_id',
        'codigo',
        'data_validade',
        'autorizado'
    ];

    public $casts = [
        'codigo' => 'int',
        'data_validade' => 'datetime',
        'autorizado' => 'bool'
    ];

    public function destinatario(): BelongsTo
    {
        return $this->belongsTo(Destinatario::class);
    }

    public function dataValidadeValida(): bool
    {
        return now()->lessThan($this->data_validade);
    }

    public function assinatura(): HasOne
    {
        return $this->hasOne(Assinatura::class);
    }
}
