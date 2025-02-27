<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user', 'post')->get();
        return response()->json([
            'status' => 200,
            'data' => $comments,
            'message' => 'Barcha izohlar muvaffaqiyatli olindi'
        ]);
    }
    public function store(Request $request)
    {
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);
    
        return response()->json([
            'status' => 201,
            'data' => $comment,
            'message' => 'Izoh muvaffaqiyatli qoshildi!'
        ], 201);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'status' => 403,
                'message' => 'Siz faqat oz izohingizni ochira olasiz'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Izoh muvaffaqiyatli ochirildi'
        ]);
    }
}
