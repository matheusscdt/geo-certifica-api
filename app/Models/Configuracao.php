<?php

namespace App\Models;

use App\Enums\LembreteDocumentoEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Configuracao extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'configuracao';

    protected $fillable = [
        'documento_id',
        'data_limite_assinatura',
        'lembrete_documento',
        'data_limite_lembrete'
    ];

    public $casts = [
        'data_limite_assinatura' => 'date',
        'lembrete_documento' => LembreteDocumentoEnum::class
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function calcularDataLimiteLembrete(): Carbon
    {
        return now()->addDays($this->lembrete_documento->value);
    }
}
