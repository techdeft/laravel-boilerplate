<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogins extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'country',
        'device',
        'logged_in_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
