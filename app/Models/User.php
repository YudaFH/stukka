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
        'roles',
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
            'roles' => 'array',
        ];
    }

    public static function getRoles(): array
    {
        return [
            self::ROLE_FULL_ACCESS => [
                'name' => 'Full Access',
                'label' => 'Full Access (Semua Fitur)',
                'desc' => 'Akses penuh ke semua modul admin CMS, portofolio, keuangan, karyawan, rekap absensi, serta presensi biometrik wajah.',
                'badge_bg' => 'bg-purple-600',
                'badge_border' => 'border-purple-700',
                'badge_text' => 'text-white',
            ],
            self::ROLE_EVENT_FINANCE => [
                'name' => 'Event & Finansial',
                'label' => 'Event & Finansial',
                'desc' => 'Kelola jadwal event, alokasi kru, pembayaran klien, pengeluaran & laporan keuangan, serta presensi biometrik wajah.',
                'badge_bg' => 'bg-emerald-600',
                'badge_border' => 'border-emerald-700',
                'badge_text' => 'text-white',
            ],
            self::ROLE_CONTENT_MANAGEMENT => [
                'name' => 'Content Management',
                'label' => 'Content Management',
                'desc' => 'Kelola portofolio project, logo client trust, testimoni klien, serta presensi biometrik wajah.',
                'badge_bg' => 'bg-blue-600',
                'badge_border' => 'border-blue-700',
                'badge_text' => 'text-white',
            ],
            self::ROLE_FIELD_STAFF => [
                'name' => 'Kru Lapangan',
                'label' => 'Kru Lapangan (Staff Biasa)',
                'desc' => 'Staff operasional lapangan (jadwal tugas event & presensi biometrik wajah GPS tanpa akses admin CMS).',
                'badge_bg' => 'bg-slate-600',
                'badge_border' => 'border-slate-700',
                'badge_text' => 'text-white',
            ],
        ];
    }

    /**
     * Get list of roles assigned to this user as an array.
     */
    public function getRolesListAttribute(): array
    {
        if (!empty($this->roles)) {
            if (is_array($this->roles)) {
                return $this->roles;
            }
            $decoded = json_decode($this->roles, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        if (!empty($this->role)) {
            $parts = array_filter(array_map('trim', explode(',', $this->role)));
            if (!empty($parts)) {
                return array_values($parts);
            }
        }

        if ($this->isAdmin()) {
            return [self::ROLE_FULL_ACCESS];
        }

        if ($this->isKaryawan()) {
            return [self::ROLE_FIELD_STAFF];
        }

        return [];
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        if ($this->isFullAccess()) {
            return true;
        }

        return in_array($role, $this->roles_list, true);
    }

    public function getRoleNameAttribute(): string
    {
        if ($this->id === 1 || ($this->isAdmin() && empty($this->role) && empty($this->roles))) {
            return 'Full Access';
        }

        $allRoles = self::getRoles();
        $names = [];
        foreach ($this->roles_list as $r) {
            if (isset($allRoles[$r]['name'])) {
                $names[] = $allRoles[$r]['name'];
            }
        }

        return !empty($names) ? implode(' + ', $names) : ($this->isAdmin() ? 'Full Access' : 'Kru Lapangan');
    }

    public function isFullAccess(): bool
    {
        if ($this->role === self::ROLE_FULL_ACCESS) {
            return true;
        }

        if (in_array(self::ROLE_FULL_ACCESS, $this->roles_list, true)) {
            return true;
        }

        return $this->usertype === 'admin' && empty($this->role) && empty($this->roles);
    }

    public function canManageContent(): bool
    {
        return $this->isFullAccess() || in_array(self::ROLE_CONTENT_MANAGEMENT, $this->roles_list, true);
    }

    public function canManageEventFinance(): bool
    {
        return $this->isFullAccess() || in_array(self::ROLE_EVENT_FINANCE, $this->roles_list, true);
    }

    public function canManageEmployees(): bool
    {
        return $this->isFullAccess();
    }

    public function canManageAttendance(): bool
    {
        return $this->isFullAccess() || in_array(self::ROLE_EVENT_FINANCE, $this->roles_list, true);
    }

    public function hasAdminAccess(): bool
    {
        return $this->isFullAccess() 
            || in_array(self::ROLE_CONTENT_MANAGEMENT, $this->roles_list, true) 
            || in_array(self::ROLE_EVENT_FINANCE, $this->roles_list, true);
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
        return $this->hasOne(Attendance::class)->whereDate('date', now()->toDateString());
    }

    public function assignedEvents()
    {
        return $this->belongsToMany(ScheduledEvent::class, 'event_crew')
            ->withPivot(['id', 'role_in_event', 'notes'])
            ->withTimestamps();
    }
}
