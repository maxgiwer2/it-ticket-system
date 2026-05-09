@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Dashboard</h1>
            <p class="text-slate-500">ยินดีต้อนรับคุณ {{ auth()->user()->name }} - ภาพรวมระบบและการทำงานของคุณ</p>
        </div>
    </div>

    <!-- Personal Control Center (Stats + Calendar) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">
        <!-- Personal Summary Card (Col 6) -->
        <div class="lg:col-span-6 flex flex-col">
            <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center">
                <span class="w-8 h-[2px] bg-emerald-500 mr-3"></span>
                สรุปผลงานของคุณ ({{ now()->translatedFormat('F Y') }})
            </h2>
            <div class="glass-card rounded-[2.5rem] overflow-hidden flex-grow border border-white/60 shadow-2xl shadow-slate-200/50 relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none" style="will-change: filter;"></div>
                
                <div class="relative z-10 h-full flex flex-col justify-around p-4">
                    <!-- Stat Row: Total -->
                    <div class="flex items-center justify-between p-6 rounded-3xl hover:bg-slate-50/50 transition-all duration-300 group">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform duration-500" style="min-width: 64px; min-height: 64px;">
                                <svg class="w-8 h-8" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div class="ml-6">
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Assigned Tickets</p>
                                <h4 class="text-xl font-black text-slate-800">งานที่ได้รับมอบหมายทั้งหมด</h4>
                            </div>
                        </div>
                        <div class="text-5xl font-black text-slate-900 tabular-nums">{{ $personalStats['total'] }}</div>
                    </div>

                    <!-- Stat Row: Processing -->
                    <div class="flex items-center justify-between p-6 rounded-3xl hover:bg-slate-50/50 transition-all duration-300 group">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-amber-200 group-hover:scale-110 transition-transform duration-500" style="min-width: 64px; min-height: 64px;">
                                <svg class="w-8 h-8" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="ml-6">
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">In Progress</p>
                                <h4 class="text-xl font-black text-slate-800">กำลังดำเนินการแก้ไข</h4>
                            </div>
                        </div>
                        <div class="text-5xl font-black text-slate-900 tabular-nums">{{ $personalStats['processing'] }}</div>
                    </div>

                    <!-- Stat Row: Completed -->
                    <div class="flex items-center justify-between p-6 rounded-3xl hover:bg-slate-50/50 transition-all duration-300 group">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200 group-hover:scale-110 transition-transform duration-500" style="min-width: 64px; min-height: 64px;">
                                <svg class="w-8 h-8" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="ml-6">
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Success</p>
                                <h4 class="text-xl font-black text-slate-800">งานที่ทำสำเร็จแล้ว</h4>
                            </div>
                        </div>
                        <div class="text-5xl font-black text-emerald-600 tabular-nums">{{ $personalStats['completed'] }}</div>
                    </div>

                    <!-- Workloads Stat -->
                    <div class="flex items-center justify-between p-6 bg-slate-50/50 rounded-3xl border border-slate-100 hover:bg-white hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-300 group">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <svg style="width: 32px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div class="ml-6">
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Workload</p>
                                <h4 class="text-xl font-black text-slate-800">ภาระงานอื่นๆ สะสม</h4>
                            </div>
                        </div>
                        <div class="text-5xl font-black text-indigo-600 tabular-nums">{{ $personalStats['workloads'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mini Calendar (Col 6) -->
        <div class="lg:col-span-6 flex flex-col">
            <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center">
                <span class="w-8 h-[2px] bg-cyan-500 mr-3"></span>
                Your Service Calendar
            </h2>
            <div class="glass-card rounded-[2.5rem] p-8 flex-grow border border-white/60 shadow-2xl shadow-slate-200/50 relative overflow-hidden">
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-cyan-500/5 rounded-full blur-3xl -ml-24 -mb-24 pointer-events-none" style="will-change: filter;"></div>
                
                @php
                    $startOfMonth = now()->startOfMonth();
                    $daysInMonth = $startOfMonth->daysInMonth;
                    $firstDayOfWeek = $startOfMonth->dayOfWeek;
                    $dayLabels = ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'];
                @endphp

                <div class="grid grid-cols-7 gap-2 relative z-10">
                    @foreach($dayLabels as $label)
                        <div class="pb-4 text-center text-[10px] font-black text-slate-300 uppercase tracking-tighter">{{ $label }}</div>
                    @endforeach

                    @for($i = 0; $i < $firstDayOfWeek; $i++)
                        <div class="aspect-square"></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $dateString = now()->startOfMonth()->addDays($day - 1)->format('Y-m-d');
                            $dayData = $calendarData[$dateString] ?? ['tickets' => 0, 'workloads' => 0];
                            $ticketCount = $dayData['tickets'];
                            $workloadCount = $dayData['workloads'];
                            $isToday = now()->format('d') == $day;
                        @endphp
                        <div class="aspect-square rounded-2xl border flex flex-col items-center justify-center transition-all duration-300 relative group {{ $isToday ? 'bg-gradient-to-br from-emerald-500 to-teal-600 border-emerald-400 text-white shadow-xl shadow-emerald-200 -translate-y-1 scale-110 z-20' : 'bg-white/50 border-slate-100 hover:border-emerald-200 hover:bg-white text-slate-600' }}">
                            <span class="text-xs font-bold">{{ $day }}</span>
                            
                            <div class="absolute -top-1 -right-1 flex gap-0.5">
                                @if($ticketCount > 0)
                                    <div class="w-5 h-5 rounded-full {{ $isToday ? 'bg-white text-emerald-600' : 'bg-rose-500 text-white' }} text-[9px] font-black flex items-center justify-center shadow-lg border-2 border-white">
                                        {{ $ticketCount }}
                                    </div>
                                @endif
                                @if($workloadCount > 0)
                                    <div class="w-5 h-5 rounded-full {{ $isToday ? 'bg-white text-indigo-600' : 'bg-indigo-500 text-white' }} text-[9px] font-black flex items-center justify-center shadow-lg border-2 border-white">
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

    <!-- System-wide Stats Overview -->
    <div class="mb-10">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
            <span class="w-2 h-6 bg-slate-400 rounded-full mr-3"></span>
            สรุปภาพรวมงานแจ้งซ่อมทั้งหมดในระบบ
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Pending -->
        <div class="glass-card p-8 rounded-[2.5rem] border border-white/40 shadow-2xl shadow-emerald-100/20 relative overflow-hidden group interactive-scale cursor-default stagger-1">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">รอดำเนินการ</p>
                    <h3 class="text-4xl font-black text-slate-900 leading-none">{{ $stats['pending'] }}</h3>
                    <p class="text-xs text-amber-500 font-bold mt-2 flex items-center">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                        ต้องการความช่วยเหลือ
                    </p>
                </div>
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl"></div>
        </div>

        <!-- Processing -->
        <div class="glass-card p-8 rounded-[2.5rem] border border-white/40 shadow-2xl shadow-emerald-100/20 relative overflow-hidden group stagger-2">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">กำลังทำ</p>
                    <h3 class="text-4xl font-black text-slate-900 leading-none">{{ $stats['processing'] }}</h3>
                    <p class="text-xs text-emerald-500 font-bold mt-2">เจ้าหน้าที่กำลังแก้ไข</p>
                </div>
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl"></div>
        </div>

        <!-- Completed -->
        <div class="glass-card p-8 rounded-[2.5rem] border border-white/40 shadow-2xl shadow-emerald-100/20 relative overflow-hidden group stagger-3">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">เสร็จแล้ว</p>
                    <h3 class="text-4xl font-black text-slate-900 leading-none">{{ $stats['completed'] }}</h3>
                    <p class="text-xs text-emerald-500 font-bold mt-2">ปิดงานเรียบร้อย</p>
                </div>
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl"></div>
        </div>

        <!-- More Info -->
        <div class="glass-card p-8 rounded-[2.5rem] border border-white/40 shadow-2xl shadow-emerald-100/20 relative overflow-hidden group">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">รอข้อมูลเพิ่ม</p>
                    <h3 class="text-4xl font-black text-slate-900 leading-none">{{ $stats['more_info'] }}</h3>
                    <p class="text-xs text-rose-500 font-bold mt-2">รอผู้แจ้งตอบกลับ</p>
                </div>
                <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl"></div>
        </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-10 flex items-center gap-4 overflow-x-auto pb-4 no-scrollbar">
        <a href="{{ route('admin.tickets.create') }}" class="cta-gradient text-white px-8 py-4 rounded-3xl font-bold shadow-xl shadow-emerald-200 hover:shadow-emerald-400 hover:-translate-y-1 interactive-scale transition-all flex items-center whitespace-nowrap">
            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            สร้างใบงานด่วน
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'pending']) }}" class="bg-white text-slate-700 px-8 py-4 rounded-3xl font-bold border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 interactive-scale transition-all flex items-center whitespace-nowrap">
            <div class="w-8 h-8 bg-amber-50 rounded-xl flex items-center justify-center mr-3 text-amber-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            จัดการงานค้าง
        </a>
        <a href="{{ route('admin.tickets.export') }}" class="bg-white text-slate-700 px-8 py-4 rounded-3xl font-bold border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 interactive-scale transition-all flex items-center whitespace-nowrap">
            <div class="w-8 h-8 bg-slate-50 rounded-xl flex items-center justify-center mr-3 text-slate-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            ส่งออกรายงาน
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Analytics Chart -->
        <div class="lg:col-span-2">
            <div class="glass-card rounded-3xl p-8 h-full">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Workload Trends</h2>
                        <p class="text-slate-400 text-sm italic">จำนวนการรับแจ้งซ่อมย้อนหลัง 7 วัน</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Latest Tickets -->
        <div class="lg:col-span-1">
            <div class="glass-card rounded-3xl overflow-hidden h-full flex flex-col">
                <div class="px-6 py-6 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-bold text-slate-900">ล่าสุด</h2>
                    <a href="{{ route('admin.tickets.index') }}" class="text-emerald-600 text-sm font-bold hover:underline">ทั้งหมด</a>
                </div>
                <div class="flex-grow">
                    <table class="w-full text-left border-collapse">
                        <tbody class="divide-y divide-slate-50">
                            @forelse($latestTickets as $ticket)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-700 text-sm">{{ $ticket->requester_name }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $ticket->ticket_number }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'processing' => 'bg-emerald-100 text-emerald-700',
                                            'completed' => 'bg-emerald-100 text-emerald-700',
                                            'more_info' => 'bg-rose-100 text-rose-700',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $colors[$ticket->status] }}">
                                        {{ $ticket->status === 'pending' ? 'รอดำเนินการ' : ($ticket->status === 'processing' ? 'กำลังดำเนินการ' : ($ticket->status === 'completed' ? 'เสร็จสิ้น' : 'ขอข้อมูลเพิ่ม')) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-6 py-10 text-center text-slate-400 italic text-sm">ไม่มีรายการใหม่</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mt-8">
        <div class="lg:col-span-1">
            <div class="glass-card rounded-3xl p-8">
                <h2 class="font-bold text-slate-900 mb-6 text-center">สัดส่วนหน่วยงาน</h2>
                <div class="h-48 relative">
                    <canvas id="deptChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-3">
            <div class="glass-card rounded-[2.5rem] p-10 h-full relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold mb-2 text-slate-900">สรุปภาพรวมระบบ</h2>
                    <p class="text-slate-500 text-sm mb-10 font-medium">ประสิทธิภาพการทำงานของฝ่าย IT คณะแพทยศาสตร์</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Average Response</p>
                            <h4 class="text-4xl font-black text-slate-900">1.2 <span class="text-base font-normal text-slate-400 ml-1">ชม.</span></h4>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Success Rate</p>
                            <h4 class="text-4xl font-black text-emerald-600">98.5 <span class="text-base font-normal text-slate-400 ml-1">%</span></h4>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Satisfaction</p>
                            <h4 class="text-4xl font-black text-emerald-600">4.9 <span class="text-base font-normal text-slate-400 ml-1">/ 5</span></h4>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
                <div class="absolute -left-20 -top-20 w-64 h-64 bg-cyan-500/5 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    const gradient = ctxTrend.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Tickets',
                data: {!! json_encode($chartData['data']) !!},
                borderColor: '#0891b2',
                borderWidth: 4,
                tension: 0.4,
                fill: true,
                backgroundColor: gradient,
                pointBackgroundColor: '#0891b2',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { display: false },
                    ticks: { stepSize: 1, color: '#94a3b8' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8' }
                }
            }
        }
    });

    const ctxDept = document.getElementById('deptChart').getContext('2d');
    new Chart(ctxDept, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deptStats->pluck('department.name')) !!},
            datasets: [{
                data: {!! json_encode($deptStats->pluck('count')) !!},
                backgroundColor: [
                    '#10b981', '#059669', '#34d399', '#6ee7b7', '#a7f3d0', '#06b6d4'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
