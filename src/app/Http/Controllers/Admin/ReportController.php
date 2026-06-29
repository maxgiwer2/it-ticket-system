<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketSurvey;
use App\Models\User;
use App\Models\JobType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $users = User::whereIn('role', ['admin', 'superadmin'])->orderBy('name')->get();
        $jobTypes = JobType::orderBy('category')->orderBy('name')->get();

        $query = Ticket::with(['jobType', 'technician', 'department']);

        // Handle Date Filtering
        $reportType = $request->get('report_type', 'range'); // 'range' or 'yearly'
        
        if ($reportType === 'yearly' && $request->year) {
            // Convert Thai year to Gregorian year for querying
            $gregorianYear = (int)$request->year - 543;
            $dateFrom = Carbon::createFromDate($gregorianYear, 1, 1)->startOfDay();
            $dateTo = Carbon::createFromDate($gregorianYear, 12, 31)->endOfDay();
        } else {
            // Default to current month if no dates provided
            $dateFrom = $request->date_from ? Carbon::createFromFormat('Y-m-d', $request->date_from)->startOfDay() : Carbon::now()->startOfMonth();
            $dateTo = $request->date_to ? Carbon::createFromFormat('Y-m-d', $request->date_to)->endOfDay() : Carbon::now()->endOfMonth();
        }

        $query->whereBetween('created_at', [$dateFrom, $dateTo]);

        // Handle User Filtering
        $userId = $request->get('user_id', 'all');
        if ($userId !== 'all') {
            $query->where('assigned_to', $userId);
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        // Prepare Chart Data (Group by Job Type Category)
        $chartData = [];
        $categoryColors = [
            'Helpdesk' => '#10b981', // Emerald
            'HIS' => '#8b5cf6',      // Violet
            'Network' => '#f59e0b',  // Amber
            'Web/Info' => '#3b82f6', // Blue
            'Administrator' => '#ef4444', // Red
            'อื่นๆ' => '#64748b'       // Slate
        ];

        foreach ($tickets as $ticket) {
            $category = $ticket->jobType->category ?? 'อื่นๆ';
            if (!isset($chartData[$category])) {
                $chartData[$category] = [
                    'count' => 0,
                    'color' => $categoryColors[$category] ?? '#64748b'
                ];
            }
            $chartData[$category]['count']++;
        }

        // Prepare Matrix Data (User vs Job Types)
        $matrixData = [];
        $targetUsers = $userId !== 'all' ? $users->where('id', $userId) : $users;

        foreach ($targetUsers as $user) {
            $matrixData[$user->id] = [
                'user' => $user,
                'total' => 0,
                'job_types' => []
            ];
            foreach ($jobTypes as $jt) {
                $matrixData[$user->id]['job_types'][$jt->id] = 0;
            }
        }

        foreach ($tickets as $ticket) {
            $tUserId = $ticket->assigned_to;
            if ($tUserId && isset($matrixData[$tUserId])) {
                $jtId = $ticket->job_type_id;
                if (isset($matrixData[$tUserId]['job_types'][$jtId])) {
                    $matrixData[$tUserId]['job_types'][$jtId]++;
                    $matrixData[$tUserId]['total']++;
                }
            }
        }

        // Generate years for dropdown (Current year - 5 to Current year + 1) in Thai Buddhist Era
        $currentYearTH = Carbon::now()->year + 543;
        $years = range($currentYearTH, $currentYearTH - 5);

        return view('admin.reports.index', compact('users', 'jobTypes', 'tickets', 'chartData', 'matrixData', 'dateFrom', 'dateTo', 'years', 'reportType', 'userId'));
    }

    public function departments(Request $request)
    {
        // Default to current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year; // Gregorian

        $selectedMonth = $request->get('month', $currentMonth);
        $selectedYear = $request->get('year', $currentYear + 543) - 543; // Convert TH to Gregorian

        $dateFrom = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $dateTo = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Query tickets for the selected month
        $tickets = Ticket::with(['department', 'jobType', 'technician'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'asc')
            ->get();

        // Aggregate by Department
        $departmentStats = $tickets->groupBy(function($ticket) {
            return $ticket->department->name ?? 'ไม่ระบุหน่วยงาน';
        })->map(function($group) {
            return $group->count();
        })->sortByDesc(function($count) {
            return $count;
        });

        // Aggregate by Job Type
        $jobTypeStats = $tickets->groupBy(function($ticket) {
            return $ticket->jobType->name ?? 'ไม่ระบุประเภท';
        })->map(function($group) {
            return $group->count();
        })->sortByDesc(function($count) {
            return $count;
        });

        // Dropdown data
        $months = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        
        $currentYearTH = Carbon::now()->year + 543;
        $years = range($currentYearTH, $currentYearTH - 5);

        return view('admin.reports.departments', compact(
            'tickets', 
            'departmentStats', 
            'jobTypeStats', 
            'months', 
            'years', 
            'selectedMonth', 
            'selectedYear'
        ));
    }

    public function satisfaction(Request $request)
    {
        $surveys = TicketSurvey::with(['ticket.technician', 'ticket.department'])->get();

        $responseCount = $surveys->count();
        $completedCount = Ticket::where('status', 'completed')->count();
        $responseRate = $completedCount > 0 ? round($responseCount / $completedCount * 100, 1) : 0;

        // คะแนนเฉลี่ยรายด้าน + รวม
        $avgSpeed = round($surveys->avg('rating_speed') ?? 0, 2);
        $avgManner = round($surveys->avg('rating_manner') ?? 0, 2);
        $avgQuality = round($surveys->avg('rating_quality') ?? 0, 2);
        $avgOverall = $responseCount > 0 ? round(($avgSpeed + $avgManner + $avgQuality) / 3, 2) : 0;

        // การกระจายคะแนน (ตามคะแนนเฉลี่ยปัดของแต่ละใบ)
        $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($surveys as $s) {
            $score = (int) round(($s->rating_speed + $s->rating_manner + $s->rating_quality) / 3);
            $score = max(1, min(5, $score));
            $distribution[$score]++;
        }

        // คะแนนเฉลี่ยแยกตามเจ้าหน้าที่ผู้รับผิดชอบ
        $byTechnician = $surveys
            ->groupBy(fn($s) => $s->ticket->technician->name ?? 'ไม่ระบุผู้รับผิดชอบ')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'average' => round($group->avg(fn($s) => ($s->rating_speed + $s->rating_manner + $s->rating_quality) / 3), 2),
                ];
            })
            ->sortByDesc('average');

        // ความคิดเห็นล่าสุด
        $recentComments = $surveys
            ->filter(fn($s) => filled($s->comment))
            ->sortByDesc('created_at')
            ->take(20);

        return view('admin.reports.satisfaction', compact(
            'responseCount', 'completedCount', 'responseRate',
            'avgSpeed', 'avgManner', 'avgQuality', 'avgOverall',
            'distribution', 'byTechnician', 'recentComments'
        ));
    }

    public function exportCsv(Request $request)
    {
        $query = Ticket::with(['department', 'jobType', 'technician']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('ticket_number', 'like', "%{$request->search}%")
                  ->orWhere('requester_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        $response = new StreamedResponse(function() use ($tickets) {
            $handle = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel Thai support
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'หมายเลข Ticket',
                'ผู้แจ้ง',
                'เบอร์ติดต่อ',
                'หน่วยงาน',
                'ประเภทงาน',
                'รายละเอียด',
                'สถานะ',
                'ผู้รับผิดชอบ',
                'วันที่แจ้ง'
            ]);

            $statusLabels = [
                'pending' => 'รอดำเนินการ',
                'processing' => 'กำลังดำเนินการ',
                'completed' => 'เสร็จสิ้น',
                'more_info' => 'ขอข้อมูลเพิ่ม',
            ];

            foreach ($tickets as $ticket) {
                fputcsv($handle, [
                    $ticket->ticket_number,
                    $ticket->requester_name,
                    $ticket->phone,
                    $ticket->department->name,
                    $ticket->jobType->name,
                    $ticket->details,
                    $statusLabels[$ticket->status] ?? $ticket->status,
                    $ticket->technician ? $ticket->technician->name : '-',
                    $ticket->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="it_tickets_report_'.date('Ymd_His').'.csv"',
        ]);

        return $response;
    }
}
