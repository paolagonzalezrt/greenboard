<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Mostrar todos los tips con reportes
     */
    public function reportedTips()
    {
        // Obtener todos los tips que tienen al menos un reporte
        $reportedTips = Tip::has('reports')
            ->with(['user', 'reports.user'])
            ->withCount('reports')
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

        return view('admin.reported-tips', compact('reportedTips'));
    }

    /**
     * Eliminar un tip reportado
     */
    public function deleteTip(Tip $tip)
    {
        // Verificar que el usuario sea administrador
        if (!auth()->user()->is_admin) {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Eliminar la imagen si existe
        if ($tip->image) {
            Storage::disk('public')->delete($tip->image);
        }

        // Eliminar el tip (las relaciones se eliminan en cascada)
        $tip->delete();

        return redirect()->route('admin.reported-tips')
            ->with('success', 'Tip eliminado exitosamente.');
    }

    /**
     * Marcar un reporte como revisado/resuelto
     */
    public function updateReportStatus(Report $report, Request $request)
    {
        // Verificar que el usuario sea administrador
        if (!auth()->user()->is_admin) {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,dismissed'
        ]);

        $report->status = $request->status;
        $report->save();

        return redirect()->back()->with('success', 'Estado del reporte actualizado.');
    }
}

