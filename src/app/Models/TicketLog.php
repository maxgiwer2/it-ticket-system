<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    protected $fillable = ['ticket_id', 'staff_id', 'action', 'note'];
    use HasFactory;
}
