<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
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
    ];

    public function bookings() {
        return $this->hasMany(Booking::class, 'user_id', 'id');
    }

    public function providers() {
        return $this->hasMany(Provider::class, 'user_id', 'id');
    }

    public function tutorings() {
        return $this->hasMany(Tutoring::class, 'tutor_id', 'id');
    }

    public function getSubjectsAttribute() {
        return collect($this->tutorings->all())->map(function ($tutoring) {
            return $tutoring->subject->name;
        })->unique();
    }

    public function favoriteTutors() {
        return $this->belongsToMany(User::class, 'tutor_favorites', 'user_id', 'tutor_id')->withPivot('is_favorite');
    }
}
