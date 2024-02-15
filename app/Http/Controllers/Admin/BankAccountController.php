<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    function bankAccounts(){
        $banks = BankAccount::all();
        return view('admin.financials.bank-accounts.index', compact('banks'));
    }

    function saveBankAccount(Request $request)
    {
        $bankAccount = new BankAccount();
        $bankAccount->status = $request->status;
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->shaba_number = $request->shaba_number;
        $bankAccount->card_number = $request->card_number;
        $bankAccount->account_name = $request->account_name;

        if($bankAccount->save())
            session()->flash('success', 'حساب بانکی با موفقیت ثبت گردید');
        else
            session()->flash('success', 'ثبت حساب بانکی با شکست مواجه گردید');

        return redirect()->back();
    }

    function editBankAccount($id){
        $bank = BankAccount::find($id);
        return view('admin.financials.bank-accounts.edit', compact('bank'))->render();
    }

    function updateBankAccount(Request $request){
        $bankAccount = BankAccount::find($request->id);
        $bankAccount->status = $request->status;
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->shaba_number = $request->shaba_number;
        $bankAccount->card_number = $request->card_number;
        $bankAccount->account_name = $request->account_name;

        if($bankAccount->save())
            return 1;
        else
            return 0;
    }
}
