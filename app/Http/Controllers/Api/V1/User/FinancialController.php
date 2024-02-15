<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\Invoice;
use App\Models\Off;
use App\Models\Factor;
use App\Models\Transaction;
use App\Providers\JDF;
use Illuminate\Http\Request;
use App\Providers\MyHelpers;
use App\Http\Controllers\Controller;
use Shetabit\Payment\Facade\Payment;

class FinancialController extends Controller
{

    public function getFactor($id, Request $request)
    {
        $factor = Factor::find($id);
        if (!$factor) {
            return response([
                'status' => 0,
                'msg' => 'فاکتور یافت نشد',
                "factor" => NULL,
            ]);
        }
        if ($request->off_code && $request->off_code != "") {
            $off = Off::where("code", "=", $request->off_code)
                ->where("status", 1)
                ->first();
            if (!$off) {
                return response([
                    'status' => 1,
                    'msg' => '',
                    "factor" => [
                        "id" => $id,
                        "user" => [
                            "id" => $factor->user->id,
                            "name" => $factor->user->firstname . " " . $factor->user->lastname,
                            "mobile" => $factor->user->mobile,
                            "email" => $factor->user->email,
                        ],
                        "data" => $factor,
                    ],
                    "discount" => 0,
                    "discount_type" => 0,
                    "amount" => $factor->amount_final,
                    "discount_msg" => "code is not valid",
                ]);
            }
            if ($off->current_usage >= $off->maximum_usage) {
                return response([
                    'status' => 1,
                    'msg' => '',
                    "factor" => [
                        "id" => $id,
                        "user" => [
                            "id" => $factor->user->id,
                            "name" => $factor->user->firstname . " " . $factor->user->lastname,
                            "mobile" => $factor->user->mobile,
                            "email" => $factor->user->email,
                        ],
                        "data" => $factor,
                    ],
                    "discount" => 0,
                    "discount_type" => 0,
                    "amount" => $factor->amount_final,
                    "discount_msg" => "maximum reach",
                ]);
            }
            if (MyHelpers::dateToJalali2(date("Y-m-d")) > MyHelpers::numberToEnglish($off->end_date) && $off->end_date != '') {
                return response([
                    'status' => 1,
                    'msg' => '',
                    "factor" => [
                        "id" => $id,
                        "user" => [
                            "id" => $factor->user->id,
                            "name" => $factor->user->firstname . " " . $factor->user->lastname,
                            "mobile" => $factor->user->mobile,
                            "email" => $factor->user->email,
                        ],
                        "data" => $factor,
                    ],
                    "discount" => 0,
                    "discount_type" => 0,
                    "amount" => $factor->amount_final,
                    "discount_msg" => "code is expired",
                ]);
            }
            if ($off->user_id == 0 || $off->user_id == $factor->user->id) {
                return response([
                    'status' => 1,
                    'msg' => '',
                    "factor" => [
                        "id" => $id,
                        "user" => [
                            "id" => $factor->user->id,
                            "name" => $factor->user->firstname . " " . $factor->user->lastname,
                            "mobile" => $factor->user->mobile,
                            "email" => $factor->user->email,
                        ],
                        "data" => $factor,
                    ],
                    "discount" => $off->discount,
                    "discount_type" => $off->discount_type,
                    "amount" => $off->discount_type == 1 ? round($factor->amount_final - ($factor->amount_final * $off->discount) / 100) : $factor->amount_final - $off->discount,
                    "discount_msg" => "code is working",
                ]);
            } elseif ($off->user_id != $factor->user->id) {
                return response([
                    'status' => 1,
                    'msg' => '',
                    "factor" => [
                        "id" => $id,
                        "user" => [
                            "id" => $factor->user->id,
                            "name" => $factor->user->firstname . " " . $factor->user->lastname,
                            "mobile" => $factor->user->mobile,
                            "email" => $factor->user->email,
                        ],
                        "data" => $factor,
                    ],
                    "discount" => 0,
                    "discount_type" => 0,
                    "amount" => $factor->amount_final,
                    "discount_msg" => "code is not for user",
                ]);
            }
        }
        return response([
            'status' => 1,
            'msg' => '',
            "factor" => [
                "id" => $id,
                "user" => [
                    "id" => $factor->user->id,
                    "name" => $factor->user->firstname . " " . $factor->user->lastname,
                    "mobile" => $factor->user->mobile,
                    "email" => $factor->user->email,
                ],
                "data" => $factor,
            ],
            "discount" => 0,
            "discount_type" => 0,
            "amount" => $factor->amount_final,
            "discount_msg" => "",
        ]);
    }

