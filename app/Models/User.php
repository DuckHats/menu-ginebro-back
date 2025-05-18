<?php

namespace App\Models;

use App\Contracts\Exportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;

class User extends Authenticatable implements Exportable
{
    const STATUS_INACTIVE = 0;

    const STATUS_ACTIVE = 1;

    const ROLE_ADMIN = 1;

    const ROLE_COOK = 2;

    const ROLE_USER = 3;

    // Per a l'usuari normal
    // const ROLE_USER = 'usuari';

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'user_type_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function isAdmin()
    {
        return $this->user_type_id == self::ROLE_ADMIN;
    }

    public function isCook()
    {
        return $this->user_type_id == self::ROLE_COOK;
    }

    public function isUser()
    {
        return $this->user_type_id == self::ROLE_USER;
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isInactive()
    {
        return $this->status == self::STATUS_INACTIVE;
    }

    public function getExportData(): Collection
    {
        return $this->newQuery()
            ->with('userType')
            ->get()
            ->map(function ($user) {
                return [
                    'ID' => $user->id,
                    'Nom' => $user->name,
                    'Cognom' => $user->last_name,
                    'Email' => $user->email,
                    'Tipus' => $user->userType->name ?? 'N/A',
                    'Status' => $user->status == self::STATUS_ACTIVE ? 'Active' : 'Inactive',
                ];
            });
    }

    public function getExportHeadings(): array
    {
        return [
            'ID',
            'Nom',
            'Cognom',
            'Email',
            'Tipus',
            'Status',
        ];
    }
}
