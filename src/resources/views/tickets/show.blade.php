@extends('layouts.app')

@section('title', 'หมายเลข Ticket ' . $ticket->ticket_number)

@section('content')
<div class="glass-card rounded-2xl overflow-hidden">
    <div class="primary-gradient p-6 sm:p-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-wider">Ticket Details</span>
                <div class="flex items-center mt-2 group">
                    <h1 class="text-3xl font-bold mr-3" id="ticketNumber">{{ $ticket->ticket_number }}</h1>
                    <button onclick="copyTicket()" class="p-2 bg-white/20 hover:bg-white/40 rounded-lg transition-all" title="คัดลอกหมายเลข">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    </button>
                </div>
                @if(session('success'))
                    <p class="text-emerald-100 text-xs mt-3 flex items-center bg-emerald-700/30 px-3 py-2 rounded-lg border border-emerald-400/30">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        โปรดแคปหน้าจอหรือจดหมายเลขนี้ไว้เพื่อติดตามสถานะในภายหลัง
                    </p>
                @endif
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <p class="text-white/80 text-sm">สถานะปัจจุบัน</p>
                @php
                    $statusColors = [
                        'pending' => 'bg-amber-400',
                        'processing' => 'bg-emerald-400',
                        'completed' => 'bg-emerald-500',
                        'more_info' => 'bg-rose-400'
                    ];
                    $statusText = [
                        'pending' => 'รอดำเนินการ',
                        'processing' => 'กำลังดำเนินการ',
                        'completed' => 'เสร็จสิ้น',
                        'more_info' => 'ขอข้อมูลเพิ่ม'
                    ];
                @endphp
                <span class="inline-block px-4 py-1 {{ $statusColors[$ticket->status] }} text-white font-bold rounded-full mt-1">
                    {{ $statusText[$ticket->status] }}
                </span>
            </div>
        </div>
    </div>

    <div class="p-6 sm:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div class="space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">ข้อมูลผู้แจ้ง</h3>
                    <p class="text-lg font-medium text-slate-900 mt-1">{{ $ticket->requester_name }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">หน่วยงาน</h3>
                    <p class="text-slate-700 mt-1">
                        {{ $ticket->department->name }} 
                        <span class="ml-2 text-xs px-2 py-0.5 bg-slate-100 text-slate-500 rounded font-bold">
                            {{ $ticket->department->type === 'faculty' ? 'คณะแพทยศาสตร์' : 'ศูนย์การแพทย์ฯ' }}
                        </span>
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">เบอร์ติดต่อ</h3>
                    <p class="text-slate-700 mt-1">{{ $ticket->phone }}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">ประเภทงาน</h3>
                    <p class="text-lg font-medium text-slate-900 mt-1">{{ $ticket->jobType->name }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">วันที่แจ้ง</h3>
                    <p class="text-slate-700 mt-1">{{ $ticket->created_at->format('d/m/Y H:i') }} น.</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">ผู้รับผิดชอบ</h3>
                    <p class="text-emerald-600 font-bold mt-1">
                        {{ $ticket->technician ? $ticket->technician->name : 'กำลังรอมอบหมาย' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Progress Timeline -->
        <div class="mb-10 bg-slate-50 p-6 sm:p-8 rounded-3xl border border-slate-100">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8 text-center">ความคืบหน้าของ Ticket</h3>
            <div class="relative">
                <!-- Line -->
                <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-200 -translate-y-1/2 hidden lg:block"></div>
                <div class="absolute left-[23px] top-0 h-full w-1 bg-slate-200 block lg:hidden"></div>
                <div class="flex flex-col lg:flex-row justify-between relative space-y-8 lg:space-y-0">
                    @php
                        $steps = [
                            ['key' => 'pending', 'label' => 'ได้รับเรื่องแล้ว', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['key' => 'processing', 'label' => 'กำลังดำเนินการ', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                            ['key' => 'completed', 'label' => 'เสร็จสิ้น', 'icon' => 'M5 13l4 4L19 7'],
                        ];
                        $foundCurrent = false;
                        $currentIndex = 0;
                        foreach($steps as $idx => $step) {
                            if($step['key'] === $ticket->status) {
                                $currentIndex = $idx;
                                break;
                            }
                        }
                    @endphp

                    @foreach($steps as $index => $step)
                        @php
                            $isActive = $index <= $currentIndex;
                            $isCurrent = $index === $currentIndex;
                        @endphp
                        <div class="flex flex-row lg:flex-col items-center flex-1 relative">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center z-10 transition-all duration-500 {{ $isActive ? 'cta-gradient text-white shadow-lg shadow-emerald-200' : 'bg-white text-slate-300 border-2 border-slate-100' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"></path></svg>
                            </div>
                            <div class="ml-4 lg:ml-0 lg:mt-3 text-left lg:text-center">
                                <p class="text-sm font-bold {{ $isActive ? 'text-emerald-600' : 'text-slate-400' }}">{{ $step['label'] }}</p>
                                @if($isCurrent)
                                    <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-tighter animate-pulse">Current Stage</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-8">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">รายละเอียดปัญหา</h3>
            <div class="bg-slate-50 p-6 rounded-2xl text-slate-700 leading-relaxed">
                {{ $ticket->details }}
            </div>
        </div>

        @if($ticket->admin_note)
        <div class="mt-8 p-6 bg-cyan-50/50 rounded-2xl border border-cyan-100">
            <h3 class="text-xs font-bold text-cyan-600 uppercase tracking-widest mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                บันทึกจากเจ้าหน้าที่
            </h3>
            <div class="text-cyan-900 leading-relaxed italic">
                {{ $ticket->admin_note }}
            </div>
        </div>
        @endif

        @if($ticket->attachment_path)
        <div class="mt-8">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ไฟล์แนบ</h3>
            <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-emerald-50 text-emerald-600 rounded-xl transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                เปิดไฟล์แนบ
            </a>
        </div>
        @endif

        {{-- แบบประเมินความพึงพอใจ (แสดงเมื่อใบงานเสร็จสิ้นแล้วเท่านั้น) --}}
        @if($ticket->status === 'completed')
            @php
                $criteria = [
                    'rating_speed' => 'ความรวดเร็วในการให้บริการ',
                    'rating_manner' => 'ความสุภาพ/มารยาทของเจ้าหน้าที่',
                    'rating_quality' => 'คุณภาพและผลของการแก้ไขงาน',
                ];
            @endphp

            @if($ticket->survey)
                {{-- ประเมินแล้ว: แสดงผลแบบอ่านอย่างเดียว --}}
                <div class="mt-8 p-6 sm:p-8 bg-emerald-50/40 rounded-3xl border border-emerald-100">
                    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
                        <h3 class="text-base font-black text-emerald-700 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            ท่านได้ประเมินความพึงพอใจแล้ว
                        </h3>
                        <span class="text-sm font-bold text-emerald-600">คะแนนเฉลี่ย {{ $ticket->survey->average }} / 5</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($criteria as $field => $label)
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $ticket->survey->$field ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.54 1.118l-3.366-2.446a1 1 0 00-1.175 0l-3.366 2.446c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.98 9.391c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path></svg>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($ticket->survey->comment)
                        <div class="mt-6 pt-5 border-t border-emerald-100">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">ความคิดเห็นเพิ่มเติม</h4>
                            <p class="text-slate-700 leading-relaxed italic">{{ $ticket->survey->comment }}</p>
                        </div>
                    @endif
                </div>
            @else
                {{-- ยังไม่ประเมิน: แสดงฟอร์ม --}}
                <div class="mt-8 p-6 sm:p-8 glass-card rounded-3xl border border-emerald-100">
                    <h3 class="text-lg font-black text-slate-800 mb-1 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        ประเมินความพึงพอใจในการให้บริการ
                    </h3>
                    <p class="text-sm text-slate-500 mb-6">งานของท่านเสร็จสิ้นแล้ว โปรดสละเวลาให้คะแนนเพื่อช่วยเราพัฒนาการบริการ</p>

                    <form action="{{ route('surveys.store', $ticket->ticket_number) }}" method="POST" class="space-y-6">
                        @csrf

                        @foreach($criteria as $field => $label)
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">{{ $label }}</label>
                                <div class="star-rating inline-flex gap-1" data-target="{{ $field }}">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" data-value="{{ $i }}"
                                            class="star text-slate-300 hover:scale-110 transition-transform focus:outline-none"
                                            title="{{ $i }} ดาว">
                                            <svg class="w-9 h-9" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.54 1.118l-3.366-2.446a1 1 0 00-1.175 0l-3.366 2.446c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.98 9.391c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path></svg>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="{{ $field }}" id="input_{{ $field }}" value="{{ old($field) }}">
                                @error($field)
                                    <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">ความคิดเห็นเพิ่มเติม (ถ้ามี)</label>
                            <textarea name="comment" rows="3"
                                class="w-full px-4 py-3 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-300"
                                placeholder="ข้อเสนอแนะหรือคำชมเพิ่มเติม...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full cta-gradient text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-100 hover:-translate-y-0.5 transition-all interactive-scale">
                            ส่งแบบประเมิน
                        </button>
                    </form>
                </div>
            @endif
        @endif
    </div>
    
    <div class="bg-slate-50/50 p-8 border-t border-slate-100 flex justify-center">
        <a href="{{ route('tickets.create') }}" class="text-cyan-600 font-bold hover:underline interactive-scale">ส่งใบแจ้งซ่อมใหม่</a>
        <span class="mx-4 text-slate-300">|</span>
        <a href="{{ route('tickets.search') }}" class="text-slate-500 font-bold hover:underline interactive-scale">ติดตามสถานะอื่น</a>
    </div>
</div>
@push('scripts')
<script>
    function copyTicket() {
        const ticketNum = document.getElementById('ticketNumber').innerText;
        navigator.clipboard.writeText(ticketNum).then(() => {
            alert('คัดลอกหมายเลข Ticket: ' + ticketNum + ' แล้ว');
        });
    }

    // Interactive star rating widgets
    document.querySelectorAll('.star-rating').forEach(function (group) {
        const target = group.dataset.target;
        const input = document.getElementById('input_' + target);
        const stars = group.querySelectorAll('.star');

        function paint(value) {
            stars.forEach(function (star) {
                const v = parseInt(star.dataset.value, 10);
                star.classList.toggle('text-amber-400', v <= value);
                star.classList.toggle('text-slate-300', v > value);
            });
        }

        stars.forEach(function (star) {
            star.addEventListener('click', function () {
                const value = parseInt(star.dataset.value, 10);
                input.value = value;
                paint(value);
            });
            star.addEventListener('mouseenter', function () {
                paint(parseInt(star.dataset.value, 10));
            });
        });

        group.addEventListener('mouseleave', function () {
            paint(parseInt(input.value, 10) || 0);
        });

        // restore previous value (e.g. validation error)
        paint(parseInt(input.value, 10) || 0);
    });
</script>
@endpush
@endsection
