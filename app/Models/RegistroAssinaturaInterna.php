<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegistroAssinaturaInterna extends Model implements Transformable
{
    use TransformableTrait, HasUuids;

    public $table = 'registro_assinatura_interna';

    protected $fillable = [
        'documento_id',
        'certificado_id',
        'arquivo_original_id',
        'hash'
    ];

    protected $casts = [
        'certificado_id' => 'int'
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function certificado(): BelongsTo
    {
        return $this->belongsTo(Certificado::class);
    }

    public function arquivoOriginal(): BelongsTo
    {
        return $this->belongsTo(Arquivo::class, 'arquivo_original_id');
    }

    public function arquivo(): MorphOne
    {
        return $this->morphOne(Arquivo::class, 'related');
    }

    protected function dataHoraAtualCompleta(): Attribute
    {
        return new Attribute(
            get: fn($value, $attributes) => getDataHoraAtualCompleta()
        );
    }

    public function getQrCode(int $tamanho = 102)
    {
        $url = $this->getUrlValidador();
        return QrCode::size($tamanho)->generate($url);
    }

    public function getUrlValidador(): string
    {
        $urlFrontEnd = env('APP_URL_FRONT_END');
        return "{$urlFrontEnd}/documentos/validador/{$this->id}";
    }
}
