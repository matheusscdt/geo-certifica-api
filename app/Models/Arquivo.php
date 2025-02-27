<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Arquivo extends Model implements Transformable
{
    use TransformableTrait, HasUuids;

    protected $table = "arquivo";

    protected $fillable = [
        'nome',
        'arquivo',
        'extensao',
        'mime_type',
        'tamanho'
    ];

    protected $casts = [
        'tamanho' => 'int'
    ];

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function getArquivoWithBucket(): string
    {
        return env('MINIO_BUCKET').$this->arquivo;
    }

    public function getUrlAttribute(): string
    {
        return Storage::temporaryUrl($this->getArquivoWithBucket(), now()->addMinutes(60));
    }

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class, 'related_id');
    }

    public function getHashSha256(): string
    {
        $file = Storage::get($this->getArquivoWithBucket());
        return hash('sha256', $file);
    }

    public function adicionarTextoConcatenadoNomeArquivo(string $texto): string
    {
        $nome = explode('.', $this->nome)[0];
        return "{$nome}_{$texto}.{$this->extensao}";
    }

    public function concatenarAssinadoEmNome(): string
    {
        return $this->adicionarTextoConcatenadoNomeArquivo('assinado');
    }

    public function getContent(): ?string
    {
        return Storage::get($this->getArquivoWithBucket());
    }

    public function registroAssinaturaInterna(): BelongsTo
    {
        return $this->belongsTo(RegistroAssinaturaInterna::class, 'related_id');
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class, 'related_id');
    }
}
