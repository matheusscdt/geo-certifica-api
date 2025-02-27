<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class UserAgenda extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'user_agenda';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'agenda_id',
    ];

    public $casts = [
        'agenda_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }

    protected function userAgendaLogado(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => auth()->id() === $this->user_id
        );
    }
}
