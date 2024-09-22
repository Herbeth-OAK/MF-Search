<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        $mediafireLinks = $user->mediafireLinks;
        $rank = $user->rank();
        return response()->json([
            'user' => $user,
            'mediafire_links' => $mediafireLinks,
            'rank' => $rank ? $rank->name : 'Sem patente',
        ]);
    }

    public function show($id)
    {
        $user = User::with(['mediafireLinks' => function($query) {
                $query->withCount('likes'); // Adiciona likes_count
            }])->find($id);
    
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
    
        // Adiciona o atributo is_liked_by_user a cada MediafireLink
        $user->mediafireLinks->transform(function($link) {
            $link->is_liked_by_user = $link->likes()->where('user_id', Auth::id())->exists();
            return $link;
        });
    
        $stats = $user->stats();
        $rank = $user->rank();
    
        return response()->json([
            'user' => $user,
            'rank' => $rank ? $rank->name : 'Sem patente',
            'stats' => $stats ? $stats : 'sem stats'
        ]);
    }
    
}
