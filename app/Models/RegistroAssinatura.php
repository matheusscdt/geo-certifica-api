<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class RegistroAssinatura extends Model implements Transformable
{
    use TransformableTrait, HasUuids;

    public $table = 'registro_assinatura';

    protected $fillable = [
        'documento_id',
        'assinatura_id',
        'ordem',
        'hash'
    ];

    protected $casts = [
        'ordem' => 'int'
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function assinatura(): BelongsTo
    {
        return $this->belongsTo(Assinatura::class);
    }

    public function arquivoAssinado(): MorphOne
    {
        return $this->morphOne(Arquivo::class, 'related');
    }
}
