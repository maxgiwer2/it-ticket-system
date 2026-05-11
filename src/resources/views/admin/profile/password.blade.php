@extends('layouts.app')

@section('title', 'เปลี่ยนรหัสผ่าน')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">ตั้งค่าความปลอดภัย</h1>
        <p class="text-slate-500 mt-2 font-medium">เปลี่ยนรหัสผ่านใหม่เพื่อความปลอดภัยของบัญชีผู้ใช้งาน</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center text-emerald-600 animate-fade-in-down">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="glass-card rounded-3xl p-8 border border-white/40 shadow-xl relative overflow-hidden">
        <!-- Decoration Gradient -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-100/30 blur-3xl rounded-full"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-100/30 blur-3xl rounded-full"></div>

        <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-6 relative z-10">
            @csrf

            <!-- Current Password -->
            <div>
                <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-wider">รหัสผ่านปัจจุบัน</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <input type="password" name="current_password" required
                           class="w-full pl-11 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-300 font-bold"
                           placeholder="••••••••">
                </div>
                @error('current_password')
                    <p class="mt-2 text-xs text-rose-500 font-bold flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="h-px bg-slate-100 my-2"></div>

            <!-- New Password -->
            <div>
                <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-wider">รหัสผ่านใหม่</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </span>
                    <input type="password" name="password" required
                           class="w-full pl-11 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-300 font-bold"
                           placeholder="ระบุอย่างน้อย 8 ตัวอักษร">
                </div>
                @error('password')
                    <p class="mt-2 text-xs text-rose-500 font-bold flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-wider">ยืนยันรหัสผ่านใหม่</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </span>
                    <input type="password" name="password_confirmation" required
                           class="w-full pl-11 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-300 font-bold"
                           placeholder="พิมพ์รหัสผ่านใหม่อีกครั้ง">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" 
                        class="w-full primary-gradient text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-200 hover:shadow-emerald-300 transform transition-all active:scale-95 flex items-center justify-center group">
                    <span>อัปเดตรหัสผ่านใหม่</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 p-6 bg-slate-900 rounded-3xl text-white relative overflow-hidden">
        <div class="relative z-10 flex items-start">
            <div class="p-3 bg-white/10 rounded-2xl mr-4">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-black text-lg mb-1">คำแนะนำความปลอดภัย</h4>
                <p class="text-sm text-slate-400 leading-relaxed font-medium">
                    ควรใช้รหัสผ่านที่มีทั้งตัวอักษรเล็ก-ใหญ่ ตัวเลข และสัญลักษณ์พิเศษผสมกัน เพื่อความปลอดภัยสูงสุดของระบบ และไม่ควรใช้รหัสผ่านเดียวกับที่ใช้ในระบบอื่นๆ
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
