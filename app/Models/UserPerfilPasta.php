<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class UserPerfilPasta extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'perfil_perfil_pasta';

    public $timestamps = false;

    protected $fillable = [
        'user_perfil_id',
        'pasta_id',
    ];

    public $casts = [
        'user_perfil_id' => 'int',
        'pasta_id' => 'int'
    ];

    public function userPerfil(): BelongsTo
    {
        return $this->belongsTo(UserPerfil::class);
    }

    public function pasta(): BelongsTo
    {
        return $this->belongsTo(Pasta::class);
    }
}
