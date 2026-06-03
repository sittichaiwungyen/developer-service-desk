<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['cr_number','requester_id','description','reason','impact','risk_assessment','approval_status'];
}
