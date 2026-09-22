<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('module', 'like', $term)
                  ->orWhere('action', 'like', $term)
                  ->orWhere('description', 'like', $term)
                  ->orWhereHas('user', function ($userQuery) use ($term) {
                      $userQuery->where('name', 'like', $term)
                          ->orWhere('email', 'like', $term);
                  });
            });
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(20)->withQueryString();
        $modules = ActivityLog::select('module')->distinct()->orderBy('module')->pluck('module');

        return view('admin.activity-logs.index', compact('logs', 'modules'));
    }
}
