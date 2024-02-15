<?php

namespace App\Http\Controllers\Admin;

use App\ExcelExports\Transactions;
use App\Http\Controllers\Controller;
use App\Mail\MailVerificationCode;
use App\Models\BankAccount;
use App\Models\Invoice;
use App\Models\Off;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserExtraUniversity;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Providers\Notification;
use App\Providers\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Shetabit\Payment\Facade\Payment;
use Illuminate\Support\Facades\View;

class InvoiceController extends Controller
{
    function invoices($type)
    {
        $bankAccounts = BankAccount::where('status', 'publish')->get();
        if ($type !== 'create') {
            if ($type === 'pre-invoice') {
                $invoices = Invoice::orderBy('confirmed_at', 'desc')->where('invoice_title', 'pre-invoice')->where('payment_at', null)->paginate(10);
                $users = User::where('level', 1)->get();
                return view('admin.financials.invoices.pre-invoice', compact('invoices', 'users', 'type', 'bankAccounts'));
            } elseif ($type === 'receipt') {
                $invoices = Invoice::orderBy('code', 'desc')->where('invoice_title', 'receipt')->where('manager_confirmed_at', '<>', null)->paginate(10);
                $users = User::where('level', 1)->get();
                return view('admin.financials.invoices.receipt', compact('invoices', 'users', 'type', 'bankAccounts'));
            } elseif ($type === 'confirmed') {
                $invoices = Invoice::orderBy('code', 'desc')->where('status', 'published')
                    ->where('payment_at', '<>', null)
                    ->where('manager_confirmed_at', null)->with('bankRelation')->paginate(10);
                $users = User::where('level', 1)->get();
                $type = 'confirmed';
                return view('admin.financials.invoices.confirmed', compact('invoices', 'users', 'type', 'bankAccounts'));
            }
        } else {
            return view('admin.financials.invoices.create');
        }
    }

    function create()
    {
        $invoices = Invoice::orderBy('code', 'desc')->paginate(10);
        $users = User::where('level', 1)->get();
        $banks = BankAccount::where('status', 'publish')->get();

        return view('admin.financials.invoices.create', compact('invoices', 'users', 'banks'));
    }

