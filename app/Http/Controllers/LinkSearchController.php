<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediafireLink;
use Illuminate\Support\Facades\Auth;

class LinkSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        if (!$query) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }
    
        $mediafireLinks = MediafireLink::with(['user' => function($query) {
                    $query->select('id', 'name', 'nickname', 'image_url');
                }])
                ->withCount('likes')
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', '%' . $query . '%')
                      ->orWhere('description', 'like', '%' . $query . '%');
                })
                ->orderBy('likes_count', 'desc')
                ->take(50)
                ->get()
                ->transform(function($link) {
                    $link->is_liked_by_user = $link->likes()->where('user_id', Auth::id())->exists();
                    return $link;
                });
    
        return response()->json(['links' => $mediafireLinks], 200);
    }
}    
