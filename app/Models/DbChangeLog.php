<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbChangeLog extends Model
{
    use HasFactory;

    protected $fillable = ['object_type','object_name','database_name','change_description','changed_by','deployed_at'];
}
