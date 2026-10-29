<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCrew extends Model
{
    use HasFactory;

    protected $table = 'event_crew';

    protected $fillable = [
        'scheduled_event_id',
        'user_id',
        'crew_type',
        'external_name',
        'external_phone',
        'role_in_event',
        'notes',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(ScheduledEvent::class, 'scheduled_event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExternal(): bool
    {
        return $this->crew_type === 'external';
    }

    public function isInternal(): bool
    {
        return $this->crew_type === 'internal';
    }

    public function getCrewNameAttribute(): string
    {
        if ($this->isExternal()) {
            return $this->external_name ?? 'Kru Eksternal';
        }
        return $this->user?->name ?? 'Karyawan Terhapus';
    }

    public function getCrewPhoneAttribute(): ?string
    {
        if ($this->isExternal()) {
            return $this->external_phone;
        }
        return $this->user?->phone;
    }
}
