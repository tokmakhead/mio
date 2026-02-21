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
        'role',
        'is_master',
        'master_role',
        'demo_readonly',
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
            'password' => 'hashed',
            'is_master' => 'boolean',
            'demo_readonly' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is master
     */
    public function isMaster(): bool
    {
        return (bool) $this->is_master;
    }

    /**
     * Check for specific Master Panel permissions
     */
    public function hasMasterPermission($permission): bool
    {
        if (!$this->isMaster())
            return false;

        $role = $this->master_role ?? 'super_admin'; // Default existing ones to super for safety

        if ($role === 'super_admin')
            return true;

        $permissions = [
            'manager' => [
                'manage_licenses',
                'manage_releases',
                'view_logs',
            ],
            'support' => [
                'view_licenses',
                'manage_announcements',
            ]
        ];

        $allowed = $permissions[$role] ?? [];

        // Handle plural/singular or specific actions
        return in_array($permission, $allowed);
    }
}
