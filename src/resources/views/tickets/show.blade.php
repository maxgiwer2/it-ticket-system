@extends('layouts.app')

@section('title', 'หมายเลข Ticket ' . $ticket->ticket_number)

@section('content')
<div class="glass-card rounded-2xl overflow-hidden">
    <div class="primary-gradient p-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-wider">Ticket Details</span>
                <h1 class="text-3xl font-bold mt-2">{{ $ticket->ticket_number }}</h1>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <p class="text-white/80 text-sm">สถานะปัจจุบัน</p>
                @php
                    $statusColors = [
                        'pending' => 'bg-amber-400',
                        'processing' => 'bg-blue-400',
                        'completed' => 'bg-emerald-400',
                        'more_info' => 'bg-rose-400'
                    ];
                    $statusText = [
                        'pending' => 'รอดำเนินการ',
                        'processing' => 'กำลังดำเนินการ',
                        'completed' => 'เสร็จสิ้น',
                        'more_info' => 'ต้องการข้อมูลเพิ่ม'
                    ];
                @endphp
                <span class="inline-block px-4 py-1 {{ $statusColors[$ticket->status] }} text-white font-bold rounded-full mt-1">
                    {{ $statusText[$ticket->status] }}
                </span>
            </div>
        </div>
    </div>

    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div class="space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">ข้อมูลผู้แจ้ง</h3>
                    <p class="text-lg font-medium text-slate-900 mt-1">{{ $ticket->requester_name }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">หน่วยงาน</h3>
                    <p class="text-slate-700 mt-1">{{ $ticket->department->name }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">เบอร์ติดต่อ</h3>
                    <p class="text-slate-700 mt-1">{{ $ticket->phone }}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">ประเภทงาน</h3>
                    <p class="text-lg font-medium text-slate-900 mt-1">{{ $ticket->jobType->name }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">วันที่แจ้ง</h3>
                    <p class="text-slate-700 mt-1">{{ $ticket->created_at->format('d/m/Y H:i') }} น.</p>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-8">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">รายละเอียดปัญหา</h3>
            <div class="bg-slate-50 p-6 rounded-2xl text-slate-700 leading-relaxed">
                {{ $ticket->details }}
            </div>
        </div>

        @if($ticket->attachment_path)
        <div class="mt-8">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ไฟล์แนบ</h3>
            <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" 
               class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-indigo-50 text-indigo-600 rounded-xl transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                เปิดไฟล์แนบ
            </a>
        </div>
        @endif
    </div>
    
    <div class="bg-slate-50 p-8 border-t border-slate-100 flex justify-center">
        <a href="{{ route('tickets.create') }}" class="text-indigo-600 font-bold hover:underline">ส่งใบแจ้งซ่อมใหม่</a>
        <span class="mx-4 text-slate-300">|</span>
        <a href="{{ route('tickets.status') }}" class="text-slate-500 font-bold hover:underline">ติดตามสถานะอื่น</a>
    </div>
</div>
@endsection
