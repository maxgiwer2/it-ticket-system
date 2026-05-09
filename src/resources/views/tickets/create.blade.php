@extends('layouts.app')

@section('title', 'แบบฟอร์มแจ้งซ่อม')

@section('content')
<div class="glass-card rounded-2xl p-6 sm:p-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">แจ้งซ่อมและขอใช้บริการ IT</h1>
        <p class="text-slate-500 mt-1">กรุณากรอกข้อมูลให้ครบถ้วนเพื่อให้เจ้าหน้าที่ดำเนินการตรวจสอบ</p>
    </div>

    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อผู้แจ้ง</label>
                <input type="text" name="requester_name" value="{{ old('requester_name') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus-ring focus:border-cyan-500 outline-none transition-all"
                    placeholder="ระบุชื่อ-นามสกุล">
                @error('requester_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">เบอร์ภายใน</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus-ring focus:border-cyan-500 outline-none transition-all"
                    placeholder="ตัวอย่าง 60401">
                @error('phone') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">หน่วยงาน/แผนก</label>
                <select name="department_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all appearance-none bg-white">
                    <option value="">เลือกหน่วยงาน</option>
                    @foreach($departments as $type => $group)
                        <optgroup label="{{ $type === 'faculty' ? 'คณะแพทยศาสตร์' : 'ศูนย์การแพทย์ฯ' }}">
                            @foreach($group as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('department_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">ประเภทงาน</label>
                <div class="space-y-3">
                    <div class="flex gap-2 p-1 bg-slate-100 rounded-xl overflow-x-auto no-scrollbar">
                        @foreach($jobTypes as $category => $types)
                        <button type="button" class="category-btn flex-1 py-2 px-3 rounded-lg text-xs font-bold transition-all {{ $loop->first ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:bg-white/50' }}"
                            data-category="{{ $category }}">
                            {{ $category }}
                        </button>
                        @endforeach
                    </div>
                    <select name="job_type_id" id="job_type_select" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all appearance-none bg-white">
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
                @error('job_type_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">รายละเอียดปัญหา/ความต้องการ</label>
            <textarea name="details" rows="4" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus-ring focus:border-cyan-500 outline-none transition-all"
                placeholder="อธิบายอาการเสียหรือบริการที่ต้องการอย่างละเอียด">{{ old('details') }}</textarea>
            @error('details') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">แนบไฟล์ (ถ้ามี)</label>
            <div class="relative">
                <input type="file" name="attachment" id="attachment" class="hidden">
                <label for="attachment" class="flex items-center justify-center w-full px-4 py-4 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 hover:border-emerald-300 transition-all">
                    <svg class="w-6 h-6 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span class="text-slate-500" id="file-label">เลือกไฟล์รูปภาพหรือเอกสาร (ไม่เกิน 5MB)</span>
                </label>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit"
                class="w-full cta-gradient text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 hover:shadow-emerald-400 transform transition-all active:scale-95 interactive-scale">
                ส่งใบแจ้งซ่อม
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('attachment').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'เลือกไฟล์รูปภาพหรือเอกสาร (ไม่เกิน 5MB)';
        document.getElementById('file-label').textContent = fileName;
    });

    // Initialize Tom Select
    new TomSelect("select[name='department_id']", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        placeholder: "ค้นหาและเลือกหน่วยงาน..."
    });

    // Job Type Filtering
    const jobSelect = document.getElementById('job_type_select');
    const jobOptions = Array.from(jobSelect.options);
    const categoryBtns = document.querySelectorAll('.category-btn');

    function filterJobs(category) {
        jobSelect.innerHTML = '<option value="">เลือกหัวข้อในหมวด ' + category + '...</option>';
        jobOptions.forEach(opt => {
            if (opt.dataset.category === category) {
                jobSelect.add(opt.cloneNode(true));
            }
        });
        
        categoryBtns.forEach(btn => {
            if (btn.dataset.category === category) {
                btn.classList.add('bg-white', 'text-cyan-600', 'shadow-md');
                btn.classList.remove('text-slate-500', 'hover:bg-white/50');
            } else {
                btn.classList.remove('bg-white', 'text-cyan-600', 'shadow-md');
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

</script>
@endsection
