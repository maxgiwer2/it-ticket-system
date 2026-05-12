@extends('layouts.app')

@section('title', 'รายงานสถิติใบงาน')

@section('content')
<div class="max-w-[1600px] mx-auto">
    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">รายการสถิติ "ใบงาน" ของเจ้าหน้าที่</h1>
    </div>

    <!-- Mode Selector (Visual Tabs) -->
    <div class="flex justify-center gap-4 mb-8">
        <button class="px-6 py-2.5 bg-slate-700 text-white font-bold rounded-xl shadow-md hover:bg-slate-800 transition-colors">ดูรายเดือน/ช่วงเวลา (ทุกคน)</button>
        <button class="px-6 py-2.5 bg-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-300 transition-colors">ดูตามรายบุคคล</button>
    </div>

    <!-- Filter Forms -->
    <div class="space-y-6 mb-12">
        <!-- Monthly / Range Filter -->
        <div class="glass-card rounded-[2rem] p-6 border border-white/60 shadow-xl">
            <h3 class="text-xl font-black text-slate-800 mb-4">ดูรายงานแต่ละเดือน</h3>
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center gap-4">
                <input type="hidden" name="report_type" value="range">
                <div class="w-full md:w-64">
                    <select name="user_id" class="w-full px-4 py-3 rounded-xl border-none bg-slate-100/50 focus:ring-2 focus:ring-emerald-500 outline-none text-sm font-medium text-slate-700">
                        <option value="all">ทุกคน</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <span class="text-sm font-bold text-slate-600 whitespace-nowrap">เลือกช่วงวันที่ :</span>
                    <input type="date" name="date_from" value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}" class="px-4 py-3 rounded-xl border-none bg-slate-100/50 focus:ring-2 focus:ring-emerald-500 outline-none text-sm font-medium text-slate-700 w-full md:w-40">
                    <span class="text-sm font-bold text-slate-600">ถึง</span>
                    <input type="date" name="date_to" value="{{ request('date_to', $dateTo->format('Y-m-d')) }}" class="px-4 py-3 rounded-xl border-none bg-slate-100/50 focus:ring-2 focus:ring-emerald-500 outline-none text-sm font-medium text-slate-700 w-full md:w-40">
                </div>

                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-600 transition-colors">ดึงข้อมูล</button>
            </form>
        </div>

        <!-- Yearly Filter -->
        <div class="glass-card rounded-[2rem] p-6 border border-white/60 shadow-xl">
            <h3 class="text-xl font-black text-slate-800 mb-4">ดูรายงานทั้งปี</h3>
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center gap-4">
                <input type="hidden" name="report_type" value="yearly">
                <div class="w-full md:w-64">
                    <select name="user_id" class="w-full px-4 py-3 rounded-xl border-none bg-slate-100/50 focus:ring-2 focus:ring-emerald-500 outline-none text-sm font-medium text-slate-700">
                        <option value="all">เลือกเจ้าหน้าที่ (ทุกคน)</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <span class="text-sm font-bold text-slate-600 whitespace-nowrap">เลือกช่วงวันที่ :</span>
                    <select name="year" class="px-4 py-3 rounded-xl border-none bg-slate-100/50 focus:ring-2 focus:ring-emerald-500 outline-none text-sm font-medium text-slate-700 w-full md:w-32">
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ request('year', \Carbon\Carbon::now()->year + 543) == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-600 transition-colors">ดึงข้อมูล</button>
            </form>
        </div>
    </div>

    <!-- Report Results Area -->
    <div class="mb-12">
        <h2 class="text-xl font-black text-slate-800 mb-2">สรุปใบงาน/ภาระงานรวมของ {{ $userId === 'all' ? 'ทุกคน' : $users->where('id', $userId)->first()->name ?? 'ผู้ใช้งาน' }} (ช่วงวันที่ {{ $dateFrom->format('d/m/Y') }} ถึง {{ $dateTo->format('d/m/Y') }})</h2>
        <p class="text-lg font-bold text-emerald-600 mb-6">รวมใบงานทั้งหมด: {{ $tickets->count() }} ใบ</p>

        <!-- Chart Section -->
        <div class="glass-card rounded-[2rem] p-6 border border-white/60 shadow-xl mb-8 relative h-[400px]">
            <canvas id="jobChart"></canvas>
        </div>

        <!-- Matrix Table -->
        <div class="glass-card rounded-[2rem] overflow-hidden border border-white/60 shadow-xl mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr class="bg-blue-200/50">
                            <th class="px-4 py-3 border border-slate-200 text-xs font-black text-slate-700 whitespace-nowrap min-w-[150px]">ชื่อ</th>
                            @foreach($jobTypes as $jt)
                                <th class="px-2 py-3 border border-slate-200 text-[10px] font-bold text-slate-700 whitespace-nowrap" style="writing-mode: vertical-rl; transform: rotate(180deg);">{{ $jt->name }}</th>
                            @endforeach
                            <th class="px-4 py-3 border border-slate-200 text-xs font-black text-slate-700 whitespace-nowrap">รวม</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/50">
                        @foreach($matrixData as $userId => $data)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-2 border border-slate-200 text-xs font-bold text-slate-700 text-left">{{ $data['user']->name }}</td>
                            @foreach($jobTypes as $jt)
                                <td class="px-2 py-2 border border-slate-200 text-xs text-slate-600">{{ $data['job_types'][$jt->id] > 0 ? $data['job_types'][$jt->id] : '0' }}</td>
                            @endforeach
                            <td class="px-4 py-2 border border-slate-200 text-sm font-black text-emerald-600">{{ $data['total'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detail Table -->
        <div class="glass-card rounded-[2rem] overflow-hidden border border-white/60 shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-emerald-200/50 text-center">
                            <th class="px-4 py-3 border border-slate-200 text-xs font-black text-slate-700 w-16">ลำดับ</th>
                            <th class="px-4 py-3 border border-slate-200 text-xs font-black text-slate-700 w-32">วันที่</th>
                            <th class="px-4 py-3 border border-slate-200 text-xs font-black text-slate-700 w-48">ประเภท</th>
                            <th class="px-4 py-3 border border-slate-200 text-xs font-black text-slate-700">รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/50">
                        @forelse($tickets as $index => $ticket)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 border border-slate-200 text-xs text-center text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 border border-slate-200 text-xs text-center font-medium text-slate-700">{{ $ticket->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 border border-slate-200 text-xs font-bold text-slate-700 text-center">{{ $ticket->jobType->name ?? '-' }}</td>
                            <td class="px-4 py-3 border border-slate-200 text-xs text-slate-600">{{ $ticket->details }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500 text-sm">ไม่พบข้อมูลใบงานในช่วงเวลาดังกล่าว</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('jobChart').getContext('2d');
        
        // Prepare data from backend
        const chartDataRaw = @json($chartData);
        const labels = Object.keys(chartDataRaw);
        const data = labels.map(label => chartDataRaw[label].count);
        const backgroundColors = labels.map(label => chartDataRaw[label].color);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนใบงาน',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 0,
                    borderRadius: 4,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // We can hide legend if we want it to look like the image, or show it. Let's hide to match image cleaner look.
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { size: 14, family: "'Figtree', sans-serif" },
                        bodyFont: { size: 14, family: "'Figtree', sans-serif", weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(241, 245, 249, 1)',
                            drawBorder: false,
                        },
                        ticks: {
                            stepSize: 1,
                            font: { family: "'Figtree', sans-serif" }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: "'Figtree', sans-serif", weight: 'bold' },
                            color: '#475569'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
