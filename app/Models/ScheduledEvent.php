<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduledEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'title',
        'event_type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'venue_detail',
        'client_name',
        'client_phone',
        'client_email',
        'contract_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'contract_amount' => 'decimal:2',
        ];
    }

    // =========================================================================
    // RELATIONS
    // =========================================================================

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function crewAssignments(): HasMany
    {
        return $this->hasMany(EventCrew::class);
    }

    public function crew(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_crew')
            ->withPivot(['id', 'role_in_event', 'notes'])
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(EventPayment::class)->orderBy('payment_date', 'asc');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(EventExpense::class)->orderBy('created_at', 'desc');
    }

    // =========================================================================
    // FINANCIAL CALCULATIONS & HELPERS
    // =========================================================================

    /**
     * Total pembayaran masuk dari klien yang sudah berstatus 'paid'
     */
    public function getTotalPaidAttribute(): float
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->where('status', 'paid')->sum('amount');
        }
        return (float) $this->payments()->where('status', 'paid')->sum('amount');
    }

    /**
     * Sisa Piutang Klien (Nilai Kontrak - Total yang sudah dibayar)
     */
    public function getRemainingReceivableAttribute(): float
    {
        return max(0, (float) $this->contract_amount - $this->total_paid);
    }

    /**
     * Persentase Pelunasan Klien (%)
     */
    public function getPaymentProgressPercentageAttribute(): int
    {
        if ($this->contract_amount <= 0) return 0;
        return (int) min(100, round(($this->total_paid / $this->contract_amount) * 100));
    }

    /**
     * Total seluruh estimasi/realisasi biaya produksi event
     */
    public function getTotalExpenseAttribute(): float
    {
        if ($this->relationLoaded('expenses')) {
            return (float) $this->expenses->sum('amount');
        }
        return (float) $this->expenses()->sum('amount');
    }

    /**
     * Biaya produksi yang sudah dibayarkan (Lunas ke vendor/kru)
     */
    public function getTotalPaidExpenseAttribute(): float
    {
        if ($this->relationLoaded('expenses')) {
            return (float) $this->expenses->where('status', 'paid')->sum('amount');
        }
        return (float) $this->expenses()->where('status', 'paid')->sum('amount');
    }

    /**
     * Hutang biaya produksi yang belum dibayar ke vendor
     */
    public function getRemainingExpenseAttribute(): float
    {
        if ($this->relationLoaded('expenses')) {
            return (float) $this->expenses->where('status', 'unpaid')->sum('amount');
        }
        return (float) $this->expenses()->where('status', 'unpaid')->sum('amount');
    }

    /**
     * Laba Bersih Proyek (Nilai Kontrak - Total Biaya Produksi)
     */
    public function getNetProfitAttribute(): float
    {
        return (float) $this->contract_amount - $this->total_expense;
    }

    /**
     * Margin Keuntungan Bersih (%)
     */
    public function getProfitMarginPercentageAttribute(): float
    {
        if ($this->contract_amount <= 0) return 0;
        return round(($this->net_profit / $this->contract_amount) * 100, 1);
    }

    /**
     * Status Pelunasan Pembayaran Klien
     */
    public function getClientPaymentStatusAttribute(): string
    {
        if ($this->contract_amount <= 0) return 'no_contract';
        if ($this->remaining_receivable <= 0) return 'fully_paid';
        if ($this->total_paid > 0) return 'partially_paid';
        return 'unpaid';
    }

    /**
     * Status Pelunasan Vendor Produksi
     */
    public function getVendorPaymentStatusAttribute(): string
    {
        if ($this->total_expense <= 0) return 'no_expenses';
        if ($this->remaining_expense <= 0) return 'fully_paid';
        if ($this->total_paid_expense > 0) return 'partially_paid';
        return 'unpaid';
    }
}
