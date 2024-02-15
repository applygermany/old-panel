<?php

namespace App\Http\Controllers\Admin;

use App\ExcelExports\Transactions;
use App\Models\User;
use App\Models\Factor;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Providers\SMS;
use App\Models\Pricing;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Shetabit\Multipay\Invoice;
use App\Providers\Notification;
use App\Mail\MailVerificationCode;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Shetabit\Payment\Facade\Payment;

class FinanialController extends Controller
{
    function factors()
    {
        $factors = Factor::orderBy('id', 'DESC')->paginate(10);
        $users = User::select('id', 'firstname', 'lastname', 'mobile', 'email')->get();
        return view('admin.financials.factors', compact('factors', 'users'));
    }

    function pricing()
    {
        $pricing = Pricing::first();
        return view('admin.financials.pricing', compact('pricing'));
    }

    function updatePrice(Request $request)
    {
        $rules = [
            'resume_price' => 'required|integer',
            'resume_bi_price' => 'required|integer',
            'euro_price' => 'required|integer',
            'motivation_price' => 'required|integer',
            'invite_action' => 'required|integer',
            'extra_university_price' => 'required|integer',
            'package_price' => 'required|integer',
            'package_2_price' => 'required|integer',
            'add_college_price' => 'required|integer',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            session()->flash("error", "مقادیر را کنترل کنید");
            return redirect()->back();
        }
        $pricing = Pricing::first();
        $pricing->resume_price = $request->resume_price;
        $pricing->resume_bi_price = $request->resume_bi_price;
        $pricing->motivation_price = $request->motivation_price;
        $pricing->invite_action = $request->invite_action;
        $pricing->euro_price = $request->euro_price;
        $pricing->package_2_price = $request->package_2_price;
        $pricing->tel_maximum_price = $request->tel_maximum_price;
        $pricing->extra_university_price = $request->extra_university_price;
        $pricing->package_price = $request->package_price;
        $pricing->add_college_price = $request->add_college_price;
        if ($pricing->save()) {
            session()->flash("success", "مبالغ با موفقیت ویرایش شد");
            return redirect()->back();
        }
        session()->flash("error", "خطایی رخ داده");
        return redirect()->back();
    }

    function getFactors(Request $request)
    {
        $factor = Factor::Query();
        if ($request->searchUser)
            $factor->where('user_id', $request->searchUser);
        $factors = $factor->orderBy('id', 'DESC')->paginate(10);
        return view('admin.financials.listFactors', compact('factors'))->render();
    }

    function acceptFactor(Request $request)
    {
        $factor = Factor::find($request->id);
        $transaction = Transaction::where("associate_id", $request->id)->first();

        $factor->amount = $request->amount_irt;
        $factor->amount_euro = $request->amount_eur;
        $factor->off_type = $request->type;
        $factor->factor_desc = $request->factor_desc;
        $factor->status = $request->invoiceType === 'invoice' ? 0 : 1;
        $factor->payment_type = $request->paymentType;
        $factor->payment_method = $request->paymentMethod;

        $discount_raw = 0;
        if ($request->discount) {
            $factor->discount = $request->discount;
            if ($factor->off_type == 1) {
                $discount_raw = round(($request->amount_irt * $request->discount) / 100);
                $factor->amount_final = round($request->amount_irt - ($request->amount_irt * $request->discount) / 100);
            } else {
                $discount_raw = $request->discount;
                $factor->amount_final = $request->amount_irt - $request->discount;
            }
        } else {
            $factor->amount_final = $request->amount_irt;
        }
        if ($request->discount_desc) {
            $factor->discount_desc = $request->discount_desc;
        }
        $factor->invoice_type = $request->invoiceType;

        if ($factor->payment_method !== $request->paymentMethod) {
            if ($request->paymentMethod === 'online') {
                $invoice = (new Invoice)->amount($factor->amount_final * 10);
                $invoice->detail(['Amount' => $factor->amount_final * 10]);
                $payment = Payment::purchase($invoice, function ($driver, $transactionId)
                use ($discount_raw, $request, $factor) {
                    $transaction = Transaction::where("associate_id", $request->id)->first();
                    global $type;
                    $transaction->transaction_code = $transactionId;
                    $transaction->amount = $request->amount_eur == 0 ? $factor->amount_final : $request->amount_eur;
                    $transaction->currency = $request->amount_eur == 0 ? 'toman' : 'euro';
                    $transaction->discount = $discount_raw;
                    $transaction->discount_type = $factor->off_type;
                    $transaction->discount_amount = $factor->discount;
                    $transaction->type = $type;
                    $transaction->save();
                })->pay()->toJson();
            }
        }

        if ($request->invoiceType === 'receipt') {
            $factor->status = 1;
            $factor->invoice_type = 'receipt';
            $transaction->status = 1;
        }
        if ($factor->save()) {
            $transaction->save();

            return 1;
        }
        return 2;
    }

    function editFactor($id)
    {
        $factor = Factor::find($id);
        $users = User::select('id', 'firstname', 'lastname', 'mobile', 'email')->get();
        return view('admin.financials.editFactor', compact('factor', 'users'))->render();
    }

    function transactions()
    {
        $transactions = Transaction::orderBy('id', 'DESC')->paginate(10);
        $users = User::select('id', 'firstname', 'lastname')->get();
        return view('admin.financials.transactions', compact('transactions', 'users'));
    }

    function getTransactions(Request $request)
    {
        $transaction = Transaction::Query();
        if ($request->searchUser)
            $transaction->where('user_id', $request->searchUser);
        if ($request->searchStartDate) {
            $date = MyHelpers::numberToEnglish(explode('/', $request->searchStartDate));
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];

            $date = JDF::jalali_to_gregorian($year, $month, $day, '-');


            $transaction->where('created_at', '>=', $date);
        }
        if ($request->searchEndDate) {
            $date = MyHelpers::numberToEnglish(explode('/', $request->searchEndDate));
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];

            $date = JDF::jalali_to_gregorian($year, $month, $day, '-');

            $transaction->where('created_at', '<=', $date);
        }
        $transactions = $transaction->orderBy('id', 'DESC')->paginate(10);
        return view('admin.financials.listTransactions', compact('transactions'))->render();
    }

    function exportTransactions(Request $request)
    {
        return Excel::download(new Transactions($request), 'transactions.xlsx');
    }



}
