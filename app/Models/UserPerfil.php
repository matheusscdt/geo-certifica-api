<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class UserPerfil extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'user_perfil';

    protected $fillable = [
        'user_id',
        'perfil_id',
        'perfil_principal',
        'gestor',
        'ativo'
    ];

    protected $casts = [
        'perfil_principal' => 'boolean',
        'gestor' => 'boolean',
        'ativo' => 'boolean'
    ];

    protected function perfilAtivo(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => (getPerfilId() == $this->perfil_id),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class);
    }

    public function pastas(): BelongsToMany
    {
        return $this->belongsToMany(Pasta::class, 'user_perfil_pasta');
    }
}
