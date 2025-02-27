<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post)
    {

         if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $comment = new Comment([
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
        ]);

        $post->comments()->save($comment);

        return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
    }

    public function index(Post $post)
    {
        $comments = $post->comments()->with('user')->get();
        return response()->json(['comments' => $post->comments], 200);
    }
}
