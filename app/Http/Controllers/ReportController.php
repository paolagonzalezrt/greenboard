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
                'message' => __('tips.report_login_required')
            ], 401);
        }

        // Validar que el usuario no esté reportando su propio tip
        if ($tip->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('tips.report_own_tip')
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
                'message' => __('tips.report_already_reported')
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
            'message' => __('tips.report_sent_success'),
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
                'message' => __('comments.report_login_required')
            ], 401);
        }

        // Validar que el usuario no esté reportando su propio comentario
        if ($comment->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('comments.report_own_comment')
            ], 403);
        }

        // Validar que el usuario no haya reportado este comentario anteriormente
        $existingReport = Report::where('user_id', Auth::id())
            ->where('comment_id', $comment->id)
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => __('comments.report_already_reported')
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
            'message' => __('tips.report_sent_success'),
            'report' => $report
        ], 201);
    }
}

