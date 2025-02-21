<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingTransaction extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'name',
        'phone_number',
        'booking_trx',
        'started_at',
        'ended_at',
        'is_paid',
        'duration',
        'total_amount',
        'office_space_id'
    ];

    public function generateUniqueTrxId()
    {
        $prefix = 'BO';
        do {
            $randomString = $prefix . mt_rand(100000, 999999);
        } while (self::where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }

    public function officeSpace()
    {
        return $this->belongsTo(OfficeSpace::class);
    }
}
