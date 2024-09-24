<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediafireLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MediafireLinkController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'link' => 'required|url|max:1000',
    ]);

    $mediafireLink = MediafireLink::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'description' => $request->description,
        'link' => $request->link,
    ]);

    return response()->json(['message' => 'Link cadastrado com sucesso!', 'link' => $mediafireLink], 201);
}

public function index()
{
    $mediafireLinks = MediafireLink::with(['user' => function($query) {
            $query->select('id', 'name', 'nickname', 'image');
        }])
        ->withCount('likes') 
        ->orderBy('likes_count', 'desc')
        ->take(50)
        ->get()
        ->transform(function($link) {
            // Verifica se o campo `image` do usuário precisa ser convertido
            if ($link->user && is_resource($link->user->image)) {
                $link->user->image = (stream_get_contents($link->user->image));
            }

            // Checa se o usuário deu like no link
            $link->is_liked_by_user = $link->likes()->where('user_id', Auth::id())->exists();
            return $link;
        });

    return response()->json(['links' => $mediafireLinks], 200);
}


public function show($id)
{
    $mediafireLink = MediafireLink::findOrFail($id);

    return response()->json(['link' => $mediafireLink], 200);
}

public function like(Request $request, $id)
{
    $link = MediafireLink::find($id);
    if (!$link) {
        return response()->json(['error' => 'Link not found'], 404);
    }

    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $like = $link->likes()->where('user_id', $user->id)->first();
    if ($like) {
        $like->delete();
        return response()->json(['message' => 'Link unliked successfully'], 200);
    } else {
        $link->likes()->create(['user_id' => $user->id]);
        return response()->json(['message' => 'Link liked successfully'], 201);
    }
}
}
