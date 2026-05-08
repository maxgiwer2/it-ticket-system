<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number', 'requester_name', 'department_id', 
        'phone', 'job_type_id', 'details', 'status', 
        'attachment_path', 'assigned_to'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }
}
