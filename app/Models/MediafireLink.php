<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MediafireLink extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'link'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(LinkLike::class);
    }

    // Acessor para verificar se o usuário atual curtiu o link
    public function getIsLikedByUserAttribute()
    {
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    // Acessor para obter a contagem de likes
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
}
