<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Department;
use App\Models\JobType;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['department', 'jobType']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('ticket_number', 'like', '%' . $request->search . '%')
                  ->orWhere('requester_name', 'like', '%' . $request->search . '%');
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $jobTypes = JobType::all();
        return view('admin.tickets.create', compact('departments', 'jobTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'requester_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'phone' => 'required|string|max:20',
            'job_type_id' => 'required|exists:job_types,id',
            'details' => 'required|string',
        ]);

        $date = date('Ymd');
        $count = Ticket::whereDate('created_at', today())->count() + 1;
        $ticketNumber = 'TK-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $data = $request->all();
        $data['ticket_number'] = $ticketNumber;
        $data['status'] = 'processing';
        $data['assigned_to'] = auth()->id();

        Ticket::create($data);

        return redirect()->route('admin.tickets.index')->with('success', 'สร้างใบงานใหม่เรียบร้อยแล้ว');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['department', 'jobType', 'technician']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function accept(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'processing',
            'assigned_to' => auth()->id()
        ]);

        return back()->with('success', 'คุณได้รับเรื่องใบงานนี้แล้ว');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,more_info',
            'admin_note' => 'nullable|string',
        ]);

        $ticket->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'อัปเดตสถานะ Ticket เรียบร้อยแล้ว');
    }
}
