@extends('layouts.app')

@section('title', 'เพิ่มหน่วยงานใหม่')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.departments.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center mb-4 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            กลับหน้าจัดการ
        </a>
        <h1 class="text-3xl font-bold text-slate-800">เพิ่มหน่วยงานใหม่</h1>
        <p class="text-slate-500">ระบุชื่อหน่วยงานที่ต้องการเพิ่มเข้าสู่ระบบ</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
        <form action="{{ route('admin.departments.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อหน่วยงาน</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                    placeholder="เช่น ภาควิชาอายุรศาสตร์">
                @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">กลุ่มหน่วยงาน</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all border-indigo-500 bg-indigo-50">
                        <input type="radio" name="type" value="faculty" class="hidden" checked>
                        <span class="font-bold text-indigo-600">คณะแพทยศาสตร์</span>
                    </label>
                    <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all border-slate-100">
                        <input type="radio" name="type" value="medical_center" class="hidden">
                        <span class="font-bold text-slate-500">ศูนย์การแพทย์ฯ</span>
                    </label>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full primary-gradient text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transform transition-all active:scale-95">
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle visual state for type radio buttons
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="type"]').forEach(r => {
                const label = r.closest('label');
                const span = label.querySelector('span');
                if (r.checked) {
                    label.classList.add('border-indigo-500', 'bg-indigo-50');
                    span.classList.add('text-indigo-600');
                    label.classList.remove('border-slate-100');
                    span.classList.remove('text-slate-500');
                } else {
                    label.classList.remove('border-indigo-500', 'bg-indigo-50');
                    span.classList.remove('text-indigo-600');
                    label.classList.add('border-slate-100');
                    span.classList.add('text-slate-500');
                }
            });
        });
    });
</script>
@endsection
