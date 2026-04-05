<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Mostrar todos los tips y comentarios con reportes
     */
    public function reportedTips()
    {
        // Obtener todos los tips que tienen al menos un reporte
        $reportedTips = Tip::whereHas('reports', function($q) {
            $q->whereNull('comment_id');
        })
            ->with(['user', 'reports' => function($q) {
                $q->whereNull('comment_id')->with('user');
            }])
            ->withCount(['reports' => function($q) {
                $q->whereNull('comment_id');
            }])
            ->orderBy('reports_count', 'desc')
            ->get()
            ->map(function ($tip) {
                return [
                    'id' => $tip->id,
                    'title' => $tip->title,
                    'description' => $tip->description,
                    'category' => $tip->category,
                    'image' => $tip->image,
                    'author' => $tip->user->name,
                    'author_email' => $tip->user->email,
                    'created_at' => $tip->created_at->format('Y-m-d H:i:s'),
                    'reports_count' => $tip->reports_count,
                    'reports' => $tip->reports->map(function ($report) {
                        return [
                            'id' => $report->id,
                            'reason' => $report->reason,
                            'description' => $report->description,
                            'status' => $report->status,
                            'reporter' => $report->user->name,
                            'reporter_email' => $report->user->email,
                            'created_at' => $report->created_at->format('Y-m-d H:i:s'),
                        ];
                    })
                ];
            });

        // Obtener todos los comentarios que tienen reportes
        $reportedComments = Comment::whereHas('reports')
            ->with(['user', 'tip', 'reports.user'])
            ->withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'author' => $comment->user->name,
                    'author_email' => $comment->user->email,
                    'tip_id' => $comment->tip->id,
                    'tip_title' => $comment->tip->title,
                    'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                    'reports_count' => $comment->reports_count,
                    'reports' => $comment->reports->map(function ($report) {
                        return [
                            'id' => $report->id,
                            'reason' => $report->reason,
                            'description' => $report->description,
                            'status' => $report->status,
                            'reporter' => $report->user->name,
                            'reporter_email' => $report->user->email,
                            'created_at' => $report->created_at->format('Y-m-d H:i:s'),
                        ];
                    })
                ];
            });

        return view('admin.reported-tips', compact('reportedTips', 'reportedComments'));
    }

    /**
     * Eliminar un tip reportado
     */
    public function deleteTip(Tip $tip)
    {
        // Verificar que el usuario sea administrador
        if (!auth()->user()->is_admin) {
            return redirect()->back()->with('error', __('messages.error.no_permission'));
        }

        // Eliminar la imagen si existe
        if ($tip->image) {
            Storage::disk('public')->delete($tip->image);
        }

        // Eliminar el tip (las relaciones se eliminan en cascada)
        $tip->delete();

        return redirect()->route('admin.reported-tips')
            ->with('success', __('admin.tip_deleted_success'));
    }

    /**
     * Eliminar un comentario reportado
     */
    public function deleteComment(Comment $comment)
    {
        // Verificar que el usuario sea administrador
        if (!auth()->user()->is_admin) {
            return redirect()->back()->with('error', __('messages.error.no_permission'));
        }

        // Eliminar el comentario (las respuestas e relaciones se eliminan en cascada)
        $comment->delete();

        return redirect()->route('admin.reported-tips')
            ->with('success', __('admin.comment_deleted_success'));
    }

    /**
     * Marcar un reporte como revisado/resuelto
     */
    public function updateReportStatus(Report $report, Request $request)
    {
        // Verificar que el usuario sea administrador
        if (!auth()->user()->is_admin) {
            return redirect()->back()->with('error', __('messages.error.no_permission'));
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,dismissed'
        ]);

        $report->status = $request->status;
        $report->save();

        return redirect()->back()->with('success', __('admin.report_status_updated'));
    }
}