    function saveInvoice(Request $request)
    {
        if (!$request->ir_amount && !$request->euro_amount) {
            session()->flash('error', 'مبلغ را به ریال یا یورو وارد کنید');
            return redirect()->back();
        }

        if ($request->user === '0' && $request->invoice_title === 'pre-invoice') {
            session()->flash('error', 'امکان صدور پیش فاکتور برای این کاربر وجود ندارد');
            return redirect()->back();
        }

        $invoice = new Invoice();
        $invoice->user_id = $request->user ?? 0;
        $invoice->invoice_type = $request->invoice_type ?? 'resume';
        $invoice->invoice_title = $request->invoice_title;
        $invoice->payment_method = $request->payment_method ?? 'cash';
        $invoice->bank_account_id = $request->bankAccount;
        $invoice->ir_amount = $request->ir_amount ?? 0;
        $invoice->euro_amount = $request->euro_amount;
        $invoice->confirmed_at = Carbon::now();
        $invoice->discount_type = $request->discount_type;
        $invoice->discount_amount = $request->discount_amount ?? 0;
        $invoice->discount_description = $request->discount_description;
        $invoice->invoice_description = $request->invoice_description;
        $invoice->currency = $request->currency;

//      $invoice->balance_amount = $request->balance ?? 0;
        $invoice->balance_amount = $request->unTouchedBalance ?? 0;
        $invoice->status = 'published';
        $invoice->payment_at = $request->invoice_title == 'receipt' ? MyHelpers::numberToEnglish($request->paymentAt) : null;
        $invoice->payment_status = $request->invoice_title == 'receipt' ? 'paid' : 'unpaid';

        if ($request->invoice_title === 'receipt') {
            $count = Invoice::orderBy('payment_at', 'desc')->where('payment_at', '<>', null)->count();
            $code = MyHelpers::numberToEnglish(JDF::jdate('Y'));

            if ($count > 999) {
                $code .= '' . $count;
            } elseif ($count > 99) {
                $code .= '0' . $count;
            } elseif ($count > 9) {
                $code .= '00' . $count;
            }
            if ($count <= 9) {
                $code .= '000' . $count;
            }
            $invoice->code = $code;
        } else {
            $count = Invoice::orderBy('payment_at', 'desc')->where('invoice_title', $request->invoice_title)->count();
            $code = MyHelpers::numberToEnglish(JDF::jdate('Y'));

            if ($count > 999) {
                $code .= '' . $count;
            } elseif ($count > 99) {
                $code .= '0' . $count;
            } elseif ($count > 9) {
                $code .= '00' . $count;
            }
            if ($count <= 9) {
                $code .= '000' . $count;
            }
            $invoice->code = $code;
        }


        $amount = 0;
        if ($request->payment_method === 'online') {
            if ($request->discount_amount) {
                if ($request->discount_type == 'percent') {
                    if ($request->euro_amount !== 0) {
                        $amount = round($request->euro_amount - ($request->euro_amount * $request->discount_amount) / 100);
                    } else {
                        $amount = round($request->ir_amount - ($request->ir_amount * $request->discount_amount) / 100);
                    }
                } else {
                    if ($request->euro_amount !== 0) {
                        $amount = $request->euro_amount - $request->discount_amount;
                    } else {
                        $amount = $request->ir_amount - $request->discount_amount;
                    }
                }
            } else {
                if ($request->euro_amount !== 0) {
                    $amount = $request->euro_amount;
                } else {
                    $amount = $request->ir_amount;
                }
            }
        }

        if (isset($request->discount_code)) {
            $invoice->discount_code = $request->discount_code;
        }

        if ($invoice->save()) {
            $extraUniversities=null;
            if($invoice->invoice_type=='final'){
                $extraUniversities=UserExtraUniversity::where('user_id',$invoice->user->id)->get();
            }
            if (isset($request->discount_code)) {
                $off = Off::where('code', $request->discount_code)->first();
                if ($off) {
                    $off->current_usage = (int)$off->current_usage + 1;
                    $off->save();
                }
            }

            if ($request->payment_method === 'online') {
                $invoice2 = (new \Shetabit\Multipay\Invoice)->amount($amount);
                $invoice2->detail(['Amount' => $amount]);
                $payment = Payment::purchase($invoice, function ($driver, $transactionId)
                use ($amount, $request, $invoice) {
                    $transaction = new Transaction();
                    $transaction->invoice_id = $invoice->id;
                    $transaction->amount = $amount;
                    $transaction->status = 'unpaid';
                    $transaction->code = MyHelpers::numberToEnglish(JDF::jdate('Y')) . '' . (Transaction::count() + 1);
                    $transaction->transaction_code = $transactionId;
                    $transaction->save();
                })->pay()->toJson();
            }

            $user = User::find($request->user);
            $name = $user->firstname . " " . $user->lastname;
            $newInvoice= Invoice::where('id',$invoice['id'])->first();
            $invoice=$newInvoice;
            if ($request->invoice_title === 'pre-invoice') {
                $pdf = PDF::loadView('invoice.pdfPreInvoice', [
                    'invoice' => $invoice,
                ], ['extra_universities' => $extraUniversities], [
                    'subject' => $invoice->invoice_type_title,
                ]);
                $pdf->save(public_path('uploads/invoices/' . $invoice->id . '.pdf'), 'F');
                $send = (new SMS())->sendVerification($user->mobile, "add_pre_invoice", "name=={$name}&code=={$invoice->code}&type=={$invoice->invoice_type_title}");
                User::sendMail(new MailVerificationCode("add_pre_invoice", [$name, $invoice->code, $invoice->invoice_type_title, $invoice->payment_method_title],
                    "finanial", public_path('uploads/invoices/' . $invoice->id . '.pdf')), $user->email);
                $notif = (new Notification("add_pre_invoice", [$name, $invoice->code, $invoice->invoice]))->send($user->id);
            }

            session()->flash('success', 'قاکتور با موفقیت صادر گردید');
        } else {
            session()->flash('error', 'صدور فاکتور با شکست مواجه گردید');
        }

        return redirect()->back();
    }

