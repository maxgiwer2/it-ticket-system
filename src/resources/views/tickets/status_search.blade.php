@extends('layouts.app')

@section('title', 'ติดตามสถานะแจ้งซ่อม')

@section('content')
<div class="glass-card rounded-2xl p-8 max-w-2xl mx-auto">
    <div class="text-center mb-10">
        <div class="w-16 h-16 primary-gradient rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-100">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">ติดตามสถานะการแจ้งซ่อม</h1>
        <p class="text-slate-500 mt-2">กรอกหมายเลข Ticket เพื่อตรวจสอบความคืบหน้าของงาน</p>
    </div>

    <form action="{{ route('tickets.search') }}" method="GET" class="space-y-6">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2 text-center">หมายเลข Ticket</label>
            <input type="text" name="ticket_number" required
                class="w-full px-6 py-4 text-center text-2xl font-bold tracking-widest rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-300 placeholder:font-normal placeholder:tracking-normal"
                placeholder="TK-YYYYMMDD-XXXX">
        </div>

        <div class="pt-4">
            <button type="submit"
                class="w-full primary-gradient text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transform transition-all active:scale-95">
                ค้นหาสถานะ
            </button>
        </div>
    </form>

    <div class="mt-10 pt-8 border-t border-slate-100 text-center">
        <p class="text-slate-500 text-sm">หากลืมหมายเลข Ticket กรุณาติดต่อหน่วยงานไอที</p>
        <a href="{{ route('tickets.create') }}" class="inline-block mt-4 text-indigo-600 font-bold hover:underline">กลับไปหน้าแจ้งซ่อม</a>
    </div>
</div>
@endsection
