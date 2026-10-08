<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;


class PatientProfile extends Model
{
    use HasFactory;

    public const CREATED_AT = null;
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'sex',
        'address',
    ];
    protected function dateOfBirth(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }
    protected $casts = [
        'date_of_birth' => 'date:Y-m-d',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medicalTickets(): HasMany
    {
        return $this->hasMany(
            MedicalTicket::class,
            'patient_id'
        );
    }
}
