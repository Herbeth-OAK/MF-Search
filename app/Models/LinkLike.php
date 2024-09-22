<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkLike extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'mediafire_link_id'];

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com o link
    public function mediafireLink()
    {
        return $this->belongsTo(MediafireLink::class);
    }
}