    function deleteInvoice($id)
    {
        $invoice = Invoice::find($id);
        if ($invoice->delete()) {
            $transaction = Transaction::where("invoice_id", $id)->delete();
            session()->flash('success', 'فاکتور حذف شد');
        } else {
            session()->flash('error', 'خطا در حذف  فاکتور');
        }
        return redirect()->back();
    }

    function invoiceSearch(Request $request)
    {
        $bankAccounts = BankAccount::where('status', 'publish')->get();
        $invoices = Invoice::query();
        if ($request->invoiceTitle === 'confirmed') {
            $invoices->where('payment_at', '<>', null);
        } else {
            $invoices->where('invoice_title', $request->invoiceTitle);
        }
        if ($request->searchUser) {
            $invoices->where('user_id', $request->searchUser);
        }
        if ($request->contractCode) {
            $users = User::where('contract_code', $request->contractCode)->pluck('id');
            $invoices->whereIn('user_id', $users);
        }

        if ($request->paymentMethod) {
            $invoices->whereIn('payment_method', $request->paymentMethod);
        }
        if ($request->payFor) {
            $invoices->whereIn('invoice_type', $request->payFor);
        }
        if ($request->code) {
            $invoices->where('code', $request->code);
        }
        if ($request->euro_amount) {
            $invoices->where('euro_amount', $request->euro_amount);
        }
        if ($request->ir_amount) {
            $invoices->where('ir_amount', $request->ir_amount);
        }
        if ($request->currency) {
            $invoices->where('currency', $request->currency);
        }
        if ($request->bankAccount) {
            $invoices->where('bank_account_id', $request->bankAccount);
        }
        if ($request->invoiceTitle != 'receipt') {//agar resid nabood
            if ($request->paymentAt) {
                $invoices->where('payment_at', MyHelpers::numberToEnglish($request->paymentAt));
            }
            if ($request->searchStartDate) {
                $date = MyHelpers::numberToEnglish(explode('/', $request->searchStartDate));
                $year = $date[0];
                $month = $date[1];
                $day = $date[2];
                $date = JDF::jalali_to_gregorian($year, $month, $day, '-');
                $invoices->where('created_at', '>=', $date);
            }
            if ($request->searchEndDate) {
                $date = MyHelpers::numberToEnglish(explode('/', $request->searchEndDate));
                $year = $date[0];
                $month = $date[1];
                $day = $date[2];

                $date = JDF::jalali_to_gregorian($year, $month, $day, '-');

                $invoices->where('created_at', '<=', $date);
            }
        }

        $type = $request->invoiceTitle;
        if ($request->invoiceTitle === 'pre-invoice') {
            $invoices = $invoices->where('payment_at', null)->orderBy('confirmed_at', 'DESC')->paginate(10);
            return view('admin.financials.invoices.partials.list-pre-invoice', compact('invoices', 'type', 'bankAccounts'))->render();
        } elseif ($request->invoiceTitle === 'receipt') {
            if ($request->searchStartDate) {
                $date = MyHelpers::numberToEnglish(explode('/', $request->searchStartDate));
                $date=implode('/',$date);
                $invoices->where('payment_at', '>=', $date);
            }
            if ($request->searchEndDate) {
                $date = MyHelpers::numberToEnglish(explode('/', $request->searchEndDate));
                $date=implode('/',$date);
                $invoices->where('payment_at', '<=', $date);
            }

            $invoices = $invoices->where('payment_at', '<>', null)->where('manager_confirmed_at', '<>', null)->orderBy('code', 'DESC')->paginate(10);
            return view('admin.financials.invoices.partials.list-receipt', compact('invoices', 'type', 'bankAccounts'))->render();
        } elseif ($request->invoiceTitle === 'confirmed') {
            $invoices = $invoices->orderBy('code', 'desc')->where('status', 'published')
                ->where('payment_at', '<>', null)
                ->where('manager_confirmed_at', null)->paginate(10);
            $type = 'confirmed';
            return view('admin.financials.invoices.partials.list-confirmed', compact('invoices', 'type', 'bankAccounts'))->render();
        }

    }

