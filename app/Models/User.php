<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
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
        'contact_number',
        'address',
        'role'
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

    public function rating()
    {
        return $this->hasOne(Rating::class, 'user_id', 'id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'user_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'user_id', 'id');
    }

    public function packageFeedback()
    {
        return $this->hasMany(PackageFeedback::class, 'user_id', 'id');
    }

    public function packageFeedbackReply()
    {
        return $this->hasMany(PackageFeedbackReply::class, 'user_id', 'id');
    }

    public function packageRating()
    {
        return $this->hasOne(PackageRating::class, 'user_id', 'id');
    }

    public function inquiry()
    {
        return $this->hasMany(Inquiry::class, 'user_id', 'id');
    }
}
