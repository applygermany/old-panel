<?php

namespace App\Http\Services\V1\User;

use App\Models\Off;
use App\Models\User;
use App\Models\Factor;
use App\Models\Resume;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Providers\SMS;
use App\Models\Motivation;
use App\Models\TelSupport;
use App\Providers\HesabFa;
use App\Models\Acceptance;
use App\Models\Transaction;
use App\Models\UserWebinar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UserTelSupport;
use PDF;
use Shetabit\Multipay\Invoice;
use App\Providers\Notification;
use App\Mail\MailVerificationCode;
use Illuminate\Support\Facades\Mail;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;

class InvoiceService
{
    public function goPay($user_id, $amount, $type, $associate_id)
    {
        $invoiceType = '';
        if ($type === 6) {
            $invoiceType = 'tel-support';
        } elseif ($type === 3 || $type === 2) {
            $invoiceType = 'resume';
        }

        $transaction = \App\Models\Invoice::where("user_id", $user_id)->where("payment_status", 'paid')->where("invoice_type", $invoiceType)->where("associate_id", $associate_id)->first();
        if ($user_id > 0) {
            if ($transaction) {
                //transaction is payed
                return 0;
            }
        }
        $invoice = (new Invoice)->amount($amount);
        $invoice->detail(['Amount' => $amount]);
        $payment = Payment::purchase($invoice, function ($driver, $transactionId)
        use ($transaction, $associate_id, $user_id, $amount, $type, $invoiceType) {
            $transaction = Transaction::where("transaction_code", $transactionId)->first();
            if (!$transaction) {
                $invoice2 = new \App\Models\Invoice();
                $invoice2->user_id = $user_id;;
                $invoice2->invoice_type = $invoiceType;
                $invoice2->invoice_title = 'pre-invoice';
                $invoice2->payment_method = 'online';
                $invoice2->ir_amount = $amount;
                $invoice2->euro_amount = 0;
                $invoice2->discount_amount = 0;
                $invoice2->is_resume = $type === 2;
                $invoice2->status = 'drafted';
                $invoice2->associate_id = $associate_id;
                $code = \App\Models\Invoice::where('invoice_title', 'pre-invoice')->count() + 1;
                $invoice2->code = MyHelpers::numberToEnglish(JDF::jdate('Y')) . '' . $code;
                $invoice2->payment_status = 'unpaid';
                $invoice2->save();

                $transaction = new Transaction();
                $transaction->transaction_code = $transactionId;
                $transaction->invoice_id = $invoice2->id;
                $transaction->amount = $amount;
                $transaction->status = 'unpaid';
                $transaction->code = MyHelpers::numberToEnglish(JDF::jdate('Y')) . '' . (Transaction::count() + 1);
            } else {
                $transaction->transaction_code = $transactionId;
            }
            $transaction->save();
        })->pay()->toJson();
        $payment = json_decode($payment);
        if ($payment) {
            return $payment->action;
        }
        return 2;
    }

