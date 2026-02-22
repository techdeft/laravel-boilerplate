<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogins extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'login_time',
        'logout_time',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
