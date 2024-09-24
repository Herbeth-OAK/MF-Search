<?php

namespace App\Models;

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
        'nickname',
        'email',
        'password',
        'image_url',
        'image',
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

    public function mediafireLinks()
    {
        return $this->hasMany(MediafireLink::class);
    }

    public function likes()
    {
        return $this->hasMany(LinkLike::class);
    }

    public function rank()
    {
        $totalLinks = $this->mediafireLinks()->count();
        $totalLikes = $this->mediafireLinks()->withCount('likes')->get()->sum('likes_count');

        return Rank::where('required_links', '<=', $totalLinks)
                    ->where('required_likes', '<=', $totalLikes)
                    ->orderBy('required_links', 'desc')
                    ->first();
    }

    public function stats()
    {
        $totalLinks = $this->mediafireLinks()->count();
        $totalLikes = $this->mediafireLinks()->withCount('likes')->get()->sum('likes_count');
    
        return (object) [
            'totalLinks' => $totalLinks,
            'totalLikes' => $totalLikes,
        ];
    }
}
