<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $hidden = ['deleted_at'];

    protected $dates = ['created_at', 'updated_at'];

    protected $perPage = 20;

    protected $guarded = ['id'];
}
