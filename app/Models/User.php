<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'password',
        'birthday',
        'height',
        'weight',
        'recommended_calories',
        'lose_or_gain',
        'goal_weight',
        'admin'
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
            'height' => 'float',
            'weight' => 'float',
            'recommended_calories' => 'float',
            'goal_weight' => 'float',
            'admin' => 'boolean'
        ];
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all of the postReactions for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postReactions(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
