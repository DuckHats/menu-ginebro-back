<?php

namespace App\Models;

use App\Contracts\Exportable;
use App\Contracts\Importable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements Exportable, Importable
{
    const STATUS_INACTIVE = 0;

    const STATUS_ACTIVE = 1;

    const ROLE_ADMIN = 1;

    const ROLE_COOK = 3;

    const ROLE_USER = 2;

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

    public function importRow(array $data): void
    {
        self::updateOrCreate(
            ['email' => $data['email']], // Clau única
            [
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'password' => bcrypt($data['password']),
                'user_type_id' => $data['user_type_id'],
                'status' => $data['status'],
            ]
        );
    }

    public function getImportValidationRules(): array
    {
        return [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'user_type_id' => 'required|exists:user_types,id',
            'status' => 'required|in:0,1',
        ];
    }
}
