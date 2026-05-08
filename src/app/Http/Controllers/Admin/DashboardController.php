<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Department;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'processing' => Ticket::where('status', 'processing')->count(),
            'completed' => Ticket::where('status', 'completed')->count(),
            'more_info' => Ticket::where('status', 'more_info')->count(),
        ];

        $latestTickets = Ticket::with(['department', 'jobType'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $deptStats = Ticket::selectRaw('department_id, count(*) as count')
            ->groupBy('department_id')
            ->with('department')
            ->get();

        return view('admin.dashboard', compact('stats', 'latestTickets', 'deptStats'));
    }
}
