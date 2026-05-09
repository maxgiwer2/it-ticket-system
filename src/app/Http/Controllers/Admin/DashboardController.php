<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Department;
use App\Models\Workload;
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

        // Personal Stats for current month
        $personalStats = [
            'total' => Ticket::where('assigned_to', auth()->id())
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'completed' => Ticket::where('assigned_to', auth()->id())
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'processing' => Ticket::where('assigned_to', auth()->id())
                ->whereIn('status', ['pending', 'processing', 'more_info'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Calendar Data: Tickets
        $ticketCalendar = Ticket::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('assigned_to', auth()->id())
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date');

        // Calendar Data: Workloads
        $workloadCalendar = Workload::whereMonth('work_date', now()->month)
            ->whereYear('work_date', now()->year)
            ->where('user_id', auth()->id())
            ->selectRaw('work_date as date, count(*) as count')
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date');

        // Personal Workload Count for current month
        $personalStats['workloads'] = Workload::where('user_id', auth()->id())
            ->whereMonth('work_date', now()->month)
            ->whereYear('work_date', now()->year)
            ->count();

        // Merge into Unified Calendar Data
        $calendarData = [];
        $allDates = $ticketCalendar->keys()->concat($workloadCalendar->keys())->unique();
        
        foreach ($allDates as $date) {
            $calendarData[$date] = [
                'tickets' => $ticketCalendar->get($date, 0),
                'workloads' => $workloadCalendar->get($date, 0)
            ];
        }

        $latestTickets = Ticket::with(['department', 'jobType'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $deptStats = Ticket::selectRaw('department_id, count(*) as count')
            ->groupBy('department_id')
            ->with('department')
            ->get();

        // Data for Charts
        $dailyTrend = Ticket::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $chartData = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData['labels'][] = now()->subDays($i)->format('d/m');
            $chartData['data'][] = $dailyTrend->get($date, 0);
        }

        return view('admin.dashboard', compact('stats', 'personalStats', 'calendarData', 'latestTickets', 'deptStats', 'chartData'));
    }
}
