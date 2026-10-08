<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabReport extends Model
{
    use HasFactory;

    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected $fillable = [
        'ticket_id',
        'file_path',
        'file_type',
        'processing_status',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            MedicalTicket::class,
            'ticket_id'
        );
    }
}
