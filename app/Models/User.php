<?php

namespace App\Models;


use Filament\Panel;
use App\Models\Event;
use App\Models\Startup;
use App\Models\EventUser;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasAvatar;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasEmailAuthentication

{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // <-- allow access to all panels
    }

    //Profile Avatar
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url
            ? asset('storage/' . $this->avatar_url)
            : asset('storage/default/user.png');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'avatar_url',
        'name',
        'email',
        'contact',
        'company',
        'password',
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
            'has_email_authentication' => 'boolean',
        ];
    }

    public function hasEmailAuthentication(): bool
    { 
        return $this->has_email_authentication;
    }

    public function toggleEmailAuthentication(bool $condition): void
    {
        $this->has_email_authentication = $condition;
        $this->save();
    }

    public function startups()
    {
        return $this->hasMany(Startup::class);
    }

    public function attendedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_user')
                    ->using(EventUser::class)
                    ->withPivot('is_attending')
                    ->withTimestamps();
    }
}
