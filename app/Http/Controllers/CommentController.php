<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Guardar un nuevo comentario
     */
    public function store(Request $request, Tip $tip)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'tip_id' => $tip->id,
            'content' => $validated['content'],
        ]);

        return redirect()->route('tips.show', $tip)->with('success', 'Comment posted successfully!');
    }

    /**
     * Responder a un comentario
     */
    public function reply(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $reply = Comment::create([
            'user_id' => Auth::id(),
            'tip_id' => $comment->tip_id,
            'parent_id' => $comment->id,
            'content' => $validated['content'],
        ]);

        return redirect()->route('tips.show', $comment->tip)->with('success', 'Reply posted successfully!');
    }
}