    function confirmInvoice($id)
    {
        $invoice = Invoice::find($id);
        $banks = BankAccount::where('status', 'publish')->get();
        return view('admin.financials.invoices.partials.confirm', compact('invoice', 'banks'))->render();
    }

    function editInvoiceUser($id)
    {
        $invoice = Invoice::find($id);
        $banks = BankAccount::where('status', 'publish')->get();
        $users = User::where('level', 1)->get();
        return view('admin.financials.invoices.partials.editUser', compact('invoice', 'banks', 'users'))->render();
    }

    function updateEditUser(Request $request)
    {
        $invoice = Invoice::find($request->id);
        $invoice->user_id = $request->user;
        if ($invoice->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    function showInvoice($id)
    {
        $invoice = Invoice::find($id);
        return view('admin.financials.invoices.partials.show', compact('invoice'))->render();
    }

    function acceptInvoiceManager($id)
    {
        $invoice = Invoice::find($id);
        $invoice->manager_confirmed_at = Carbon::now();
        $invoice->invoice_title = 'receipt';


        if ($invoice->save()) {

            $user = $invoice->user;
            $name = $user->firstname . " " . $user->lastname;
            $pdf = PDF::loadView('invoice.pdfReceipt', [
                'invoice' => $invoice,
            ], [], [
                'subject' => $invoice->invoice_type_title,
            ]);
            $pdf->save(public_path('uploads/invoices/' . $invoice->id . '.pdf'), 'F');

            $send = (new SMS())->sendVerification($user->mobile, "add_receipt_invoice", "name=={$name}&code=={$invoice->code}");
            User::sendMail(new MailVerificationCode("add_receipt_invoice", [$name, $invoice->code, $invoice->invoice_type_title],
                "finanial", public_path('uploads/invoices/' . $invoice->id . '.pdf')), $user->email);
            $notif = (new Notification("receipt_invoice", [$name, $invoice->code]))->send($user->id);

            session()->flash('success', 'رسید با موفقیت تایید گردید');
        } else {
            session()->flash('error', 'تایید رسید با شکست مواجه گردید');
        }
        return redirect()->back();
    }

    function invoicePaymentDate(Request $request)
    {
        $invoice = Invoice::find($request->id);
        $invoice->payment_method = $request->payment_method ?? 'cash';
        $invoice->bank_account_id = $request->bankAccount;
        $invoice->ir_amount = $request->ir_amount ?? 0;
        $invoice->invoice_description = $request->invoice_description;
        $invoice->payment_at = MyHelpers::numberToEnglish($request->paymentAt);
        //$invoice->invoice_title = 'receipt';
        $invoice->payment_status = $request->payment_method === 'online' ? 'unpaid' : 'paid';
        $invoice->status = 'published';

        $count = Invoice::orderBy('payment_at', 'desc')->where('payment_at', '<>', null)->count();
        $code = MyHelpers::numberToEnglish(JDF::jdate('Y'));
        if ($count > 999) {
            $code .= '' . $count;
        } elseif ($count > 99) {
            $code .= '0' . $count;
        } elseif ($count > 9) {
            $code .= '00' . $count;
        }
        if ($count <= 9) {
            $code .= '000' . $count;
        }
        $invoice->code = $code;

        $amount = 0;
        if ($request->payment_method === 'online') {
            if ($request->discount_amount) {
                if ($request->discount_type == 'percent') {
                    if ($request->euro_amount !== '0') {
                        $amount = round($request->euro_amount - ($request->euro_amount * $request->discount_amount) / 100);
                    } else {
                        $amount = round($request->ir_amount - ($request->ir_amount * $request->discount_amount) / 100);
                    }
                } else {
                    if ($request->euro_amount !== '0') {
                        $amount = $request->euro_amount - $request->discount_amount;
                    } else {
                        $amount = $request->ir_amount - $request->discount_amount;
                    }
                }
            } else {
                if ($request->euro_amount !== '0') {
                    $amount = $request->euro_amount;
                } else {
                    $amount = $request->ir_amount;
                }
            }
        }

        if ($invoice->save()) {
            if ($request->payment_method === 'online') {
                $invoice2 = (new \Shetabit\Multipay\Invoice)->amount($amount);
                $invoice2->detail(['Amount' => $amount]);
                $payment = Payment::purchase($invoice, function ($driver, $transactionId)
                use ($amount, $request, $invoice) {
                    $transaction = new Transaction();
                    $transaction->invoice_id = $invoice->id;
                    $transaction->amount = $amount;
                    $transaction->status = 'unpaid';
                    $transaction->code = MyHelpers::numberToEnglish(JDF::jdate('Y')) . '' . (Transaction::count() + 1);
                    $transaction->transaction_code = $transactionId;
                    $transaction->save();
                })->pay()->toJson();
            }
            return 1;
        } else {
            return 0;
        }

    }

    function generateInvoice($id)
    {
        $invoice = Invoice::find($id);
        $banks = BankAccount::all();
        $extraUniversities=null;
        if($invoice->invoice_type=='final'){
            $extraUniversities=UserExtraUniversity::where('user_id',$invoice->user->id)->get();
        }
        if ($invoice->invoice_title === 'pre-invoice') {
            $pdf = PDF::loadView('invoice.pdfPreInvoice', [
                'invoice' => $invoice,
                'banks' => $banks
            ], ['extra_universities' => $extraUniversities], [
                'subject' => $invoice->invoice_type_title
            ]);
            return $pdf->stream($invoice->id . ' ' . $invoice->created_at . '.pdf');
        } else {

            $pdf = PDF::loadView('newPdf', [
                'invoice' => $invoice,
            ], ['extra_universities' => $extraUniversities], [
                'subject' => $invoice->invoice_type_title,

            ]);
            return $pdf->stream($invoice->id . ' ' . $invoice->created_at . '.pdf');
        }
    }

    function exportInvoice(Request $request)
    {
        $invoices = Invoice::query();
        if ($request->exportUser) {
            $invoices->where('user_id', $request->exportUser);
        } else {
            $invoices->where('invoice_title', $request->exportInvoiceTitle);
        }
        if ($request->exportContractCode) {
            $users = User::where('contract_code', $request->exportContractCode)->pluck('id');
            $invoices->whereIn('user_id', $users);
        }
        if ($request->exportInvoiceType) {
            $invoices->where('invoice_title', $request->exportInvoiceType);
        }
        if ($request->exportPaymentMethod) {
            $arrayMethod = explode(',', $request->exportPaymentMethod);
            $invoices->whereIn('payment_method', $arrayMethod);
        }
        if ($request->exportPayFor) {
            $invoices->whereIn('invoice_type', $request->exportPayFor);
        }
        if ($request->exportCode) {
            $invoices->where('code', $request->exportCode);
        }
        if ($request->exporteuro_amount) {
            $invoices->where('euro_amount', $request->exporteuro_amount);
        }
        if ($request->exportir_amount) {
            $invoices->where('ir_amount', $request->exportir_amount);
        }
        if ($request->exportcurrency) {
            $invoices->where('currency', $request->exportcurrency);
        }
        if ($request->exportbankAccount) {
            $invoices->where('bank_account_id', $request->exportbankAccount);
        }
//        if ($request->exportpaymentAt) {
//            $invoices->where('payment_at', MyHelpers::numberToEnglish($request->exportpaymentAt));
//        }
        if ($request->exportInvoiceTitle != 'receipt') {//agar resid nabood
            if ($request->exportStartDate) {
                $date = MyHelpers::numberToEnglish(explode('/', $request->exportStartDate));
                $year = $date[0];
                $month = $date[1];
                $day = $date[2];
                $date = JDF::jalali_to_gregorian($year, $month, $day, '-');
                $invoices->where('created_at', '>=', $date);
            }
            if ($request->exportEndDate) {
                $date = MyHelpers::numberToEnglish(explode('/', $request->exportEndDate));
                $year = $date[0];
                $month = $date[1];
                $day = $date[2];
                $date = JDF::jalali_to_gregorian($year, $month, $day, '-');
                $invoices->where('created_at', '<=', $date);
            }
        }

        if ($request->exportInvoiceTitle === 'pre-invoice') {
            $invoices = $invoices->where('payment_at', null)->orderBy('confirmed_at', 'DESC')->get();
            return Excel::download(new Transactions($invoices), 'invoices.xlsx');
        } elseif ($request->exportInvoiceTitle === 'receipt') {
            if ($request->exportStartDate) {
                $date = MyHelpers::numberToEnglish(explode('/', $request->exportStartDate));
                $date=implode('/',$date);
                $invoices->where('payment_at', '>=', $date);
            }
            if ($request->exportEndDate) {
                $date = MyHelpers::numberToEnglish(explode('/', $request->exportEndDate));
                $date=implode('/',$date);
                $invoices->where('payment_at', '<=', $date);
            }

            $invoices = $invoices->where('payment_at', '<>', null)->where('manager_confirmed_at', '<>', null)->orderBy('code', 'DESC')->get();
            return Excel::download(new Transactions($invoices), 'invoices.xlsx');
        } elseif ($request->exportInvoiceTitle === 'confirmed') {
            $invoices = Invoice::orderBy('code', 'desc')->where('status', 'published')
                ->where('invoice_title', 'receipt')->where('payment_at', '<>', null)
                ->where('manager_confirmed_at', null)
                ->where('manager_confirmed_at', null)->get();
            $type = 'confirmed';
            return Excel::download(new Transactions($invoices), 'invoices.xlsx');
        }
    }

    function editInvoice($id)
    {
        $invoice = Invoice::find($id);
        $banks = BankAccount::where('status', 'publish')->get();
        $date = explode('/', $invoice->payment_at);
        $paymentAt = JDF::jalali_to_gregorian($date[0], $date[1], $date[2], '/');
        return view('admin.financials.invoices.partials.edit', compact('invoice', 'banks', 'paymentAt'))->render();
    }

    function updateInvoiceManager(Request $request)
    {
        $invoice = Invoice::find($request->id);

        $invoice->invoice_type = $request->invoice_type;
        $invoice->payment_method = $request->payment_method;
        $invoice->bank_account_id = $request->bankAccount;
        $invoice->ir_amount = $request->ir_amount ?? 0;
        $invoice->euro_amount = $request->euro_amount;
        $invoice->discount_type = $request->discount_type;
        $invoice->discount_amount = $request->discount_amount ?? 0;
        $invoice->discount_description = $request->discount_description;
        $invoice->invoice_description = $request->invoice_description;
        $invoice->currency = $request->currency;

        if (isset($request->paymentAt))
            $invoice->payment_at = MyHelpers::numberToEnglish($request->paymentAt);
        $invoice->balance_amount = $request->balance ?? 0;

        if ($invoice->save())
            return 1;
        return 0;
    }

    function declineInvoice($id)
    {
        $invoice = Invoice::find($id);
        $invoice->payment_at = null;
        if ($invoice->save()) {
            session()->flash('success', 'رد فاکتور با موفقیت انجام گردید');
        } else {
            session()->flash('error', 'رد فاکتور رسید با شکست مواجه گردید');
        }
        return redirect()->back();
    }
}
