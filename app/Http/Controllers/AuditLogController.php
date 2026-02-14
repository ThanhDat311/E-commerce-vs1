<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest('created_at');

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by model ID
        if ($request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        $auditLogs = $query->paginate(25);
        $users = User::orderBy('name')->get();
        $modelTypes = [
            'App\Models\Product' => 'Product',
            'App\Models\Order' => 'Order',
            'App\Models\User' => 'User',
        ];

        return view('pages.admin.audit-logs.index', compact('auditLogs', 'users', 'modelTypes'));
    }

    /**
     * Display a specific audit log entry.
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');

        try {
            $model = $auditLog->getAuditableModel();
        } catch (\Exception $e) {
            $model = null;
        }

        return view('pages.admin.audit-logs.show', compact('auditLog', 'model'));
    }

    /**
     * Display audit logs for a specific model.
     */
    public function modelHistory(Request $request)
    {
        $modelType = $request->query('model_type');
        $modelId = $request->query('model_id');

        $query = AuditLog::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->with('user')
            ->latest('created_at');

        $auditLogs = $query->paginate(50);

        try {
            $model = $modelType::find($modelId);
        } catch (\Exception $e) {
            $model = null;
        }

        return view('pages.admin.audit-logs.model-history', compact('auditLogs', 'model', 'modelType', 'modelId'));
    }

    /**
     * Get audit log statistics for dashboard.
     */
    public function statistics()
    {
        $totalLogs = AuditLog::count();
        $logsByAction = AuditLog::selectRaw('action, count(*) as total')
            ->groupBy('action')
            ->get()
            ->pluck('total', 'action');

        $logsByModel = AuditLog::selectRaw('model_type, count(*) as total')
            ->groupBy('model_type')
            ->get()
            ->toArray();

        $recentLogs = AuditLog::with('user')->latest('created_at')->limit(10)->get();

        return response()->json([
            'totalLogs' => $totalLogs,
            'logsByAction' => $logsByAction,
            'logsByModel' => $logsByModel,
            'recentLogs' => $recentLogs,
        ]);
    }

    /**
     * Export audit logs to CSV.
     */
    public function export(Request $request)
    {
        $query = AuditLog::with('user')->latest('created_at');

        // Apply same filters as index
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $logs = $query->get();

        // Create CSV with proper escaping
        $filename = 'audit-logs-' . date('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID',
                'User',
                'Action',
                'Model Type',
                'Model ID',
                'IP Address',
                'User Agent',
                'Created At',
                'Old Values',
                'New Values'
            ]);

            // Data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'System',
                    $log->action,
                    class_basename($log->model_type),
                    $log->model_id ?? '',
                    $log->ip_address ?? '',
                    $log->user_agent ?? '',
                    $log->created_at->format('Y-m-d H:i:s'),
                    is_array($log->old_values) || is_object($log->old_values) ? json_encode($log->old_values) : ($log->old_values ?? ''),
                    is_array($log->new_values) || is_object($log->new_values) ? json_encode($log->new_values) : ($log->new_values ?? '')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
