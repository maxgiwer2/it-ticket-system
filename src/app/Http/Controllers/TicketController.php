<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Department;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function create()
    {
        $departments = Department::where('status', 'active')
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');
        $jobTypes = JobType::all()->groupBy('category');
        return view('tickets.create', compact('departments', 'jobTypes'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'requester_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'phone' => 'required|string|max:20',
            'job_type_id' => 'required|exists:job_types,id',
            'details' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // Generate Ticket Number: TK-YYYYMMDD-XXXX
        $date = date('Ymd');
        $count = Ticket::whereDate('created_at', today())->count() + 1;
        $ticketNumber = 'TK-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $data = $request->all();
        $data['ticket_number'] = $ticketNumber;
        $data['status'] = 'pending';

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $data['attachment_path'] = $path;
        }

        Ticket::create($data);

        return redirect()->route('tickets.show', $ticketNumber)
            ->with('success', 'แจ้งซ่อมสำเร็จ! หมายเลข Ticket ของคุณคือ ' . $ticketNumber);
    }

    public function show($ticket_number)
    {
        $ticket = Ticket::where('ticket_number', $ticket_number)->with(['department', 'jobType'])->firstOrFail();
        return view('tickets.show', compact('ticket'));
    }

    public function statusSearch(Request $request)
    {
        $ticketNumber = $request->query('ticket_number');
        if ($ticketNumber) {
            $ticket = Ticket::where('ticket_number', $ticketNumber)->first();
            if ($ticket) {
                return redirect()->route('tickets.show', $ticket->ticket_number);
            }
            return back()->with('error', 'ไม่พบหมายเลข Ticket นี้ในระบบ');
        }
        return view('tickets.status_search');
    }
}
