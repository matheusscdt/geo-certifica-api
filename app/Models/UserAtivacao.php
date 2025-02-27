<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class UserAtivacao extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'user_ativacao';

    protected $fillable = [
        'user_id',
        'codigo',
        'data_ativacao'
    ];

    protected $casts = [
        'codigo' => 'integer',
        'data_ativacao' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
