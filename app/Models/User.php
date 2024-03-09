<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use App\Observers\UserObserver;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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
        'id' => 'integer',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        //        return str_ends_with($this->email, '@pareteum.com') && $this->hasVerifiedEmail();
        return true;
    }

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the user's name.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => implode(' ', [$attributes['first_name'], $attributes['last_name']]),
        );
    }
}
