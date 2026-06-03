<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeployLog extends Model
{
    use HasFactory;

    protected $fillable = ['deploy_number','system_name','version','environment','deployed_at','deployed_by','release_note','rollback_plan'];
}
