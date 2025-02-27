<?php

namespace App\Models;

use App\Enums\StatusDocumentoEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Mail\Mailables\Attachment;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Assinatura extends Model implements Transformable
{
    use TransformableTrait, HasUuids;

    public $table = 'assinatura';

    protected $fillable = [
        'autorizacao_id',
        'cpf',
        'data_nascimento',
        'data_assinatura',
        'ip_address',
        'dispositivo'
    ];

    public $casts = [
        'data_nascimento' => 'date',
        'data_assinatura' => 'datetime'
    ];

    public function autorizacao(): BelongsTo
    {
        return $this->belongsTo(Autorizacao::class);
    }

    public function documento(): Documento
    {
        return $this->autorizacao->destinatario->documento;
    }

    public function arquivos()
    {
        return $this->autorizacao->destinatario->documento->arquivos;
    }

    public function finalizarDocumento(): bool
    {
        return $this->documento()->update([
            'status_documento' => StatusDocumentoEnum::Finalizado
        ]);
    }

    public function arquivosFinalizados(): array
    {
        return $this->documento()->registrosAssinaturaInterna->map(function (RegistroAssinaturaInterna $registroAssinaturaInterna) {
            return [
                $registroAssinaturaInterna->arquivoOriginal,
                $registroAssinaturaInterna->arquivo
            ];
        })->flatten()->map(function (Arquivo $arquivo) {
            return Attachment::fromStorageDisk('minio', $arquivo->getArquivoWithBucket())
                ->as($arquivo->nome)
                ->withMime($arquivo->mime_type);
        })->toArray();
    }
}
