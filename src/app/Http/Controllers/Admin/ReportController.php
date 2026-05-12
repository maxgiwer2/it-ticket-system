<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
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
