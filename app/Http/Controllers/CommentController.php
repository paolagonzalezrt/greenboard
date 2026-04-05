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

        return redirect()->route('tips.show', $tip)->with('success', __('comments.comment_posted_success'));
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

        return redirect()->route('tips.show', $comment->tip)->with('success', __('comments.reply_posted_success'));
    }

    /**
     * Toggle like en un comentario
     */
    public function like(Comment $comment)
    {
        $user = Auth::user();

        $existingLike = $user->commentLikes()->where('comment_id', $comment->id)->first();

        if ($existingLike) {
            // Si ya existe el like, lo eliminamos
            $existingLike->delete();
            $liked = false;
        } else {
            // Si no existe, lo creamos
            $user->commentLikes()->create([
                'comment_id' => $comment->id,
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $comment->likes()->count(),
        ]);
    }

    /**
     * Eliminar un comentario o respuesta
     */
    public function destroy(Comment $comment)
    {
        // Verificar que el usuario es el propietario del comentario o es admin
        if (Auth::id() !== $comment->user_id && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Obtener el tip para poder redirigir después
        $tip = $comment->tip;

        // Si es un comentario padre con respuestas, eliminar también las respuestas
        if ($comment->replies()->count() > 0) {
            $comment->replies()->delete();
        }

        // Eliminar el comentario
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
