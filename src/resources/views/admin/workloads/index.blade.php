@extends('layouts.app')

@section('title', 'รายการภาระงาน')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-5xl font-black text-slate-900 italic tracking-tighter">
                {{ auth()->user()->role === 'superadmin' ? 'All Workloads' : 'My Workload' }}
            </h1>
            <p class="text-slate-500 mt-2 font-medium">บันทึกภาระงานและการปฏิบัติงานทั้งหมด{{ auth()->user()->role === 'superadmin' ? 'ในระบบ' : 'ของคุณ' }}</p>
        </div>
        <a href="{{ route('admin.workloads.create') }}" class="cta-gradient text-white px-10 py-5 rounded-[2rem] font-black shadow-xl shadow-emerald-200 hover:shadow-emerald-400 hover:-translate-y-1 transition-all flex items-center group">
            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center mr-3 group-hover:rotate-90 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            บันทึกงานใหม่
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 p-6 bg-emerald-500 text-white rounded-3xl font-bold shadow-lg shadow-emerald-100 flex items-center animate-fade-in-up">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse($workloads as $workload)
        <div class="glass-card p-8 rounded-[2.5rem] border border-white/60 shadow-xl hover:shadow-2xl transition-all duration-500 group relative overflow-hidden stagger-{{ $loop->index % 5 }}">
            <!-- Type Indicator -->
            <div class="absolute right-0 top-0 w-2 h-full {{ 
                $workload->type === 'อบรม' ? 'bg-blue-500' : 
                ($workload->type === 'ช่วยงาน/ event' ? 'bg-amber-500' : 
                ($workload->type === 'ประชุม' ? 'bg-emerald-500' : 'bg-indigo-500')) 
            }} opacity-20"></div>

            <div class="flex flex-col md:flex-row gap-8 items-start relative z-10">
                <!-- Date Badge -->
                <div class="flex flex-col items-center justify-center min-w-[100px] h-[100px] bg-slate-50 rounded-3xl border border-slate-100 group-hover:bg-white group-hover:shadow-lg transition-all duration-300">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $workload->work_date->format('M') }}</span>
                    <span class="text-3xl font-black text-slate-900">{{ $workload->work_date->format('d') }}</span>
                    <span class="text-[10px] font-bold text-slate-500">{{ $workload->work_date->format('Y') }}</span>
                </div>

                <div class="flex-grow space-y-4">
                    <div class="flex items-center gap-3">
                        @if(auth()->user()->role === 'superadmin')
                        <span class="px-3 py-1 bg-slate-900 text-white text-[9px] font-black rounded-lg uppercase tracking-tighter">
                            Admin: {{ $workload->user->name }}
                        </span>
                        @endif
                        <span class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest {{ 
                            $workload->type === 'อบรม' ? 'bg-blue-100 text-blue-700' : 
                            ($workload->type === 'ช่วยงาน/ event' ? 'bg-amber-100 text-amber-700' : 
                            ($workload->type === 'ประชุม' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700')) 
                        }}">
                            {{ $workload->type }}
                        </span>
                        @if($workload->attachment_path)
                        <a href="{{ asset('storage/' . $workload->attachment_path) }}" target="_blank" class="flex items-center text-[10px] font-bold text-slate-400 hover:text-emerald-600 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            View Attachment
                        </a>
                        @endif
                    </div>
                    
                    <div class="bg-white p-8 rounded-3xl border-2 border-slate-100 group-hover:border-indigo-100 transition-colors duration-300 shadow-sm">
                        <p class="text-2xl font-black text-slate-900 leading-tight whitespace-pre-line tracking-tight">{{ $workload->details }}</p>
                    </div>
                </div>

                <div class="flex md:flex-col gap-2">
                    <form action="{{ route('admin.workloads.destroy', $workload) }}" method="POST" onsubmit="return confirm('ยืนยันการลบรายการนี้?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-12 h-12 rounded-2xl bg-white border border-slate-100 text-slate-300 hover:bg-rose-50 hover:text-rose-500 hover:border-rose-100 transition-all flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="glass-card p-20 rounded-[3rem] border border-dashed border-slate-200 text-center">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900">ยังไม่มีการบันทึกภาระงาน</h3>
            <p class="text-slate-400 mt-2">เริ่มบันทึกภาระงานแรกของคุณเพื่อสรุปผลการปฏิบัติงาน</p>
            <a href="{{ route('admin.workloads.create') }}" class="inline-block mt-8 text-emerald-600 font-black uppercase tracking-widest text-xs hover:underline">
                บันทึกงานใหม่ทันที
            </a>
        </div>
        @endforelse

        <div class="mt-12">
            {{ $workloads->links() }}
        </div>
    </div>
</div>
@endsection