    public function verifyPay(Request $request)
    {
        $transaction = Transaction::where("transaction_code", "like", "%$request->id%")->where("status", 'unpaid')->first();
        $transaction = Transaction::where("transaction_code", "like", "%$request->id%")->first();
        if (!$transaction) {
            abort(404);
        }

        try {

            $receipt = Payment::amount($transaction->amount)->transactionId($transaction->transaction_code)->verify();
            $transaction->status = 'paid';
            $transaction->save();

            $invoice = \App\Models\Invoice::find($transaction->invoice_id);
            $invoice->payment_status = 'paid';
            $invoice->invoice_title = 'receipt';
            $invoice->status = 'published';
            $invoice->confirmed_at = Carbon::now();
            $invoice->payment_at = MyHelpers::dateToJalali2(date("Y-m-d"));
            $code = \App\Models\Invoice::where('invoice_title', 'receipt')->count() + 1;
            $invoice->code = MyHelpers::numberToEnglish(JDF::jdate('Y')) . '' . $code;
            $invoice->save();

            if ($invoice->discount_code !== null) {
                $off = Off::where('code', $invoice->discount_code)->first();
                $off->current_usage = (int)$off->current_usage + 1;
                $off->save();
            }

            $item = $this->findAssociatedItem($invoice);

            if ($transaction->invoice->invoice_type == 'tel-support') {
                $item->status = 2;
                $this->chooseTelSupport($item, $invoice);
            } elseif ($transaction->invoice->invoice_type == 'resume') {
                if ($transaction->invoice->is_resume) {
                    $item->status = 1;
                } else {
                    $item->status = 1;
                }
            }
            $item->save();

            if ($transaction->invoice->user) {
                $name = $transaction->invoice->user->firstname . " " . $transaction->invoice->user->lastname;

                $pdf = PDF::loadView('invoice.pdfReceipt', [
                    'invoice' => $transaction->invoice,
                ], [], [
                    'subject' => $transaction->invoice->invoice_type_title,
                ]);
                $pdf->save(public_path('uploads/invoices/' . $transaction->invoice->id . '.pdf'), 'F');

                $send = (new SMS())->sendVerification($transaction->invoice->user->mobile, "add_receipt_invoice", "name=={$name}&code=={$transaction->invoice->code}");
                User::sendMail(new MailVerificationCode("add_receipt_invoice", [$name, $transaction->invoice->code, $transaction->invoice->invoice],
                    "finanial", public_path('uploads/invoices/' . $transaction->invoice->id . '.pdf')), $transaction->invoice->user->email);
                (new Notification("receipt_invoice", [$name, $transaction->invoice->code]))->send($transaction->invoice->user->id);


                //to admin
                $order_admin = User::where("isSuperAdmin", "1",)->first();
                $send = User::sendMail(new MailVerificationCode("admin_factor_paid", [
                    $name,
                    $transaction->invoice->id,
                    $transaction->invoice->invoice,
                ], "admin_factor_paid"), $order_admin->email);
                (new Notification("admin_factor_paid", [
                    $name,
                    $transaction->invoice->id,
                    $transaction->invoice->invoice,
                ]))->send($order_admin->id);

            }
            return redirect(env("APP_FRONT_URL") . "/payment/" . $transaction->transaction_code);
        } catch (InvalidPaymentException $exception) {
            if ($transaction->invoice->invoice_type == 'tel-support') {
                $this->cancelTelSupport($item);
            }
            return redirect(env("APP_FRONT_URL") . "/payment/" . $transaction->transaction_code . "?success=false");
        }
    }

    public function findAssociatedItem($invoice)
    {
        if ($invoice->invoice_type === 'tel-support')
            return TelSupport::find($invoice->associate_id);
        elseif ($invoice->invoice_type === 'resume') {
            if ($invoice->is_resume) {
                return Resume::find($invoice->associate_id);
            } else {
                return Motivation::find($invoice->associate_id);
            }
        }
    }

    public function chooseTelSupport($item, $invoice)
    {
        $userTelSupport = UserTelSupport::where("tel_support_id", $item->id)->first();
        if ($userTelSupport) {
            $userTelSupport->user_id = $invoice->user_id;
            $userTelSupport->save();
        }
    }

    public function cancelTelSupport($item)
    {
        $uTel = UserTelSupport::where("tel_support_id", $item->id)->first();
        if ($uTel) {
            $uTel->delete();
        }
    }

    public function checkPay($type, Request $request)
    {
        $invoice = Invoice::where('user_id', auth()->guard("api")->user()->id)->first();
        $transaction = Transaction::where("invoice_id", $invoice->id);
        if (!$transaction->first()) {
            return response([
                'status' => 0,
                'msg' => 'تراکنش موجود نیست',
            ]);
        }
        if ($m = $invoice->where("associate_id", $request->id)->first()) {
            $payed = $m->payment_status;
            if ($payed == 'paid') {
                return response([
                    'status' => 1,
                    'msg' => 'پرداخت شده',
                ]);
            }
            return response([
                'status' => 0,
                'msg' => 'پرداخت نشده',
            ]);
        }
        return response([
            'status' => 0,
            'msg' => 'تراکنش موجود نیست',
        ]);
    }
}
