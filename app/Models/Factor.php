<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factor extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    function getPaymentTypeTitleAttribute()
    {
        if ($this->attributes['payment_type'] === 'cash')
            return 'نقد';
        elseif ($this->attributes['payment_type'] === 'online')
            return 'آنلاین';
        else
            return 'واریز به حساب';
    }

    function transaction(){
        return Transaction::where('associate_id', $this->attributes['id'])->first();
    }
}
