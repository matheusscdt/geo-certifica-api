<?php

namespace App\Models;

use App\Enums\StatusDocumentoEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Documento extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes, HasUuids;

    public $table = 'documento';

    protected $fillable = [
        'pasta_id',
        'status_documento'
    ];

    public $casts = [
        'pasta_id' => 'int',
        'status_documento' => StatusDocumentoEnum::class
    ];

    public function pasta(): BelongsTo
    {
        return $this->belongsTo(Pasta::class);
    }

    public function arquivo(): MorphOne
    {
        return $this->morphOne(Arquivo::class, 'related');
    }

    public function destinatarios(): HasMany
    {
        return $this->hasMany(Destinatario::class);
    }

    public function mensagem(): HasOne
    {
        return $this->hasOne(Mensagem::class);
    }

    public function configuracao(): HasOne
    {
        return $this->hasOne(Configuracao::class);
    }

    public function registrosAssinaturaInterna(): HasMany
    {
        return $this->hasMany(RegistroAssinaturaInterna::class);
    }

    public function arquivos(): MorphMany
    {
        return $this->morphMany(Arquivo::class, 'related');
    }

    public function permitirStatusEmProcesso(): bool
    {
        if ($this->status_documento == StatusDocumentoEnum::Rascunho && $this->destinatarios->isNotEmpty() && !is_null($this->mensagem) && !is_null($this->configuracao)) {
            return true;
        }

        return false;
    }

    public function getQuantidadeAssinado(): int
    {
        return $this->destinatarios->filter(function ($destinatario) {
            return $destinatario->assinatura_realizada === true;
        })->count();
    }

    public function verificarTodosDestinatariosAssinaram(): bool
    {
        return $this->destinatarios->count() === $this->quantidade_assinado;
    }

    protected function quantidadeAssinado(): Attribute
    {
        return new Attribute(
            get: fn() => $this->getQuantidadeAssinado()
        );
    }

    protected function todosDestinatariosAssinaram(): Attribute
    {
        return new Attribute(
            get: fn() => $this->verificarTodosDestinatariosAssinaram()
        );
    }

    public function perfil(): Perfil
    {
        return $this->pasta->perfilPasta->perfil;
    }

    public function cancelar(): bool
    {
        return $this->update(['status_documento' => StatusDocumentoEnum::Cancelado]);
    }

    public function finalizar(): bool
    {
        return $this->update([
            'status_documento' => StatusDocumentoEnum::Finalizado
        ]);
    }
}
