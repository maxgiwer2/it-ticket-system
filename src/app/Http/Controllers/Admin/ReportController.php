<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
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
