<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheduled_event_id',
        'payment_name',
        'amount',
        'payment_date',
        'payment_method',
        'reference_no',
        'status',
        'receipt_proof',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(ScheduledEvent::class, 'scheduled_event_id');
    }
}
