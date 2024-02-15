<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function bankRelation(){
        return $this->hasOne(BankAccount::class,'id','bank_account_id');
    }

    function discount()
    {
        if ($this->attributes['discount_code'] != null) {
            return Off::where('code', $this->attributes['discount_code'])->first();
        }
        return null;

    }

    function getBankAttribute()
    {
        if ($this->attributes['bank_account_id'] !== null || $this->attributes['bank_account_id'] !== 0)
            return BankAccount::find($this->attributes['bank_account_id']);
        return null;
    }

    function getInvoiceTypeTitleAttribute()
    {
        if ($this->attributes['invoice_title'] === 'pre-invoice')
            return "پیش فاکتور";
        return "رسید";
    }

    function getDifferentDaysAttribute()
    {
        if ($this->attributes['confirmed_at'] != null) {
            $datetime1 = Carbon::create($this->attributes['confirmed_at'])->addDays(11);
            $datetime2 = Carbon::now();
            $interval = $datetime1->diff($datetime2);
            $days = $interval->invert > 0 ? $interval->format('%a') : '-' . $interval->format('%a');

            return $days;
        } else {
            return '---';
        }
    }

    function getJalaliDateAttribute()
    {
        $date = explode(' ', $this->attributes['created_at']);
        $date = explode('-', $date[0]);
        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
        return $date;
    }

    function getJalaliDateConfirmedAttribute()
    {
        if ($this->attributes['confirmed_at'] != null) {
            $date = explode(' ', $this->attributes['confirmed_at']);
            $date = explode('-', $date[0]);
            $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
            return $date;
        } else {
            return '---';
        }
    }

    function getUpdatedJalaliAttribute()
    {
        $date = explode(' ', $this->attributes['updated_at']);
        $date = explode('-', $date[0]);
        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
        return $date;
    }

    function getPaymentMethodTitleAttribute()
    {
        if ($this->attributes['payment_method'] === 'online')
            return "آنلاین";
        elseif ($this->attributes['payment_method'] === 'bank')
            return "حساب بانکی";
        else
            return "نقدی";
    }

    function getInvoiceAttribute()
    {
        if ($this->attributes['invoice_type'] === 'resume')
            return "رزومه و انگیزه نامه";
        elseif ($this->attributes['invoice_type'] === 'final')
            return "تسویه نهایی";
        elseif ($this->attributes['invoice_type'] === 'tel-support')
            return "مشاوره تلفنی";
        else
            return "پیش پرداخت";
    }

    function getJalaliCreatedAttribute()
    {
        $date = explode(' ', $this->attributes['updated_at']);
        $date = explode('-', $date[0]);
        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
        return $date;
    }

    function getCurrencyTitleAttribute()
    {
        if ($this->attributes['euro_amount'] !== '0') {
            if ($this->attributes['currency'] === 'euro')
                return "یورو";
            else
                return "دلار";
        } else
            return "ریال";
    }

    function getCurrencyTitleEnAttribute()
    {
        if ($this->attributes['euro_amount'] !== '0') {
            if ($this->attributes['currency'] === 'euro')
                return "euro";
            else
                return "dollar";
        } else
            return "ir";
    }

    function getFinalAmountAttribute()
    {
        if ($this->attributes['discount_amount']) {
            if ($this->attributes['discount_type'] === 'percent') {
                if ($this->attributes['euro_amount'] !== '0') {
                    return round(($this->attributes['euro_amount'] - $this->attributes['balance_amount']) - ($this->attributes['euro_amount'] * $this->attributes['discount_amount']) / 100);
                } else {
                    return round(($this->attributes['ir_amount'] - $this->attributes['balance_amount']) - ($this->attributes['ir_amount'] * $this->attributes['discount_amount']) / 100);
                }
            } else {
                if ($this->attributes['euro_amount'] !== '0') {
                    return ($this->attributes['euro_amount'] - $this->attributes['balance_amount']) - $this->attributes['discount_amount'];
                } else {
                    return ($this->attributes['ir_amount'] - $this->attributes['balance_amount']) - $this->attributes['discount_amount'];
                }
            }
        } else {
            if ($this->attributes['euro_amount'] !== '0') {
                return $this->attributes['euro_amount'] - $this->attributes['balance_amount'];
            } else {
                return $this->attributes['ir_amount'] - $this->attributes['balance_amount'];
            }
        }
    }

    function getFinalAmountIrAttribute()
    {
        if ($this->attributes['discount_amount']) {
            if ($this->attributes['discount_type'] === 'percent') {
                return round(($this->attributes['ir_amount'] - $this->attributes['balance_amount']) - ($this->attributes['ir_amount'] * $this->attributes['discount_amount']) / 100);
            } else {
                return ($this->attributes['ir_amount'] - $this->attributes['balance_amount']) - $this->attributes['discount_amount'];
            }
        } else {
            return $this->attributes['ir_amount'] - $this->attributes['balance_amount'];
        }
    }

    function getTransactionSumAttribute()
    {
        $invoices = Invoice::where('user_id', $this->attributes['user_id'])->where('payment_status', 'paid')->get();
        $amount = 0;
        foreach ($invoices as $invoice) {
            if ($invoice->discount_amount) {
                if ($invoice->discount_type === 'percent') {
                    if ($invoice->ir_amount === '0') {
                        $amount += round(($invoice->euro_amount - $invoice->balance_amount) - ($invoice->euro_amount * $invoice->discount_amount) / 100);
                    } else {
                        $amount += round(($invoice->ir_amount - $invoice->balance_amount) - ($invoice->ir_amount * $invoice->discount_amount) / 100);
                    }
                } else {
                    if ($invoice->ir_amount === '0') {
                        $amount += ($invoice->euro_amount - $invoice->balance_amount) - $invoice->discount_amount;
                    } else {
                        $amount += ($invoice->ir_amount - $invoice->balance_amount) - $invoice->discount_amount;
                    }
                }
            } else {
                if ($invoice->ir_amount === '0') {
                    $amount += $invoice->euro_amount - $invoice->balance_amount;
                } else {
                    $amount += $invoice->ir_amount - $invoice->balance_amount;
                }
            }
        }

        return $amount;
    }
}
