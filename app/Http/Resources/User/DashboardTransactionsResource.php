<?php
namespace App\Http\Resources\User;

use App\Models\Factor;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Providers\JDF;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardTransactionsResource extends JsonResource
{
    public function toArray($request)
    {
        $transaction = Transaction::orderBy('id','desc')->where('invoice_id', $this->id)->first();
        $dataArray = [
            'id' => $this->id,
            'amount' => $this->final_amount,
            'type' => $this->invoice_type,
            'type_title' => $this->invoice,
            'title' => $this->invoice_title,
            'method' => $this->payment_method,
            'methodTitle' => $this->payment_method_title,
            'status' => $this->payment_status,
            'currency' => $this->currency_title,
            'currency_sym' => $this->currency_title_en,
            'hash' => $transaction ? $transaction->transaction_code : '',
        ];

        $date = explode(' ',$this->created_at);
        $date = explode('-',$date[0]);
        $date = JDF::gregorian_to_jalali($date[0] , $date[1] , $date[2],'/');
        $dataArray['date'] = $date;

        return $dataArray;
    }
}