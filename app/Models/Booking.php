<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'pharmacist_id',
        'name',
        'email',
        'phone',
        'prescription',
        'contact_method',
        'booking_type',
        'booking_date',
        'booking_time',
        'booking_status',
        'country',
        'payment_method',
        'payment_status',
        'payment_id',
        'payment_amount',
        'payment_currency',
        'payment_date',
        'payment_time',
        'google_event_id',
        'meet_link',
    ];

    /**
     * Get the user that made the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pharmacist assigned to the booking.
     */
    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }
}
