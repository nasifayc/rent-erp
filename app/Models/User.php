<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_DEPARTMENT_MANAGER = 'department_manager';

    public const ROLE_ADMIN_OFFICER = 'admin_officer';

    public const ROLE_LEGAL_REVIEWER = 'legal_reviewer';

    public const ROLE_FLEET_MANAGER = 'fleet_manager';

    public const ROLE_FINANCE_OFFICER = 'finance_officer';

    public const ROLE_DRIVER = 'driver';

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
            'is_admin' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin || in_array($this->role, self::allowedRoles(), true);
    }

    public static function allowedRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_DEPARTMENT_MANAGER,
            self::ROLE_ADMIN_OFFICER,
            self::ROLE_LEGAL_REVIEWER,
            self::ROLE_FLEET_MANAGER,
            self::ROLE_FINANCE_OFFICER,
            self::ROLE_DRIVER,
        ];
    }

    public function hasRole(string $role): bool
    {
        return $this->is_admin || $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->is_admin || in_array($this->role, $roles, true);
    }

    public function createdBranches(): HasMany
    {
        return $this->hasMany(Branch::class, 'created_by');
    }

    public function approvedAgreements(): HasMany
    {
        return $this->hasMany(OfficeRentAgreement::class, 'approved_by');
    }

    public function approvedServiceRequests(): HasMany
    {
        return $this->hasMany(VehicleServiceRequest::class, 'approved_by');
    }

    public function requestedServiceRequests(): HasMany
    {
        return $this->hasMany(VehicleServiceRequest::class, 'requested_by');
    }

    public function recordedUtilityPayments(): HasMany
    {
        return $this->hasMany(UtilityPayment::class, 'recorded_by');
    }

    public function recordedOfficeRentPayments(): HasMany
    {
        return $this->hasMany(OfficeRentPayment::class, 'recorded_by');
    }
}
