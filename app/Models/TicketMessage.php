<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketMessage extends Model
{
    use HasFactory;

    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected $fillable = [
        'ticket_id',
        'sender_user_id',
        'reply_to_id',
        'message_type',
        'content',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            MedicalTicket::class,
            'ticket_id'
        );
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'sender_user_id'
        );
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(
            TicketMessage::class,
            'reply_to_id'
        );
    }

    public function replies(): HasMany
    {
        return $this->hasMany(
            TicketMessage::class,
            'reply_to_id'
        );
    }
}
