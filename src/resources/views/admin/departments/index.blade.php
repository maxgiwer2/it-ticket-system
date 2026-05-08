@extends('layouts.app')

@section('title', 'จัดการหน่วยงาน')

@section('content')
<div class="w-full">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">จัดการหน่วยงาน</h1>
            <p class="text-slate-500">เพิ่ม แก้ไข หรือลบหน่วยงานในระบบ</p>
        </div>
        <a href="{{ route('admin.departments.create') }}" class="primary-gradient text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transition-all flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            เพิ่มหน่วยงานใหม่
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">ชื่อหน่วยงาน</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">กลุ่มหน่วยงาน</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">สถานะ</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">วันที่สร้าง</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($departments as $dept)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-medium text-slate-700">{{ $dept->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="text-slate-600">
                                {{ $dept->type === 'faculty' ? 'คณะแพทยศาสตร์' : 'ศูนย์การแพทย์ฯ' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $dept->status === 'active' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $dept->status === 'active' ? 'เปิดใช้งาน' : 'ระงับการใช้งาน' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ $dept->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.departments.edit', $department = $dept->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors text-sm font-medium">
                                แก้ไข
                            </a>
                            <form action="{{ route('admin.departments.destroy', $dept->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors text-sm font-medium" onclick="return confirm('ยืนยันการลบหน่วยงานนี้?')">
                                    ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            ไม่พบข้อมูลหน่วยงาน
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
