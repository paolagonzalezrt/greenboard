<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Tip;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Almacenar un nuevo reporte de tip
     */
    public function store(Request $request, Tip $tip)
    {
        // Validar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para reportar un tip.'
            ], 401);
        }

        // Validar que el usuario no esté reportando su propio tip
        if ($tip->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes reportar tu propio tip.'
            ], 403);
        }

        // Validar que el usuario no haya reportado este tip anteriormente
        $existingReport = Report::where('user_id', Auth::id())
            ->where('tip_id', $tip->id)
            ->whereNull('comment_id')
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has reportado este tip anteriormente.'
            ], 409);
        }

        // Validar los datos del reporte
        $validated = $request->validate([
            'reason' => 'required|string|in:spam,inappropriate,misleading,harassment,other',
            'description' => 'nullable|string|max:500'
        ]);

        // Crear el reporte
        $report = Report::create([
            'user_id' => Auth::id(),
            'tip_id' => $tip->id,
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reporte enviado exitosamente. Lo revisaremos pronto.',
            'report' => $report
        ], 201);
    }

    /**
     * Almacenar un nuevo reporte de comentario
     */
    public function storeCommentReport(Request $request, Comment $comment)
    {
        // Validar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para reportar un comentario.'
            ], 401);
        }

        // Validar que el usuario no esté reportando su propio comentario
        if ($comment->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes reportar tu propio comentario.'
            ], 403);
        }

        // Validar que el usuario no haya reportado este comentario anteriormente
        $existingReport = Report::where('user_id', Auth::id())
            ->where('comment_id', $comment->id)
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has reportado este comentario anteriormente.'
            ], 409);
        }

        // Validar los datos del reporte
        $validated = $request->validate([
            'reason' => 'required|string|in:spam,inappropriate,misleading,harassment,other',
            'description' => 'nullable|string|max:500'
        ]);

        // Crear el reporte
        $report = Report::create([
            'user_id' => Auth::id(),
            'comment_id' => $comment->id,
            'tip_id' => $comment->tip_id,
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reporte enviado exitosamente. Lo revisaremos pronto.',
            'report' => $report
        ], 201);
    }
}

