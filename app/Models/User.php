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
