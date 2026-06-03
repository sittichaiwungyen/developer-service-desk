<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['uuid','ticket_number','department_id','requester_id','requester_name','requester_email','requester_position','contact_phone','location','subject','description','resolution','root_cause','category','subcategory','issue_type','affected_system','asset_tag','ip_address','priority','impact_level','status','assigned_to','sla_due_at','sla_breached','follow_up_required'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function histories()
    {
        return $this->hasMany(TicketHistory::class);
    }
}
