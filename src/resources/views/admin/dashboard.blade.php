@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-900 italic tracking-tighter">Control Center</h1>
            <p class="text-slate-500 font-medium">ยินดีต้อนรับคุณ {{ auth()->user()->name }} - จัดการระบบและติดตามงาน IT ทั้งหมด</p>
        </div>
    </div>

    <!-- Section 1: Global System-wide Stats (Now at the top) -->
    <section>
        <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center">
            <span class="w-8 h-[2px] bg-cyan-500 mr-3"></span>
            สรุปภาพรวมงานแจ้งซ่อมทั้งหมดในระบบ
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending -->
            <a href="{{ route('admin.tickets.index', ['status' => 'pending']) }}" class="glass-card p-8 rounded-[2rem] border-l-8 border-l-rose-500 shadow-xl shadow-slate-200/30 hover:-translate-y-2 transition-all group relative overflow-hidden block">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <p class="text-rose-600 font-black text-sm uppercase tracking-wider mb-2">งานรอรับเรื่อง</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-5xl font-black text-slate-900 tracking-tighter">{{ $stats['pending'] }}</h3>
                        <span class="text-rose-500 font-bold text-xs mb-2">ใบงาน</span>
                    </div>
                </div>
            </a>

            <!-- Processing -->
            <a href="{{ route('admin.tickets.index', ['status' => 'processing']) }}" class="glass-card p-8 rounded-[2rem] border-l-8 border-l-amber-500 shadow-xl shadow-slate-200/30 hover:-translate-y-2 transition-all group relative overflow-hidden block">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <p class="text-amber-600 font-black text-sm uppercase tracking-wider mb-2">กำลังดำเนินการ</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-5xl font-black text-slate-900 tracking-tighter">{{ $stats['processing'] }}</h3>
                        <span class="text-amber-600 font-bold text-xs mb-2">ใบงาน</span>
                    </div>
                </div>
            </a>

            <!-- More Info -->
            <a href="{{ route('admin.tickets.index', ['status' => 'more_info']) }}" class="glass-card p-8 rounded-[2rem] border-l-8 border-l-sky-500 shadow-xl shadow-slate-200/30 hover:-translate-y-2 transition-all group relative overflow-hidden block">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-sky-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <p class="text-sky-600 font-black text-sm uppercase tracking-wider mb-2">รอข้อมูลเพิ่ม</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-5xl font-black text-slate-900 tracking-tighter">{{ $stats['more_info'] }}</h3>
                        <span class="text-sky-600 font-bold text-xs mb-2">ใบงาน</span>
                    </div>
                </div>
            </a>

            <!-- Completed -->
            <a href="{{ route('admin.tickets.index', ['status' => 'completed']) }}" class="glass-card p-8 rounded-[2rem] border-l-8 border-l-emerald-500 shadow-xl shadow-slate-200/30 hover:-translate-y-2 transition-all group relative overflow-hidden block">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <p class="text-emerald-600 font-black text-sm uppercase tracking-wider mb-2">เสร็จสิ้นแล้ว</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-5xl font-black text-slate-900 tracking-tighter">{{ $stats['completed'] }}</h3>
                        <span class="text-emerald-600 font-bold text-xs mb-2">ใบงาน</span>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <!-- Section 2: Personal Stats & Calendar -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Personal Summary (Col 6) -->
        <div class="lg:col-span-6 flex flex-col">
            <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center">
                <span class="w-8 h-[2px] bg-emerald-500 mr-3"></span>
                {{ auth()->user()->role === 'superadmin' ? 'สรุปภาพรวมระบบ' : 'สรุปผลงานของคุณ' }} ({{ $selectedDate->translatedFormat('F Y') }})
            </h2>
            <div class="glass-card rounded-[2.5rem] overflow-hidden flex-grow border border-white/60 shadow-2xl shadow-slate-200/50 relative p-6">
                <div class="grid grid-cols-1 gap-4">
                    <!-- Stat Row: Total -->
                    <div class="flex items-center justify-between p-4 rounded-3xl hover:bg-slate-50/50 transition-all group">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-blue-500 text-white rounded-2xl flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Assigned</p>
                                <h4 class="text-lg font-black text-slate-800 tracking-tighter">ใบงานได้รับมอบหมาย</h4>
                            </div>
                        </div>
                        <div class="text-4xl font-black text-slate-900 tabular-nums">{{ $personalStats['total'] }}</div>
                    </div>

                    <!-- Stat Row: Processing -->
                    <div class="flex items-center justify-between p-4 rounded-3xl hover:bg-slate-50/50 transition-all group">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-amber-500 text-white rounded-2xl flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Processing</p>
                                <h4 class="text-lg font-black text-slate-800 tracking-tighter">กำลังดำเนินการแก้ไข</h4>
                            </div>
                        </div>
                        <div class="text-4xl font-black text-slate-900 tabular-nums">{{ $personalStats['processing'] }}</div>
                    </div>

                    <!-- Stat Row: Completed -->
                    <div class="flex items-center justify-between p-4 rounded-3xl hover:bg-slate-50/50 transition-all group">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Success</p>
                                <h4 class="text-lg font-black text-slate-800 tracking-tighter">ทำสำเร็จแล้ว</h4>
                            </div>
                        </div>
                        <div class="text-4xl font-black text-emerald-600 tabular-nums">{{ $personalStats['completed'] }}</div>
                    </div>

                    <!-- Workloads Stat -->
                    <div class="flex items-center justify-between p-4 bg-indigo-50/30 rounded-3xl border border-indigo-100/50 group">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-indigo-600 font-black text-xs uppercase tracking-wider">Other Workloads</p>
                                <h4 class="text-xl font-black text-slate-900 tracking-tighter">ภาระงานอื่นๆ สะสม</h4>
                            </div>
                        </div>
                        <div class="text-4xl font-black text-indigo-600 tabular-nums">{{ $personalStats['workloads'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar (Col 6) -->
        <div class="lg:col-span-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] flex items-center">
                    <span class="w-8 h-[2px] bg-indigo-500 mr-3"></span>
                    Service Calendar
                </h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.dashboard', ['month' => $selectedDate->copy()->subMonth()->month, 'year' => $selectedDate->copy()->subMonth()->year]) }}" class="w-8 h-8 bg-white border border-slate-100 rounded-full flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <span class="text-[10px] font-black text-slate-600 uppercase">{{ $selectedDate->translatedFormat('M Y') }}</span>
                    <a href="{{ route('admin.dashboard', ['month' => $selectedDate->copy()->addMonth()->month, 'year' => $selectedDate->copy()->addMonth()->year]) }}" class="w-8 h-8 bg-white border border-slate-100 rounded-full flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
            <div class="glass-card rounded-[2.5rem] p-8 flex-grow border border-white/60 shadow-2xl shadow-slate-200/50">
                @php
                    $startOfMonth = $selectedDate->copy()->startOfMonth();
                    $daysInMonth = $startOfMonth->daysInMonth;
                    $firstDayOfWeek = $startOfMonth->dayOfWeek;
                    $dayLabels = ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'];
                @endphp

                <div class="grid grid-cols-7 gap-2">
                    @foreach($dayLabels as $label)
                        <div class="pb-4 text-center text-[10px] font-black text-slate-300 uppercase tracking-tighter">{{ $label }}</div>
                    @endforeach

                    @for($i = 0; $i < $firstDayOfWeek; $i++)
                        <div class="aspect-square"></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $dateString = $selectedDate->copy()->startOfMonth()->addDays($day - 1)->format('Y-m-d');
                            $dayData = $calendarData[$dateString] ?? ['tickets' => 0, 'workloads' => 0];
                            $ticketCount = $dayData['tickets'];
                            $workloadCount = $dayData['workloads'];
                            $isToday = now()->isSameDay($selectedDate->copy()->startOfMonth()->addDays($day - 1));
                        @endphp
                        <div class="aspect-square rounded-2xl border flex flex-col items-center justify-center transition-all relative group {{ $isToday ? 'bg-indigo-600 border-indigo-400 text-white shadow-xl -translate-y-1 scale-105 z-10' : 'bg-white/50 border-slate-100 text-slate-600' }}">
                            <span class="text-xs font-black">{{ $day }}</span>
                            
                            <div class="absolute -top-1 -right-1 flex gap-0.5">
                                @if($ticketCount > 0)
                                    <div class="w-4 h-4 rounded-full bg-rose-500 text-white text-[8px] font-black flex items-center justify-center border border-white">
                                        {{ $ticketCount }}
                                    </div>
                                @endif
                                @if($workloadCount > 0)
                                    <div class="w-4 h-4 rounded-full bg-indigo-400 text-white text-[8px] font-black flex items-center justify-center border border-white">
                                        {{ $workloadCount }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex items-center gap-4 overflow-x-auto pb-4 no-scrollbar">
        <a href="{{ route('admin.tickets.create') }}" class="cta-gradient text-white px-8 py-4 rounded-3xl font-black shadow-xl shadow-emerald-100 hover:shadow-emerald-300 hover:-translate-y-1 transition-all flex items-center whitespace-nowrap">
            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            สร้างใบงานด่วน
        </a>
        <a href="{{ route('admin.workloads.create') }}" class="bg-white text-slate-700 px-8 py-4 rounded-3xl font-black border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex items-center whitespace-nowrap">
            <div class="w-8 h-8 bg-indigo-50 rounded-xl flex items-center justify-center mr-3 text-indigo-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            บันทึกภาระงาน
        </a>
        <a href="{{ route('admin.tickets.export') }}" class="bg-white text-slate-700 px-8 py-4 rounded-3xl font-black border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex items-center whitespace-nowrap">
            <div class="w-8 h-8 bg-slate-50 rounded-xl flex items-center justify-center mr-3 text-slate-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            ส่งออกรายงาน
        </a>
    </div>

    <!-- Trends & Latest Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="glass-card rounded-[2.5rem] p-8 h-full">
                <h2 class="text-xl font-black text-slate-900 mb-6 italic tracking-tight">Workload Trends</h2>
                <div class="h-72">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="glass-card rounded-[2.5rem] overflow-hidden h-full flex flex-col">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-black text-slate-900 italic tracking-tight">Latest Tickets</h2>
                    <a href="{{ route('admin.tickets.index') }}" class="text-emerald-600 text-[10px] font-black uppercase tracking-widest hover:underline">View All</a>
                </div>
                <div class="flex-grow">
                    <table class="w-full">
                        <tbody class="divide-y divide-slate-50">
                            @forelse($latestTickets as $ticket)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-black text-slate-700 text-xs">{{ $ticket->requester_name }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $ticket->ticket_number }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-tighter {{ 
                                        $ticket->status === 'pending' ? 'bg-amber-100 text-amber-700' : 
                                        ($ticket->status === 'processing' ? 'bg-blue-100 text-blue-700' : 
                                        ($ticket->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'))
                                    }}">
                                        {{ 
                                            $ticket->status === 'pending' ? 'รอรับเรื่อง' : 
                                            ($ticket->status === 'processing' ? 'กำลังดำเนินการ' : 
                                            ($ticket->status === 'more_info' ? 'รอข้อมูลเพิ่ม' : 'เสร็จสิ้น'))
                                        }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td class="px-6 py-10 text-center text-slate-400 italic text-xs">No records found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Tickets',
                data: {!! json_encode($chartData['data']) !!},
                borderColor: '#0891b2',
                borderWidth: 5,
                tension: 0.4,
                pointRadius: 0,
                fill: true,
                backgroundColor: 'rgba(8, 145, 178, 0.05)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false, beginAtZero: true },
                x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' } }
            }
        }
    });
</script>
@endpush
@endsection
