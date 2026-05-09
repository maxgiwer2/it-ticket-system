@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ Admin')

@section('content')
<div class="max-w-md mx-auto py-10">
    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl shadow-emerald-100/50">
        <div class="primary-gradient p-8 text-center text-white">
            <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-md">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h1 class="text-2xl font-bold">Admin Login</h1>
            <p class="text-emerald-100 text-sm mt-2 opacity-80">สำหรับเจ้าหน้าที่ไอทีเท่านั้น</p>
        </div>

        <div class="p-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-xl text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">อีเมล</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                            placeholder="admin@example.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">รหัสผ่าน</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input type="password" name="password" required
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                    <label for="remember" class="ml-2 text-sm text-slate-600">จดจำการใช้งาน</label>
                </div>

                <button type="submit" 
                    class="w-full primary-gradient text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 hover:shadow-emerald-300 transform transition-all active:scale-[0.98]">
                    เข้าสู่ระบบ
                </button>
            </form>
        </div>
    </div>
    <div class="mt-8 text-center">
        <a href="{{ route('tickets.create') }}" class="text-slate-500 hover:text-emerald-600 text-sm font-medium transition-colors flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            กลับสู่หน้าหลัก
        </a>
    </div>
</div>
@endsection
