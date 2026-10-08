<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory;

    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected $fillable = [
        'type',
        'message',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'notification_users',
            'notification_id',
            'user_id'
        )
            ->using(NotificationUser::class)
            ->withPivot([
                'is_read',
                'read_at',
            ]);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(
            NotificationUser::class,
            'notification_id'
        );
    }
}
