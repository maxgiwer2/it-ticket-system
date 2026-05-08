@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Dashboard</h1>
            <p class="text-slate-500">ภาพรวมสถานะการแจ้งซ่อมทั้งหมดในระบบ</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Pending -->
        <div class="glass-card p-6 rounded-2xl border-l-4 border-amber-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">รอดำเนินการ</p>
                    <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['pending'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Processing -->
        <div class="glass-card p-6 rounded-2xl border-l-4 border-blue-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">กำลังทำ</p>
                    <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['processing'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="glass-card p-6 rounded-2xl border-l-4 border-emerald-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">เสร็จแล้ว</p>
                    <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['completed'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </div>

        <!-- Total -->
        <div class="glass-card p-6 rounded-2xl border-l-4 border-indigo-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">ทั้งหมด</p>
                    <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['total'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Latest Tickets -->
        <div class="lg:col-span-2">
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-bold text-slate-900">รายการแจ้งซ่อมล่าสุด</h2>
                    <a href="{{ route('admin.tickets.index') }}" class="text-indigo-600 text-sm font-bold hover:underline">ดูทั้งหมด</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">หมายเลข</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">ผู้แจ้ง</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">หน่วยงาน</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($latestTickets as $ticket)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-indigo-600 font-bold hover:underline">
                                        {{ $ticket->ticket_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">{{ $ticket->requester_name }}</td>
                                <td class="px-6 py-4 text-slate-500 text-sm">{{ $ticket->department->name }}</td>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Dept Stats -->
        <div>
            <div class="glass-card rounded-2xl p-6">
                <h2 class="font-bold text-slate-900 mb-6 text-center">สถิติแยกตามหน่วยงาน</h2>
                <div class="space-y-4">
                    @foreach($deptStats as $stat)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 truncate mr-4">{{ $stat->department->name }}</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-slate-100 h-2 rounded-full mr-3 overflow-hidden">
                                <div class="bg-indigo-500 h-full rounded-full" style="width: {{ ($stat->count / $stats['total']) * 100 }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-900">{{ $stat->count }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
