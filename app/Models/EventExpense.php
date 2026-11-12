<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheduled_event_id',
        'expense_name',
        'item_name',
        'category',
        'vendor_name',
        'amount',
        'status',
        'is_paid',
        'due_date',
        'payment_date',
        'paid_at',
        'payment_proof',
        'invoice_proof',
        'payment_method',
        'notes',
    ];

    /**
     * Backward / View compatibility accessors & mutators
     */
    public function getItemNameAttribute(): string
    {
        return $this->attributes['expense_name'] ?? '';
    }

    public function setItemNameAttribute($value): void
    {
        $this->attributes['expense_name'] = $value;
    }

    public function getIsPaidAttribute(): bool
    {
        return ($this->attributes['status'] ?? 'unpaid') === 'paid';
    }

    public function setIsPaidAttribute($value): void
    {
        $this->attributes['status'] = $value ? 'paid' : 'unpaid';
    }

    public function getPaidAtAttribute()
    {
        return $this->payment_date;
    }

    public function setPaidAtAttribute($value): void
    {
        $this->payment_date = $value;
    }

    public function getInvoiceProofAttribute(): ?string
    {
        return $this->attributes['payment_proof'] ?? null;
    }

    public function setInvoiceProofAttribute($value): void
    {
        $this->attributes['payment_proof'] = $value;
    }

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'payment_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(ScheduledEvent::class, 'scheduled_event_id');
    }

    public static function getCategories(): array
    {
        return [
            'sound' => [
                'label' => 'Sound System & Multimedia',
                'badge' => 'bg-indigo-600',
                'icon' => 'volume-2'
            ],
            'stage' => [
                'label' => 'Panggung, Tenda & Lighting',
                'badge' => 'bg-amber-600',
                'icon' => 'sun'
            ],
            'catering' => [
                'label' => 'Konsumsi & Catering',
                'badge' => 'bg-emerald-600',
                'icon' => 'utensils'
            ],
            'logistics' => [
                'label' => 'Transportasi & Logistik',
                'badge' => 'bg-blue-600',
                'icon' => 'truck'
            ],
            'crew' => [
                'label' => 'Honor Kru Freelance / Talent',
                'badge' => 'bg-purple-600',
                'icon' => 'users'
            ],
            'venue' => [
                'label' => 'Sewa Venue & Perizinan',
                'badge' => 'bg-rose-600',
                'icon' => 'map-pin'
            ],
            'other' => [
                'label' => 'Lain-lain',
                'badge' => 'bg-slate-600',
                'icon' => 'more-horizontal'
            ],
        ];
    }

    public function getCategoryDetailsAttribute(): array
    {
        $cats = self::getCategories();
        if (isset($cats[$this->category])) {
            return $cats[$this->category];
        }

        foreach ($cats as $key => $cat) {
            if (strcasecmp($cat['label'], (string) $this->category) === 0 || strcasecmp($key, (string) $this->category) === 0) {
                return $cat;
            }
        }

        return [
            'label' => ucfirst($this->category ?? 'Lain-lain'),
            'badge' => 'bg-slate-600',
            'icon' => 'tag'
        ];
    }
}
