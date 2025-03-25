<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Post;

class OwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $post = Post::find($request->route('id'));

        if (!$post || $post->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siz faqat oz postingizni tahrirlashingiz yoki ochirishingiz mumkin.'
            ], 403);
        }
        return $next($request);
    }
}
