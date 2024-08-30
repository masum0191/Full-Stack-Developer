<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()->paginate(10);
        return response()->json($comments);
    }

    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required',
        ]);
        $comment = $post->comments()->create([
            'body' => $validated['body'],
            'user_id' => auth()->id(),
        ]);
        return response()->json($comment, 201);
    }

    public function show(Comment $comment)
    {
        return response()->json($comment);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        $validated = $request->validate([
            'body' => 'string',
        ]);
        $comment->update($validated);
        return response()->json($comment);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return response()->json(null, 204);
    }
}
