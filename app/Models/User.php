<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_FULL_ACCESS = 'full_access';
    public const ROLE_CONTENT_MANAGEMENT = 'content_management';
    public const ROLE_EVENT_FINANCE = 'event_finance';
    public const ROLE_FIELD_STAFF = 'field_staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'usertype',
        'role',
        'nip',
        'position',
        'face_descriptor',
        'face_photo',
        'face_registered_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'face_registered_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getRoles(): array
    {
        return [
            self::ROLE_FULL_ACCESS => [
                'name' => 'Full Access',
                'label' => 'Full Access (Semua Fitur)',
                'desc' => 'Akses penuh ke semua menu admin, portofolio, keuangan, karyawan, dan absensi.',
                'badge_bg' => 'bg-purple-600',
                'badge_border' => 'border-purple-700',
                'badge_text' => 'text-white',
            ],
            self::ROLE_EVENT_FINANCE => [
                'name' => 'Event & Finansial',
                'label' => 'Event & Finansial',
                'desc' => 'Mengelola jadwal acara, penugasan kru, pembayaran klien, dan keuangan event.',
                'badge_bg' => 'bg-emerald-600',
                'badge_border' => 'border-emerald-700',
                'badge_text' => 'text-white',
            ],
            self::ROLE_CONTENT_MANAGEMENT => [
                'name' => 'Content Management',
                'label' => 'Content Management',
                'desc' => 'Mengelola portofolio project, logo client trust, dan testimoni klien.',
                'badge_bg' => 'bg-blue-600',
                'badge_border' => 'border-blue-700',
                'badge_text' => 'text-white',
            ],
            self::ROLE_FIELD_STAFF => [
                'name' => 'Kru Lapangan',
                'label' => 'Kru Lapangan (Staff Biasa)',
                'desc' => 'Hanya akses Portal Karyawan untuk absensi selfie GPS & melihat jadwal tugas event.',
                'badge_bg' => 'bg-slate-600',
                'badge_border' => 'border-slate-700',
                'badge_text' => 'text-white',
            ],
        ];
    }

    public function getRoleNameAttribute(): string
    {
        if ($this->isAdmin() && empty($this->role)) {
            return 'Full Access';
        }
        $roles = self::getRoles();
        return $roles[$this->role]['name'] ?? ($this->isAdmin() ? 'Full Access' : 'Kru Lapangan');
    }

    public function isFullAccess(): bool
    {
        return $this->usertype === 'admin' || $this->role === self::ROLE_FULL_ACCESS;
    }

    public function canManageContent(): bool
    {
        return $this->isFullAccess() || $this->role === self::ROLE_CONTENT_MANAGEMENT;
    }

    public function canManageEventFinance(): bool
    {
        return $this->isFullAccess() || $this->role === self::ROLE_EVENT_FINANCE;
    }

    public function canManageEmployees(): bool
    {
        return $this->isFullAccess();
    }

    public function canManageAttendance(): bool
    {
        return $this->isFullAccess() || $this->role === self::ROLE_EVENT_FINANCE;
    }

    public function hasAdminAccess(): bool
    {
        return $this->isFullAccess() 
            || $this->role === self::ROLE_CONTENT_MANAGEMENT 
            || $this->role === self::ROLE_EVENT_FINANCE;
    }

    public function isAdmin(): bool
    {
        return $this->usertype === 'admin';
    }

    public function isKaryawan(): bool
    {
        return $this->usertype === 'karyawan';
    }

    public function isUser(): bool
    {
        return $this->usertype === 'user';
    }

    public function hasRegisteredFace(): bool
    {
        return !empty($this->face_descriptor);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function todayAttendance()
    {
        return $this->hasOne(Attendance::class)->where('date', now()->toDateString());
    }

    public function assignedEvents()
    {
        return $this->belongsToMany(ScheduledEvent::class, 'event_crew')
            ->withPivot(['id', 'role_in_event', 'notes'])
            ->withTimestamps();
    }
}
