@extends('layouts.app')

@section('title', 'จัดการรายการแจ้งซ่อม')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">รายการแจ้งซ่อม</h1>
            <p class="text-slate-500">จัดการและอัปเดตสถานะการแจ้งซ่อมทั้งหมด</p>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tickets.create') }}" class="primary-gradient text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition-all flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                สร้างใบงานเอง
            </a>
            
            <!-- Filters -->
            <form action="{{ route('admin.tickets.index') }}" method="GET" class="flex flex-wrap gap-3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all w-64"
                    placeholder="ค้นหาเลข Ticket หรือชื่อผู้แจ้ง...">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <select name="status" onchange="this.form.submit()"
                class="px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white font-medium text-slate-700">
                <option value="">ทุกสถานะ</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รอรับเรื่อง</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>กำลังทำ</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>เสร็จสิ้น</option>
                <option value="more_info" {{ request('status') == 'more_info' ? 'selected' : '' }}>ขอข้อมูลเพิ่ม</option>
            </select>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 text-slate-500 hover:text-slate-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    ล้างค่า
                </a>
            @endif
        </form>
    </div>

    <div class="glass-card rounded-2xl overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">วันที่</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">หมายเลข</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">ผู้แจ้ง</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">หน่วยงาน</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">ประเภทงาน</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">ผู้รับผิดชอบ</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">สถานะ</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ $ticket->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-900">{{ $ticket->ticket_number }}</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $ticket->requester_name }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-900">{{ $ticket->department->name }}</div>
                            <div class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter">
                                {{ $ticket->department->type === 'faculty' ? 'คณะแพทยศาสตร์' : 'ศูนย์การแพทย์ฯ' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $ticket->jobType->name }}</td>
                        <td class="px-6 py-4">
                            @if($ticket->technician)
                                <span class="text-sm font-medium text-indigo-600">{{ $ticket->technician->name }}</span>
                            @else
                                <span class="text-xs text-slate-400 italic">ยังไม่มีผู้รับ</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'more_info' => 'bg-rose-100 text-rose-700',
                                ];
                                $labels = [
                                    'pending' => 'รอรับเรื่อง',
                                    'processing' => 'กำลังทำ',
                                    'completed' => 'เสร็จสิ้น',
                                    'more_info' => 'ขอข้อมูลเพิ่ม',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $colors[$ticket->status] }}">
                                {{ $labels[$ticket->status] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
                               class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                            ไม่พบรายการแจ้งซ่อม
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
