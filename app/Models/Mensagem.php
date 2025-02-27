<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Mensagem extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'mensagem';

    protected $fillable = [
        'documento_id',
        'assunto',
        'mensagem'
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }
}
