@extends('layouts.app')

@section('title', 'จัดการผู้ดูแลระบบ')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 italic">User Management</h1>
            <p class="text-slate-500">จัดการรายชื่อ Admin และ Superadmin ในระบบ</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="primary-gradient text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition-all flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            เพิ่มผู้ใช้งานใหม่
        </a>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-white/40">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">อีเมล</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">ระดับสิทธิ์</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full primary-gradient flex items-center justify-center text-white font-bold mr-3">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-slate-700">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'superadmin')
                                <span class="px-3 py-1 bg-rose-100 text-rose-600 rounded-full text-xs font-bold border border-rose-200">Superadmin</span>
                            @else
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-600 rounded-full text-xs font-bold border border-indigo-200">Admin</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-3 py-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-600 hover:text-white transition-all text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบผู้ใช้งานนี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all text-xs font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
