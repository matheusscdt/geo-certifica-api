<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ConvitePasta extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'convite_pasta';

    protected $fillable = [
        'convite_id',
        'pasta_id'
    ];

    protected $casts = [
        'convite_id' => 'integer'
    ];

    public function convite(): BelongsTo
    {
        return $this->belongsTo(Convite::class);
    }

    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class);
    }
}
