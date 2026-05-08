@extends('layouts.app')

@section('title', 'แก้ไขหน่วยงาน')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.departments.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center mb-4 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            กลับหน้าจัดการ
        </a>
        <h1 class="text-3xl font-bold text-slate-800">แก้ไขหน่วยงาน</h1>
        <p class="text-slate-500">ปรับปรุงข้อมูลหน่วยงาน</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
        <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อหน่วยงาน</label>
                <input type="text" name="name" value="{{ old('name', $department->name) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">กลุ่มหน่วยงาน</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all {{ $department->type === 'faculty' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100' }}">
                        <input type="radio" name="type" value="faculty" class="hidden" {{ $department->type === 'faculty' ? 'checked' : '' }}>
                        <span class="font-bold {{ $department->type === 'faculty' ? 'text-indigo-600' : 'text-slate-500' }}">คณะแพทยศาสตร์</span>
                    </label>
                    <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all {{ $department->type === 'medical_center' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100' }}">
                        <input type="radio" name="type" value="medical_center" class="hidden" {{ $department->type === 'medical_center' ? 'checked' : '' }}>
                        <span class="font-bold {{ $department->type === 'medical_center' ? 'text-indigo-600' : 'text-slate-500' }}">ศูนย์การแพทย์ฯ</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">สถานะการใช้งาน</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all {{ $department->status === 'active' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100' }}">
                        <input type="radio" name="status" value="active" class="hidden" {{ $department->status === 'active' ? 'checked' : '' }}>
                        <span class="font-bold {{ $department->status === 'active' ? 'text-indigo-600' : 'text-slate-500' }}">เปิดใช้งาน</span>
                    </label>
                    <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all {{ $department->status === 'inactive' ? 'border-rose-500 bg-rose-50' : 'border-slate-100' }}">
                        <input type="radio" name="status" value="inactive" class="hidden" {{ $department->status === 'inactive' ? 'checked' : '' }}>
                        <span class="font-bold {{ $department->status === 'inactive' ? 'text-rose-600' : 'text-slate-500' }}">ระงับใช้งาน</span>
                    </label>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full primary-gradient text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transform transition-all active:scale-95">
                    อัปเดตข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Simple script to toggle visual state of radio buttons
    function initRadioToggles(name, activeClass, textClass) {
        document.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                    const label = r.closest('label');
                    const span = label.querySelector('span');
                    if (r.checked) {
                        label.classList.add(activeClass, 'bg-opacity-10');
                        span.classList.add(textClass);
                        label.classList.remove('border-slate-100');
                        span.classList.remove('text-slate-500');
                    } else {
                        label.classList.remove(activeClass, 'bg-opacity-10', 'bg-indigo-50', 'bg-rose-50');
                        span.classList.remove(textClass, 'text-indigo-600', 'text-rose-600');
                        label.classList.add('border-slate-100');
                        span.classList.add('text-slate-500');
                    }
                });
            });
        });
    }

    initRadioToggles('status', 'border-indigo-500', 'text-indigo-600'); // Note: status uses different colors in logic usually, but keep it simple
    initRadioToggles('type', 'border-indigo-500', 'text-indigo-600');
</script>
@endsection
