@extends('layouts.app')

@section('title', 'รายงานจำนวนใบงานแยกตามหน่วยงาน')

@section('content')
<div class="max-w-[1600px] mx-auto">
    <!-- Header & Filters -->
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">รายงานใบงานแบ่งตามประเภทหน่วยงาน</h1>
            <p class="text-xl font-bold text-slate-600 mt-2">
                ของเดือน {{ $months[$selectedMonth] }} ปี {{ $selectedYear + 543 }}
            </p>
        </div>

        <div class="glass-card rounded-[2rem] p-4 border border-white/60 shadow-lg">
            <form action="{{ route('admin.reports.departments') }}" method="GET" class="flex items-center gap-4">
                <select name="month" class="px-4 py-2 rounded-xl border-none bg-slate-100/50 focus:ring-2 focus:ring-emerald-500 outline-none font-bold text-slate-700 w-40">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <select name="year" class="px-4 py-2 rounded-xl border-none bg-slate-100/50 focus:ring-2 focus:ring-emerald-500 outline-none font-bold text-slate-700 w-32">
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ ($selectedYear + 543) == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white font-bold rounded-xl shadow hover:bg-blue-600 transition-colors">ดึงข้อมูล</button>
            </form>
        </div>
    </div>

    <!-- Data Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
        <!-- Left Column: Department Stats -->
        <div class="lg:col-span-5">
            <h2 class="text-lg font-black text-slate-800 mb-4">จำนวนใบงานของแต่ละหน่วยงาน</h2>
            <div class="glass-card rounded-2xl overflow-hidden border border-white/60 shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-cyan-100/60">
                            <th class="px-4 py-3 border border-slate-300 text-sm font-black text-slate-700 text-center w-16">ลำดับ</th>
                            <th class="px-4 py-3 border border-slate-300 text-sm font-black text-slate-700">หน่วยงาน</th>
                            <th class="px-4 py-3 border border-slate-300 text-sm font-black text-slate-700 text-center w-32">จำนวนใบงาน</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/80">
                        @php $i = 1; $totalDepts = 0; @endphp
                        @foreach($departmentStats as $dept => $count)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-2 border border-slate-300 text-sm font-bold text-center text-slate-600">{{ $i++ }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-sm font-medium text-slate-700">{{ $dept }}</td>
                            <td class="px-4 py-2 border border-slate-300 text-sm font-black text-center text-slate-700">{{ $count }}</td>
                        </tr>
                        @php $totalDepts += $count; @endphp
                        @endforeach
                        @if($departmentStats->isEmpty())
                        <tr><td colspan="3" class="px-4 py-6 text-center text-slate-500">ไม่พบข้อมูล</td></tr>
                        @else
                        <tr class="bg-slate-100/60 font-black">
                            <td colspan="2" class="px-4 py-3 border border-slate-300 text-right text-slate-700">รวม</td>
                            <td class="px-4 py-3 border border-slate-300 text-center text-emerald-600">{{ $totalDepts }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Job Type Stats & Detail Table -->
        <div class="lg:col-span-7 flex flex-col gap-8">
            
            <!-- Job Type Stats -->
            <div>
                <h2 class="text-lg font-black text-slate-800 mb-4">จำนวนใบงานแบ่งตามประเภท</h2>
                <div class="glass-card rounded-2xl overflow-hidden border border-white/60 shadow-xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-amber-200/50">
                                <th class="px-4 py-3 border border-slate-300 text-sm font-black text-slate-700 text-center w-16">ลำดับ</th>
                                <th class="px-4 py-3 border border-slate-300 text-sm font-black text-slate-700">ประเภท</th>
                                <th class="px-4 py-3 border border-slate-300 text-sm font-black text-slate-700 text-center w-32">จำนวนใบงาน</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80">
                            @php $j = 1; $totalTypes = 0; @endphp
                            @foreach($jobTypeStats as $type => $count)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-2 border border-slate-300 text-sm font-bold text-center text-slate-600">{{ $j++ }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-sm font-medium text-slate-700">{{ $type }}</td>
                                <td class="px-4 py-2 border border-slate-300 text-sm font-black text-center text-slate-700">{{ $count }}</td>
                            </tr>
                            @php $totalTypes += $count; @endphp
                            @endforeach
                            @if($jobTypeStats->isEmpty())
                            <tr><td colspan="3" class="px-4 py-6 text-center text-slate-500">ไม่พบข้อมูล</td></tr>
                            @else
                            <tr class="bg-slate-100/60 font-black">
                                <td colspan="2" class="px-4 py-3 border border-slate-300 text-right text-slate-700">รวม</td>
                                <td class="px-4 py-3 border border-slate-300 text-center text-emerald-600">{{ $totalTypes }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail Table -->
            <div>
                <h2 class="text-lg font-black text-slate-800 mb-4">ใบงานทั้งหมด</h2>
                <div class="glass-card rounded-2xl overflow-hidden border border-white/60 shadow-xl max-h-[600px] overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 z-10">
                            <tr class="bg-emerald-200/60">
                                <th class="px-4 py-3 border border-slate-300 text-sm font-black text-slate-700 w-48">หน่วยงาน</th>
                                <th class="px-4 py-3 border border-slate-300 text-sm font-black text-slate-700 w-28 text-center">วันที่</th>
                                <th class="px-4 py-3 border border-slate-300 text-sm font-black text-slate-700 w-32">ประเภท</th>
                                <th class="px-4 py-3 border border-slate-300 text-sm font-black text-slate-700">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80">
                            @forelse($tickets as $ticket)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-3 border border-slate-300 text-xs font-bold text-slate-700">{{ $ticket->department->name ?? 'ไม่ระบุ' }}</td>
                                <td class="px-4 py-3 border border-slate-300 text-xs font-medium text-slate-600 text-center">{{ $ticket->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 border border-slate-300 text-xs font-medium text-slate-700">{{ $ticket->jobType->name ?? 'ไม่ระบุ' }}</td>
                                <td class="px-4 py-3 border border-slate-300 text-xs text-slate-600 leading-relaxed">{{ Str::limit($ticket->details, 150) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500 text-sm">ไม่พบข้อมูลใบงานในเดือนนี้</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
