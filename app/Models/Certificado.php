<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Certificado extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    public $table = 'certificado';

    protected $fillable = [
        'perfil_id',
        'nome',
        'organizacao',
        'unidade_organizacional',
        'data_validade_inicio',
        'data_validade_fim',
        'password',
        'info',
        'content_file',
        'selecionado'
    ];

    public $casts = [
        'data_validade_inicio' => 'datetime',
        'data_validade_fim' => 'datetime',
        'info' => 'array',
        'selecionado' => 'bool'
    ];

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class);
    }

    public function diasVencimento(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => (int) now()->diffInDays($this->data_validade_fim)
        );
    }

    protected function password(): Attribute
    {
        return new Attribute(
            set: fn ($value) => ['password' => Crypt::encryptString($value)]
        );
    }

    public function passwordDecrypted(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => Crypt::decryptString($this->password)
        );
    }

    public function contentFileBase64(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => Crypt::decryptString($this->content_file)
        );
    }

    public function contentFileBlob(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => base64_decode($this->content_file_base64)
        );
    }

    public function filename(): Attribute
    {
        $nome = str_replace(' ', '_', $this->nome);
        $fileName = "{$nome}.pfx";
        return new Attribute(
            get: fn ($value, $attributes) => $fileName
        );
    }

    public function arquivo(): MorphOne
    {
        return $this->morphOne(Arquivo::class, 'related');
    }

    public function registrosAssinaturaInterna(): HasMany
    {
        return $this->hasMany(RegistroAssinaturaInterna::class);
    }
}
