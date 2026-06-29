@extends('layouts.app')

@section('title', 'รายงานความพึงพอใจ')

@php
    if (!function_exists('satStars')) {
    function satStars($value) {
        $full = (int) round($value);
        $out = '';
        for ($i = 1; $i <= 5; $i++) {
            $color = $i <= $full ? 'text-amber-400' : 'text-slate-200';
            $out .= '<svg class="inline w-5 h-5 '.$color.'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.54 1.118l-3.366-2.446a1 1 0 00-1.175 0l-3.366 2.446c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.98 9.391c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path></svg>';
        }
        return $out;
    }
    }
@endphp

@section('content')
<div class="max-w-[1600px] mx-auto">
    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">รายงานความพึงพอใจของผู้ขอใช้งาน</h1>
        <p class="text-slate-500 font-medium mt-2">สรุปผลการประเมินจากผู้แจ้งหลังปิดงาน</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="glass-card p-6 rounded-[2rem] border-l-8 border-l-amber-400 shadow-xl">
            <p class="text-amber-600 font-black text-sm uppercase tracking-wider mb-2">คะแนนเฉลี่ยรวม</p>
            <div class="flex items-end gap-2">
                <h3 class="text-5xl font-black text-slate-900 tracking-tighter">{{ $avgOverall }}</h3>
                <span class="text-slate-400 font-bold text-sm mb-2">/ 5</span>
            </div>
            <div class="mt-2">{!! satStars($avgOverall) !!}</div>
        </div>

        <div class="glass-card p-6 rounded-[2rem] border-l-8 border-l-emerald-500 shadow-xl">
            <p class="text-emerald-600 font-black text-sm uppercase tracking-wider mb-2">จำนวนผลตอบรับ</p>
            <div class="flex items-end gap-2">
                <h3 class="text-5xl font-black text-slate-900 tracking-tighter">{{ $responseCount }}</h3>
                <span class="text-slate-400 font-bold text-sm mb-2">ใบ</span>
            </div>
        </div>

        <div class="glass-card p-6 rounded-[2rem] border-l-8 border-l-cyan-500 shadow-xl">
            <p class="text-cyan-600 font-black text-sm uppercase tracking-wider mb-2">งานเสร็จสิ้น</p>
            <div class="flex items-end gap-2">
                <h3 class="text-5xl font-black text-slate-900 tracking-tighter">{{ $completedCount }}</h3>
                <span class="text-slate-400 font-bold text-sm mb-2">ใบ</span>
            </div>
        </div>

        <div class="glass-card p-6 rounded-[2rem] border-l-8 border-l-violet-500 shadow-xl">
            <p class="text-violet-600 font-black text-sm uppercase tracking-wider mb-2">อัตราการตอบ</p>
            <div class="flex items-end gap-2">
                <h3 class="text-5xl font-black text-slate-900 tracking-tighter">{{ $responseRate }}</h3>
                <span class="text-slate-400 font-bold text-sm mb-2">%</span>
            </div>
        </div>
    </div>

    @if($responseCount === 0)
        <div class="glass-card rounded-[2rem] p-12 text-center text-slate-500 border border-white/60 shadow-xl">
            ยังไม่มีผลการประเมินความพึงพอใจในระบบ
        </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <!-- คะแนนเฉลี่ยรายด้าน -->
        <div class="glass-card rounded-[2rem] p-6 sm:p-8 border border-white/60 shadow-xl">
            <h2 class="text-lg font-black text-slate-800 mb-6">คะแนนเฉลี่ยรายด้าน</h2>
            @php
                $aspects = [
                    ['label' => 'ความรวดเร็วในการให้บริการ', 'value' => $avgSpeed],
                    ['label' => 'ความสุภาพของเจ้าหน้าที่', 'value' => $avgManner],
                    ['label' => 'คุณภาพและผลของงาน', 'value' => $avgQuality],
                ];
            @endphp
            <div class="space-y-6">
                @foreach($aspects as $aspect)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-bold text-slate-700">{{ $aspect['label'] }}</span>
                        <span class="text-sm font-black text-amber-500">{{ $aspect['value'] }} / 5</span>
                    </div>
                    <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-400 rounded-full" style="width: {{ $aspect['value'] / 5 * 100 }}%"></div>
                    </div>
                    <div class="mt-1">{!! satStars($aspect['value']) !!}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- การกระจายคะแนน -->
        <div class="glass-card rounded-[2rem] p-6 sm:p-8 border border-white/60 shadow-xl">
            <h2 class="text-lg font-black text-slate-800 mb-6">การกระจายคะแนน (ตามคะแนนเฉลี่ยของแต่ละใบ)</h2>
            <div class="space-y-3">
                @for($star = 5; $star >= 1; $star--)
                    @php $pct = $responseCount > 0 ? round($distribution[$star] / $responseCount * 100) : 0; @endphp
                    <div class="flex items-center gap-3">
                        <span class="w-12 text-sm font-bold text-slate-600 whitespace-nowrap">{{ $star }} ดาว</span>
                        <div class="flex-grow h-4 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="w-20 text-right text-sm font-bold text-slate-700">{{ $distribution[$star] }} ใบ</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- คะแนนแยกตามเจ้าหน้าที่ -->
    <div class="mb-10">
        <h2 class="text-lg font-black text-slate-800 mb-4">คะแนนเฉลี่ยแยกตามเจ้าหน้าที่ผู้รับผิดชอบ</h2>
        <div class="glass-card rounded-2xl overflow-hidden border border-white/60 shadow-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cyan-100/60">
                        <th class="px-4 py-3 border border-slate-200 text-sm font-black text-slate-700">เจ้าหน้าที่</th>
                        <th class="px-4 py-3 border border-slate-200 text-sm font-black text-slate-700 text-center w-40">จำนวนผลตอบรับ</th>
                        <th class="px-4 py-3 border border-slate-200 text-sm font-black text-slate-700 text-center w-56">คะแนนเฉลี่ย</th>
                    </tr>
                </thead>
                <tbody class="bg-white/80">
                    @foreach($byTechnician as $name => $data)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-4 py-3 border border-slate-200 text-sm font-medium text-slate-700">{{ $name }}</td>
                        <td class="px-4 py-3 border border-slate-200 text-sm font-bold text-center text-slate-600">{{ $data['count'] }}</td>
                        <td class="px-4 py-3 border border-slate-200 text-sm text-center">
                            <span class="font-black text-amber-500 mr-2">{{ $data['average'] }}</span>
                            {!! satStars($data['average']) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ความคิดเห็นล่าสุด -->
    <div class="mb-12">
        <h2 class="text-lg font-black text-slate-800 mb-4">ความคิดเห็นล่าสุด</h2>
        @if($recentComments->isEmpty())
            <div class="glass-card rounded-2xl p-8 text-center text-slate-500 border border-white/60 shadow-xl">
                ยังไม่มีความคิดเห็นเพิ่มเติม
            </div>
        @else
            <div class="space-y-4">
                @foreach($recentComments as $survey)
                <div class="glass-card rounded-2xl p-5 border border-white/60 shadow-md">
                    <div class="flex items-center justify-between flex-wrap gap-2 mb-2">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $survey->ticket->ticket_number ?? '-' }}</span>
                            <span class="text-xs text-slate-400">{{ $survey->ticket->department->name ?? '' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            {!! satStars($survey->average) !!}
                            <span class="text-xs font-bold text-amber-500">{{ $survey->average }}/5</span>
                        </div>
                    </div>
                    <p class="text-slate-700 leading-relaxed italic">{{ $survey->comment }}</p>
                    <p class="text-[11px] text-slate-400 mt-2">{{ $survey->created_at->format('d/m/Y H:i') }} น.</p>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif
</div>
@endsection
