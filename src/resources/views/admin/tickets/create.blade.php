@extends('layouts.app')

@section('title', 'สร้างใบงานใหม่ (สำหรับ Admin)')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-bold transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            กลับหน้ารวม
        </a>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden">
        <div class="primary-gradient p-8 text-white">
            <h1 class="text-2xl font-bold italic">Manual Ticket Entry</h1>
            <p class="text-indigo-100 text-sm mt-1">เจ้าหน้าที่คีย์ข้อมูลใบงานแทนผู้แจ้งหรือรับเรื่องโดยตรง</p>
        </div>

        <form action="{{ route('admin.tickets.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ชื่อผู้แจ้ง/ผู้ขอรับบริการ</label>
                    <input type="text" name="requester_name" value="{{ old('requester_name') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                        placeholder="ระบุชื่อ-นามสกุล">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">เบอร์โทรศัพท์</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                        placeholder="08X-XXX-XXXX">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">หน่วยงาน</label>
                    <select name="department_id" id="admin_dept_select" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all appearance-none bg-white">
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
                    <select name="job_type_id" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all appearance-none bg-white">
                        <option value="">เลือกประเภทงาน</option>
                        @foreach($jobTypes as $type)
                            <option value="{{ $type->id }}" {{ old('job_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">รายละเอียดปัญหา/ความต้องการ</label>
                <textarea name="details" rows="4" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                    placeholder="ระบุรายละเอียดปัญหาที่ได้รับแจ้ง...">{{ old('details') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-400 italic">** เมื่อบันทึกแล้ว สถานะจะเป็น "กำลังดำเนินการ" และคุณจะเป็นผู้รับผิดชอบโดยอัตโนมัติ</p>
                <button type="submit" class="primary-gradient text-white font-bold px-10 py-3 rounded-xl shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transform transition-all active:scale-95">
                    บันทึกใบงาน
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#admin_dept_select', {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });
</script>
@endsection
