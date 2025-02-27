<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements CanResetPassword, JWTSubject, Transformable
{
    use HasFactory, Notifiable, SoftDeletes, TransformableTrait, HasUuids;

    protected $fillable = [
        'pessoa_id',
        'email',
        'password',
        'remember_token',
        'gestor',
        'ativo'
    ];

    protected $casts = [
        'pessoa_id' => 'int',
        'gestor' => 'bool',
        'ativo' => 'bool'
    ];

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'perfis' => $this->getPerfisToken()
        ];
    }
    private function getPerfisToken(): Collection
    {
        return $this->userPerfilAtivo()->map(function ($userPerfil) {
            return [
                'id' => $userPerfil->perfil_id,
                'nome' => $userPerfil->perfil->nome,
                'perfil_principal' => $userPerfil->perfil_principal,
                'arquivo_id' => $userPerfil->perfil->arquivo?->id
            ];
        })->values();
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    protected function password(): Attribute
    {
        return new Attribute(
            set: fn ($value) => ['password' => Hash::make($value)]
        );
    }

    public function userAgenda(): HasOne
    {
        return $this->hasOne(UserAgenda::class);
    }

    public function userPerfil(): HasMany
    {
        return $this->hasMany(UserPerfil::class);
    }

    public function createUserPerfil($perfilId, bool $perfilPrincipal, bool $gestor = false): void
    {
        $this->userPerfil()->create([
            'perfil_id' => $perfilId,
            'perfil_principal' => $perfilPrincipal,
            'gestor' => $gestor,
            'ativo' => true
        ]);
    }

    public function userPerfilAtivo(): Collection
    {
        return $this->userPerfil->where('ativo', true)->transform(function (UserPerfil $userPerfil) {
            $userPerfil->perfil_ativo = $userPerfil->perfil->perfil_ativo;
            return $userPerfil;
        });
    }

    public function userPerfilVinculado(): ?UserPerfil
    {
        return $this->userPerfil->where('perfil_id', getPerfilId())->first();
    }

    public function perfis()
    {
        return $this->userPerfil->map->perfil;
    }

    public function perfilUserLogadoValido(?string $perfilId): bool
    {
        if (is_null($perfilId)) {
            return false;
        }

        return auth()->user()->userPerfil->where('perfil_id', $perfilId)->isNotEmpty();
    }

    public function vincularPerfil(string $perfilId, bool $perfilPrincipal, bool $gestor): void
    {
        $this->userPerfil()->create([
            'perfil_id' => $perfilId,
            'perfil_principal' => $perfilPrincipal,
            'gestor' => $gestor
        ]);
    }

    public function vincularPastasPorConvite(string $perfilId, Convite $convite): void
    {
        $this->userPerfil->where('perfil_id', $perfilId)->first()->pastas()->sync($convite->pastas->pluck('id'));
    }

    public function userAtivacoes(): HasMany
    {
        return $this->hasMany(UserAtivacao::class);
    }

    public function getUserAtivacao(): ?UserAtivacao
    {
        return $this->userAtivacoes->whereNull('data_ativacao')->first();
    }

    protected function userAtivacao(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $this->getUserAtivacao()
        );
    }

    public function gerarCodigoAtivacao()
    {
        return $this->userAtivacoes()->create(['codigo' => gerarCodigo()]);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($this, $token));
    }

    public function isPerfilAtivoPrincipal(): bool
    {
        return $this->userPerfilAtivo()->where('perfil_ativo', true)->where('perfil_principal', true)->isNotEmpty();
    }

    public function perfilPrincipal(): UserPerfil
    {
        return $this->userPerfil->where('perfil_principal', true)->first();
    }

    public function isGestorPerfilAtivo(): bool
    {
        return $this->userPerfilAtivo()->where('perfil_ativo', true)->where('gestor', true)->isNotEmpty();
    }

    public function gestorPerfilAtivo(): UserPerfil
    {
        return $this->userPerfilAtivo()->where('perfil_ativo', true)->where('gestor', true)->first();
    }

    public function pastasPerfil(): Collection
    {
        return $this->userPerfilAtivo()->where('perfil_ativo', true)->first()->pastas;
    }
}
