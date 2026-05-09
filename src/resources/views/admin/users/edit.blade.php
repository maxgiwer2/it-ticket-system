@extends('layouts.app')

@section('title', 'แก้ไขข้อมูลผู้ดูแลระบบ')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-slate-500 hover:text-emerald-600 font-bold transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            กลับหน้ารวม
        </a>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
        <div class="primary-gradient p-8 text-white">
            <h1 class="text-2xl font-bold">Edit User Details</h1>
            <p class="text-emerald-100 text-sm mt-1">แก้ไขข้อมูลบัญชีผู้ใช้งานสำหรับเจ้าหน้าที่ไอที</p>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">ชื่อ-นามสกุล</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all @error('name') border-rose-300 @enderror"
                    placeholder="สมชาย ใจดี">
                @error('name') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">อีเมล (ใช้สำหรับเข้าสู่ระบบ)</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all @error('email') border-rose-300 @enderror"
                    placeholder="user@example.com">
                @error('email') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">ระดับสิทธิ์</label>
                <select name="role" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all appearance-none bg-white">
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin (เจ้าหน้าที่ทั่วไป)</option>
                    <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Superadmin (ผู้ดูแลระบบสูงสุด)</option>
                </select>
            </div>

            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 mb-2">
                <p class="text-xs text-amber-700 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">รหัสผ่านใหม่ (ถ้ามี)</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all @error('password') border-rose-300 @enderror"
                        placeholder="••••••••">
                    @error('password') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="w-full primary-gradient text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-100 hover:shadow-emerald-200 transform transition-all active:scale-95">
                    บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
