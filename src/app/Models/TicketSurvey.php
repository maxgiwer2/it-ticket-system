<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'rating_speed', 'rating_manner', 'rating_quality', 'comment'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * ค่าเฉลี่ยความพึงพอใจทั้ง 3 ด้าน (ทศนิยม 1 ตำแหน่ง)
     */
    public function getAverageAttribute()
    {
        return round(($this->rating_speed + $this->rating_manner + $this->rating_quality) / 3, 1);
    }
}
