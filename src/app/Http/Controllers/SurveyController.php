<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketSurvey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * รับผลประเมินความพึงพอใจจากผู้แจ้ง (public, ไม่ต้องล็อกอิน)
     */
    public function store(Request $request, $ticket_number)
    {
        $ticket = Ticket::where('ticket_number', $ticket_number)->firstOrFail();

        // ประเมินได้เฉพาะใบงานที่เสร็จสิ้นแล้ว
        if ($ticket->status !== 'completed') {
            return back()->with('error', 'ยังไม่สามารถประเมินได้ ใบงานนี้ยังไม่เสร็จสิ้น');
        }

        // กันการประเมินซ้ำ
        if ($ticket->survey()->exists()) {
            return back()->with('error', 'ใบงานนี้ได้รับการประเมินไปแล้ว ขอบคุณครับ');
        }

        $validated = $request->validate([
            'rating_speed' => 'required|integer|between:1,5',
            'rating_manner' => 'required|integer|between:1,5',
            'rating_quality' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating_speed.required' => 'กรุณาให้คะแนนด้านความรวดเร็วในการให้บริการ',
            'rating_speed.between' => 'คะแนนความรวดเร็วต้องอยู่ระหว่าง 1 ถึง 5',
            'rating_manner.required' => 'กรุณาให้คะแนนด้านความสุภาพของเจ้าหน้าที่',
            'rating_manner.between' => 'คะแนนความสุภาพต้องอยู่ระหว่าง 1 ถึง 5',
            'rating_quality.required' => 'กรุณาให้คะแนนด้านคุณภาพของงาน',
            'rating_quality.between' => 'คะแนนคุณภาพต้องอยู่ระหว่าง 1 ถึง 5',
            'comment.max' => 'ความคิดเห็นต้องไม่เกิน 1000 ตัวอักษร',
        ]);

        $ticket->survey()->create($validated);

        return back()->with('success', 'ขอบคุณสำหรับการประเมินความพึงพอใจ ความคิดเห็นของท่านมีค่าต่อการพัฒนาบริการของเรา');
    }
}
