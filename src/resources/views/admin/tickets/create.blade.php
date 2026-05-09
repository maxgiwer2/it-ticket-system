@extends('layouts.app')

@section('title', 'สร้างใบงานใหม่ (สำหรับ Admin)')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center text-slate-500 hover:text-emerald-600 font-bold transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            กลับหน้ารวม
        </a>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden">
        <div class="primary-gradient p-8 text-white">
            <h1 class="text-2xl font-bold italic">Manual Ticket Entry</h1>
            <p class="text-emerald-100 text-sm mt-1">เจ้าหน้าที่คีย์ข้อมูลใบงานแทนผู้แจ้งหรือรับเรื่องโดยตรง</p>
        </div>

        <form action="{{ route('admin.tickets.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ชื่อผู้แจ้ง/ผู้ขอรับบริการ</label>
                    <input type="text" name="requester_name" value="{{ old('requester_name') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                        placeholder="ระบุชื่อ-นามสกุล">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">เบอร์ภายใน</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                        placeholder="ตัวอย่าง 60401">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">หน่วยงาน</label>
                    <select name="department_id" id="admin_dept_select" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all appearance-none bg-white">
                        <option value="">เลือกหน่วยงาน</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} ({{ $dept->type === 'faculty' ? 'คณะแพทย์' : 'ศูนย์การแพทย์' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ประเภทงาน</label>
                    <div class="space-y-3">
                        <div class="flex gap-2 p-1 bg-slate-100 rounded-xl">
                            @foreach($jobTypes as $category => $types)
                            <button type="button" class="category-btn flex-1 py-2 px-3 rounded-lg text-xs font-bold transition-all {{ $loop->first ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:bg-white/50' }}"
                                data-category="{{ $category }}">
                                {{ $category }}
                            </button>
                            @endforeach
                        </div>
                        <select name="job_type_id" id="job_type_select" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all appearance-none bg-white">
                            <option value="">เลือกหัวข้อ...</option>
                            @foreach($jobTypes as $category => $types)
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" data-category="{{ $category }}" {{ old('job_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    ผู้ร่วมปฏิบัติงาน (Co-workers)
                    <span class="ml-2 text-[10px] font-normal text-slate-400 uppercase tracking-widest">เลือกทีมที่ร่วมทำใบงานนี้</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($admins as $admin)
                    @if($admin->id !== auth()->id())
                    <label class="cursor-pointer group">
                        <input type="checkbox" name="collaborators[]" value="{{ $admin->id }}" class="hidden peer">
                        <div class="p-3 rounded-xl border border-slate-100 bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 transition-all flex items-center group-hover:border-slate-200">
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex-shrink-0 mr-2 flex items-center justify-center font-bold text-xs">
                                {{ substr($admin->name, 0, 1) }}
                            </div>
                            <span class="text-xs font-bold truncate">{{ $admin->name }}</span>
                        </div>
                    </label>
                    @endif
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">รายละเอียดปัญหา/ความต้องการ</label>
                <textarea name="details" rows="4" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                    placeholder="ระบุรายละเอียดปัญหาที่ได้รับแจ้ง...">{{ old('details') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-400 italic">** สถานะจะเป็น "กำลังดำเนินการ" และคุณจะเป็นผู้รับผิดชอบหลัก</p>
                <button type="submit" class="primary-gradient text-white font-bold px-10 py-3 rounded-xl shadow-lg shadow-emerald-100 hover:shadow-emerald-200 transform transition-all active:scale-95">
                    บันทึกใบงาน
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Department Select
        new TomSelect('#admin_dept_select', { create: false });

        // Category Filtering
        const jobSelect = document.getElementById('job_type_select');
        const options = Array.from(jobSelect.options);
        const categoryBtns = document.querySelectorAll('.category-btn');

        function filterJobs(category) {
            jobSelect.innerHTML = '<option value="">เลือกหัวข้อในหมวด ' + category + '...</option>';
            options.forEach(opt => {
                if (opt.dataset.category === category) {
                    jobSelect.add(opt.cloneNode(true));
                }
            });
            
            categoryBtns.forEach(btn => {
                if (btn.dataset.category === category) {
                    btn.classList.add('bg-white', 'text-emerald-600', 'shadow-sm');
                    btn.classList.remove('text-slate-500', 'hover:bg-white/50');
                } else {
                    btn.classList.remove('bg-white', 'text-emerald-600', 'shadow-sm');
                    btn.classList.add('text-slate-500', 'hover:bg-white/50');
                }
            });
        }

        categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => filterJobs(btn.dataset.category));
        });

        // Initialize with first category
        if (categoryBtns.length > 0) {
            filterJobs(categoryBtns[0].dataset.category);
        }
    });
</script>
@endsection
