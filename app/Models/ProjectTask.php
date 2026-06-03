<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['project_id','parent_id','title','description','due_date','priority','assignee_id','status','position'];
}
