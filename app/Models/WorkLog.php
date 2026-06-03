<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','date','started_at','ended_at','hours','description','work_type','ticket_id','project_id','notes'];
}
