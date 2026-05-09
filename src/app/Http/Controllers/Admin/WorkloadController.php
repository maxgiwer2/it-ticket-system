<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkloadController extends Controller
{
    public function index()
    {
        $isSuperAdmin = auth()->user()->role === 'superadmin';
        
        $workloads = Workload::with('user')
            ->when(!$isSuperAdmin, function($q) {
                return $q->where('user_id', auth()->id());
            })
            ->orderBy('work_date', 'desc')
            ->paginate(10);

        return view('admin.workloads.index', compact('workloads'));
    }

    public function create()
    {
        return view('admin.workloads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:อบรม,ช่วยงาน/ event,ประชุม,พัฒนาระบบ/ Server',
            'details' => 'required|string',
            'work_date' => 'required|date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $data = $request->only(['type', 'details', 'work_date']);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('workloads', 'public');
            $data['attachment_path'] = $path;
        }

        Workload::create($data);

        return redirect()->route('admin.workloads.index')
            ->with('success', 'บันทึกภาระงานเรียบร้อยแล้ว');
    }

    public function destroy(Workload $workload)
    {
        if ($workload->user_id !== auth()->id()) {
            abort(403);
        }

        if ($workload->attachment_path) {
            Storage::disk('public')->delete($workload->attachment_path);
        }

        $workload->delete();

        return redirect()->route('admin.workloads.index')
            ->with('success', 'ลบรายการภาระงานเรียบร้อยแล้ว');
    }
}
