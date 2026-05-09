@extends('layouts.app')

@section('title', 'บันทึกภาระงาน')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <a href="{{ route('admin.workloads.index') }}" class="inline-flex items-center text-slate-500 hover:text-emerald-600 transition-colors font-bold mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            กลับหน้าสรุป
        </a>
        <h1 class="text-4xl font-black text-slate-900 italic tracking-tight">Log Workload</h1>
        <p class="text-slate-500 mt-2">บันทึกภาระงานประจำวันสำหรับสรุปผลงานรายเดือน</p>
    </div>

    <form action="{{ route('admin.workloads.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="glass-card p-10 rounded-[3rem] border border-white/60 shadow-2xl relative overflow-hidden">
            <!-- Background Accent -->
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 space-y-8">
                <!-- Type Selector -->
                <div>
                    <label class="block text-sm font-black text-slate-700 uppercase tracking-widest mb-4">ประเภทภาระงาน</label>
                    <input type="hidden" name="type" id="workload_type" value="{{ old('type', 'ประชุม') }}">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(['อบรม', 'ช่วยงาน/ event', 'ประชุม', 'พัฒนาระบบ/ Server'] as $type)
                        <button type="button" 
                            onclick="selectType('{{ $type }}')"
                            id="btn-{{ $type }}"
                            class="type-btn p-4 rounded-3xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-3 group {{ old('type', 'ประชุม') === $type ? 'border-emerald-500 bg-emerald-50/50 shadow-lg shadow-emerald-100' : 'border-slate-100 bg-white hover:border-emerald-200' }}">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 {{ old('type', 'ประชุม') === $type ? 'bg-emerald-500 text-white shadow-lg' : 'bg-slate-50 text-slate-400 group-hover:bg-emerald-100 group-hover:text-emerald-500' }}">
                                @if($type === 'อบรม')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                @elseif($type === 'ช่วยงาน/ event')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                @elseif($type === 'ประชุม')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                                @endif
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-tighter text-center leading-tight">{{ $type }}</span>
                        </button>
                        @endforeach
                    </div>
                    @error('type') <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-black text-slate-700 uppercase tracking-widest mb-3">วันที่ปฏิบัติงาน</label>
                        <input type="date" name="work_date" value="{{ old('work_date', date('Y-m-d')) }}" required
                            class="w-full px-6 py-4 rounded-3xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700">
                        @error('work_date') <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-black text-slate-700 uppercase tracking-widest mb-3">เอกสาร/รูปภาพแนบ</label>
                        <input type="file" name="attachment" 
                            class="w-full px-6 py-3 rounded-3xl border border-dashed border-slate-200 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-300 transition-all text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-emerald-500 file:text-white file:cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-2 italic">รองรับไฟล์ JPG, PNG, PDF, DOC (สูงสุด 5MB)</p>
                        @error('attachment') <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 uppercase tracking-widest mb-3">รายละเอียดการปฏิบัติงาน</label>
                    <textarea name="details" rows="5" required
                        class="w-full px-8 py-6 rounded-[2rem] border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-medium text-slate-700 placeholder:text-slate-300"
                        placeholder="ระบุรายละเอียดภาระงานที่ดำเนินการ..."></textarea>
                    @error('details') <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full cta-gradient text-white py-6 rounded-[2rem] font-black text-lg shadow-2xl shadow-emerald-200 hover:shadow-emerald-400 hover:-translate-y-1 active:scale-95 transition-all">
                        บันทึกข้อมูลภาระงาน
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function selectType(type) {
        document.getElementById('workload_type').value = type;
        
        // Reset all buttons
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.remove('border-emerald-500', 'bg-emerald-50/50', 'shadow-lg', 'shadow-emerald-100');
            btn.classList.add('border-slate-100', 'bg-white');
            
            const icon = btn.querySelector('div');
            icon.classList.remove('bg-emerald-500', 'text-white', 'shadow-lg');
            icon.classList.add('bg-slate-50', 'text-slate-400');
        });
        
        // Activate selected button
        const activeBtn = document.getElementById('btn-' + type);
        activeBtn.classList.remove('border-slate-100', 'bg-white');
        activeBtn.classList.add('border-emerald-500', 'bg-emerald-50/50', 'shadow-lg', 'shadow-emerald-100');
        
        const activeIcon = activeBtn.querySelector('div');
        activeIcon.classList.remove('bg-slate-50', 'text-slate-400');
        activeIcon.classList.add('bg-emerald-500', 'text-white', 'shadow-lg');
    }
</script>
@endsection
