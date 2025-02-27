<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Destinatario extends Model implements Transformable
{
    use TransformableTrait, HasUuids;

    public $table = 'destinatario';

    protected $fillable = [
        'documento_id',
        'agenda_id',
        'tipo_id'
    ];

    public $casts = [
        'agenda_id' => 'int',
        'tipo_id' => 'int'
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(Tipo::class);
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }

    public function autorizacoes(): HasMany
    {
        return $this->hasMany(Autorizacao::class);
    }

    public function autorizacaoAtivaNaoAutorizado(): ?Autorizacao
    {
        return $this->autorizacoes->where('autorizado', false)->sortByDesc('created_at')->first();
    }

    public function autorizacaoValida(): ?Autorizacao
    {
        return $this->autorizacoes->where('autorizado', true)->first();
    }

    public function assinatura(): Assinatura
    {
        return $this->autorizacaoValida()->assinatura;
    }

    protected function assinaturaRealizada(): Attribute
    {
        return new Attribute(
            get: fn($value, $attributes) => (bool)$this->autorizacaoValida()?->assinatura
        );
    }
}