    public function getTransaction($hash, Request $request)
    {
        $paymentLink = "https://idpay.ir/p/ws/{$hash}";
        $transaction = Transaction::where("transaction_code", $hash)->first();
        if (!$transaction) {
            return response([
                'status' => 0,
                'msg' => 'تراکنش یافت نشد',
                "data" => NULL,
            ]);
        }

        $invoice = $transaction->invoice;
        $discount = $invoice->discount_type == 'percent' ? round(($invoice->ir_amount * $invoice->discount_amount) / 100) : $invoice->discount_amount;

        $discount_msg = "";
        $isFailed = false;
        if ($request->off_code && $request->off_code != "") {
            $off = Off::where("code", explode('?', $request->off_code)[0])
                ->where("status", 1)
                ->where('type', 'tel-support')
                ->first();
            if (!$off) {
                $discount = $invoice->discount_amount;
                $discount_msg = "code is not valid";
                $isFailed = true;
            } elseif ($invoice->discount_amount > 0) {
                $discount = $invoice->discount_amount;
                $discount_msg = "code already in use for this transaction";
                $isFailed = true;
            } elseif ($off->current_usage >= $off->maximum_usage) {
                $discount = 0;
                $discount_msg = "maximum reach";
                $isFailed = true;
            } elseif (strtotime(MyHelpers::dateToJalali2(date("Y-m-d"))) < strtotime(MyHelpers::numberToEnglish($off->start_date)) && $off->start_date != '') {
                $discount = 0;
                $discount_msg = "code is not valid";
                $isFailed = true;
            } elseif (strtotime(MyHelpers::dateToJalali2(date("Y-m-d"))) > strtotime(MyHelpers::numberToEnglish($off->end_date)) && $off->end_date != '') {
                $discount = 0;
                $discount_msg = "code is expired";
                $isFailed = true;
            } elseif ($off->user_id != 0 && $off->user_id != $invoice->user->id) {
                $discount = 0;
                $discount_msg = "code is not for user";
                $isFailed = true;
            } elseif ($off->user_id == 0 || $off->user_id == $invoice->user->id) {

                $invoices = Invoice::where('user_id', $invoice->user->id)->where('discount_code', $off->code)->count();
                if ($invoices < $off->user_usage) {
                    $invoice = Invoice::find($invoice->id);
                    $invoice->discount_type = $off->discount_type == 1 ? 'percent' : 'fixed';
                    $invoice->discount_amount = $off->discount;
                    $invoice->discount_code = $off->code;
                    $invoice->save();

                    $discount = $off->discount_type == 1 ? round(($invoice->ir_amount * $off->discount) / 100) : $off->discount;
                    $discount_msg = "کد معتبر است";

                    $amount = (float)$invoice->final_amount_ir;
                    $invoiceId = $invoice->id;

                    $invoiceS = (new \Shetabit\Multipay\Invoice)->amount($amount);
                    $invoiceS->detail(['Amount' => $amount]);
                    $payment = Payment::purchase($invoiceS, function ($driver, $transactionId)
                    use ($transaction, $invoiceId, $amount) {
                        $transaction = Transaction::where("transaction_code", $transactionId)->first();
                        if (!$transaction) {
                            $transaction = new Transaction();
                            $transaction->transaction_code = $transactionId;
                            $transaction->invoice_id = $invoiceId;
                            $transaction->amount = $amount;
                            $transaction->status = 'unpaid';
                            $transaction->code = MyHelpers::numberToEnglish(JDF::jdate('Y')) . '' . (Transaction::count() + 1);
                        } else {
                            $transaction->transaction_code = $transactionId;
                        }
                        $transaction->save();
                    })->pay()->toJson();
                    $paymentLink = json_decode($payment)->action;
                }
            } else {
                $discount = 0;
                $discount_msg = "maximum reach";
            }
        } else {
            $amount = (float)$invoice->final_amount_ir;
            $invoiceId = $invoice->id;
            $invoiceS = (new \Shetabit\Multipay\Invoice)->amount($amount);
            $invoiceS->detail(['Amount' => $amount]);
            $payment = Payment::purchase($invoiceS, function ($driver, $transactionId)
            use ($transaction, $invoiceId, $amount) {
                $transaction = Transaction::where("transaction_code", $transactionId)->first();
                if (!$transaction) {
                    $transaction = new Transaction();
                    $transaction->transaction_code = $transactionId;
                    $transaction->invoice_id = $invoiceId;
                    $transaction->amount = $amount;
                    $transaction->status = 'unpaid';
                    $transaction->code = MyHelpers::numberToEnglish(JDF::jdate('Y')) . '' . (Transaction::count() + 1);
                } else {
                    $transaction->transaction_code = $transactionId;
                }
                $transaction->save();
            })->pay()->toJson();
            $paymentLink = json_decode($payment)->action;
        }

        $invoice = Invoice::find($invoice->id);

        return response([
            'status' => $isFailed ? 0 : 1,
            'msg' => '',
            "transaction" => [
                "id" => $invoice->id,
                "user" => isset($invoice->user) ? [
                    "id" => $invoice->user->id,
                    "name" => $invoice->user->firstname . " " . $invoice->user->lastname,
                    "mobile" => $invoice->user->mobile,
                    "email" => $invoice->user->email,
                ] : [],
                "data" => $invoice,
            ],
            "discount" => (float)$discount,
            "amount" => (float)$invoice->final_amount_ir,
            "discount_msg" => $discount_msg,
            "discount_amount" => $invoice->discount_amount,
            "discount_type" => $invoice->discount_type,
            "payment_gate" => $paymentLink,
        ]);
    }


}
