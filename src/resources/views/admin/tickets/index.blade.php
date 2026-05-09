@extends('layouts.app')

@section('title', 'ใบงาน')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 italic tracking-tight">ใบงาน</h1>
                <p class="text-slate-500 text-sm mt-1">จัดการและติดตามสถานะใบงานแจ้งซ่อมทั้งหมด</p>
            </div>
            
            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <a href="{{ route('admin.tickets.export', request()->all()) }}" class="flex-grow sm:flex-initial bg-white text-slate-700 px-4 sm:px-6 py-3 rounded-2xl font-bold border border-slate-200 hover:bg-slate-50 shadow-sm transition-all flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export
                </a>
                <a href="{{ route('admin.tickets.create') }}" class="flex-grow sm:flex-initial cta-gradient text-white px-4 sm:px-8 py-3 rounded-2xl font-bold shadow-xl shadow-emerald-200 hover:shadow-emerald-400 interactive-scale transition-all flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Ticket
                </a>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="mt-8 glass-card p-6 rounded-[2rem] border border-white/60 shadow-xl">
            <form action="{{ route('admin.tickets.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full pl-10 pr-4 py-3 rounded-xl border-none bg-slate-100/50 focus-ring outline-none transition-all text-sm"
                        placeholder="ค้นหาชื่อ, หมายเลข...">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <select name="status" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-xl border-none bg-slate-100/50 focus:ring-2 focus:ring-emerald-500 outline-none text-sm appearance-none cursor-pointer font-medium text-slate-700">
                    <option value="">ทุกสถานะ</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>เสร็จสิ้น</option>
                    <option value="more_info" {{ request('status') == 'more_info' ? 'selected' : '' }}>ขอข้อมูลเพิ่ม</option>
                </select>

                <select name="department_id" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-xl border-none bg-slate-100/50 focus:ring-2 focus:ring-emerald-500 outline-none text-sm appearance-none cursor-pointer font-medium text-slate-700">
                    <option value="">ทุกหน่วยงาน</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>

                <select name="assigned_to" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-xl border-none bg-slate-100/50 focus:ring-2 focus:ring-emerald-500 outline-none text-sm appearance-none cursor-pointer font-medium text-slate-700">
                    <option value="">งานว่าง + งานของฉัน</option>
                    <option value="all" {{ request('assigned_to') == 'all' ? 'selected' : '' }}>งานทั้งหมดทุกคน</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="flex-grow bg-slate-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-slate-800 transition-all text-sm">กรองข้อมูล</button>
                    @if(request()->anyFilled(['search', 'status', 'department_id', 'assigned_to']))
                        <a href="{{ route('admin.tickets.index') }}" class="w-12 bg-rose-50 text-rose-500 flex items-center justify-center rounded-xl hover:bg-rose-100 transition-all" title="ล้างการค้นหา">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="glass-card rounded-[2.5rem] overflow-hidden border border-white/60 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-4 sm:px-8 py-5 text-xs sm:text-sm font-black text-slate-700 uppercase tracking-wide">หมายเลข / หัวข้อการแจ้ง</th>
                        <th class="px-4 sm:px-8 py-5 text-xs sm:text-sm font-black text-slate-700 uppercase tracking-wide">ผู้แจ้ง / หน่วยงาน / ติดต่อ</th>
                        <th class="px-4 sm:px-8 py-5 text-xs sm:text-sm font-black text-slate-700 uppercase tracking-wide">ผู้รับผิดชอบ</th>
                        <th class="px-4 sm:px-8 py-5 text-xs sm:text-sm font-black text-slate-700 uppercase tracking-wide">สถานะ</th>
                        <th class="px-4 sm:px-8 py-5 text-xs sm:text-sm font-black text-slate-700 uppercase tracking-wide">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-cyan-50/30 transition-all group">
                        <td class="px-4 sm:px-8 py-6">
                            <div class="flex items-center">
                                <div class="hidden sm:flex w-10 h-10 rounded-xl bg-slate-100 items-center justify-center mr-4 group-hover:bg-emerald-100 group-hover:text-emerald-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-emerald-600 mb-0.5">{{ $ticket->jobType->name }}</p>
                                    <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-slate-900 font-black hover:text-emerald-600 transition-colors block text-sm sm:text-base leading-tight">
                                        {{ $ticket->ticket_number }}
                                    </a>
                                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-1 max-w-[200px]">{{ $ticket->details }}</p>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-tighter mt-1 block">{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-8 py-6">
                            <p class="font-bold text-slate-700 text-sm sm:text-base">{{ $ticket->requester_name }}</p>
                            <div class="flex flex-col gap-1 mt-1">
                                <span class="text-[10px] sm:text-xs text-slate-500 flex items-center">
                                    <svg class="w-3 h-3 mr-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $ticket->department->name }}
                                </span>
                                <span class="text-[10px] sm:text-xs text-slate-400 flex items-center">
                                    <svg class="w-3 h-3 mr-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $ticket->phone ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-8 py-6">
                            @if($ticket->technician)
                                <div class="flex items-center whitespace-nowrap">
                                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[9px] sm:text-[10px] font-bold mr-2">
                                        {{ substr($ticket->technician->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs sm:text-sm font-bold text-slate-700">{{ $ticket->technician->name }}</span>
                                </div>
                            @else
                                <span class="text-[10px] sm:text-xs text-slate-300 italic whitespace-nowrap">ยังไม่มีผู้รับเรื่อง</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-8 py-6">
                            @php
                                $statusMap = [
                                    'pending' => ['label' => 'รอดำเนินการ', 'color' => 'bg-amber-100 text-amber-700 border-amber-200'],
                                    'processing' => ['label' => 'กำลังดำเนินการ', 'color' => 'bg-cyan-100 text-cyan-700 border-cyan-200'],
                                    'completed' => ['label' => 'เสร็จสิ้น', 'color' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
                                    'more_info' => ['label' => 'ขอข้อมูลเพิ่ม', 'color' => 'bg-rose-100 text-rose-700 border-rose-200'],
                                ];
                                $s = $statusMap[$ticket->status] ?? $statusMap['pending'];
                            @endphp
                            <span class="px-3 py-1.5 rounded-full text-[11px] font-black border {{ $s['color'] }} flex items-center w-fit shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full mr-2 bg-current animate-pulse"></span>
                                {{ $s['label'] }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-8 py-6 text-right whitespace-nowrap">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white text-cyan-600 hover:bg-cyan-600 hover:text-white transition-all shadow-sm border border-cyan-100 interactive-scale">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">ไม่พบข้อมูลใบงาน</h3>
                                <p class="text-slate-400 text-sm">ลองเปลี่ยนเงื่อนไขการค้นหาหรือเพิ่มใบงานใหม่</p>
                            </div>
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
