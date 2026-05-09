@extends('layouts.app')

@section('title', 'จัดการ Ticket ' . $ticket->ticket_number)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center text-slate-500 hover:text-emerald-600 font-bold transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            กลับหน้ารวม
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card rounded-3xl overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">{{ $ticket->ticket_number }}</h1>
                        <p class="text-slate-500 text-sm">แจ้งเมื่อวันที่ {{ $ticket->created_at->format('d/m/Y H:i') }} น.</p>
                    </div>
                    @php
                        $colors = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'processing' => 'bg-emerald-100 text-emerald-700',
                            'completed' => 'bg-emerald-100 text-emerald-700',
                            'more_info' => 'bg-rose-100 text-rose-700',
                        ];
                        $labels = [
                            'pending' => 'รอดำเนินการ',
                            'processing' => 'กำลังดำเนินการ',
                            'completed' => 'เสร็จสิ้น',
                            'more_info' => 'ขอข้อมูลเพิ่ม',
                        ];
                    @endphp
                    <span class="px-4 py-1.5 rounded-full text-sm font-bold {{ $colors[$ticket->status] }}">
                        {{ $labels[$ticket->status] }}
                    </span>
                </div>
                
                <div class="p-8 space-y-8">
                    <!-- User Info Grid -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">ผู้แจ้ง</p>
                            <p class="font-bold text-slate-900">{{ $ticket->requester_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">เบอร์ติดต่อ</p>
                            <p class="text-slate-700">{{ $ticket->phone }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">หน่วยงาน</p>
                            <p class="text-slate-700">
                                {{ $ticket->department->name }}
                                <span class="block text-[10px] text-slate-400 uppercase font-bold tracking-tighter">
                                    {{ $ticket->department->type === 'faculty' ? 'คณะแพทยศาสตร์' : 'ศูนย์การแพทย์ฯ' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">ประเภทงาน</p>
                            <p class="text-slate-700">{{ $ticket->jobType->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">ผู้รับผิดชอบหลัก</p>
                            @if($ticket->technician)
                                <p class="text-emerald-600 font-bold flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $ticket->technician->name }}
                                </p>
                            @else
                                <p class="text-slate-400 italic">ยังไม่มีผู้รับเรื่อง</p>
                            @endif
                        </div>
                        @if($ticket->collaborators->count() > 0)
                        <div class="col-span-2">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">ผู้ร่วมปฏิบัติงาน</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($ticket->collaborators as $collab)
                                    <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100 flex items-center">
                                        <div class="w-4 h-4 rounded-full bg-emerald-200 mr-2 flex items-center justify-center text-[8px] text-emerald-700">
                                            {{ substr($collab->name, 0, 1) }}
                                        </div>
                                        {{ $collab->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Problem Details -->
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">รายละเอียดปัญหา</p>
                        <div class="bg-slate-50 p-6 rounded-2xl text-slate-700 leading-relaxed border border-slate-100">
                            {{ $ticket->details }}
                        </div>
                    </div>

                    @if($ticket->attachment_path)
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ไฟล์แนบ</p>
                        <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            ดูไฟล์แนบ
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Sidebar -->
        <div class="space-y-6">
            <div class="glass-card rounded-3xl p-8 border-2 border-emerald-50 shadow-xl shadow-emerald-100/50">
                <h3 class="font-bold text-slate-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    จัดการสถานะ
                </h3>
                
                @php
                    $isLocked = $ticket->status === 'completed' && auth()->user()->role !== 'superadmin';
                @endphp

                @if($isLocked)
                <div class="mb-6 p-4 bg-cyan-50 rounded-2xl border border-cyan-100 flex items-start">
                    <svg class="w-5 h-5 text-cyan-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs text-cyan-700 leading-relaxed">
                        <span class="font-bold block mb-1 underline">งานเสร็จสิ้นแล้ว</span>
                        ใบงานนี้ถูกล็อคไม่ให้แก้ไขเพิ่มเติม หากต้องการเปลี่ยนแปลงข้อมูล กรุณาติดต่อ Superadmin
                    </p>
                </div>
                @endif
                
                @if(!$ticket->assigned_to && !$isLocked)
                <div class="mb-8 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                    <p class="text-sm text-emerald-700 font-medium mb-4">ใบงานนี้ยังไม่มีผู้รับผิดชอบ</p>
                    <form action="{{ route('admin.tickets.accept', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all">
                            กดเพื่อรับใบงาน
                        </button>
                    </form>
                </div>
                @endif

                <form action="{{ route('admin.tickets.updateStatus', $ticket->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">อัปเดตสถานะงาน</label>
                        <select name="status" {{ $isLocked ? 'disabled' : '' }} class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all {{ $isLocked ? 'bg-slate-50 text-slate-400' : 'bg-white text-slate-700' }} font-medium">
                            <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                            <option value="processing" {{ $ticket->status == 'processing' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                            <option value="completed" {{ $ticket->status == 'completed' ? 'selected' : '' }}>เสร็จสิ้น</option>
                            <option value="more_info" {{ $ticket->status == 'more_info' ? 'selected' : '' }}>ขอข้อมูลเพิ่ม</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">หมายเหตุจากเจ้าหน้าที่</label>
                        <textarea name="admin_note" rows="5" {{ $isLocked ? 'disabled' : '' }} class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all {{ $isLocked ? 'bg-slate-50 text-slate-400' : 'bg-white' }} placeholder:text-slate-300" 
                                  placeholder="ระบุความคืบหน้าหรือข้อความถึงผู้แจ้ง...">{{ $ticket->admin_note }}</textarea>
                    </div>

                    @if(!$isLocked)
                    <button type="submit" class="w-full primary-gradient text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-100 hover:shadow-emerald-200 transform transition-all active:scale-95">
                        บันทึกการอัปเดต
                    </button>
                    @endif
                </form>
            </div>

            <!-- Helpful Info -->
            <div class="p-6 bg-slate-900 rounded-3xl text-white">
                <h4 class="font-bold text-emerald-400 mb-2">💡 ข้อแนะนำ</h4>
                <p class="text-xs text-slate-400 leading-relaxed italic">
                    "การอัปเดตสถานะและหมายเหตุจะแสดงให้ผู้แจ้งซ่อมเห็นทันทีผ่านหน้าติดตามสถานะ กรุณาระบุข้อมูลที่ชัดเจนเพื่อลดการสอบถามซ้ำ"
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
