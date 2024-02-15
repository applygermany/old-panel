<?php

namespace App\ExcelExports;

use App\Models\User;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;

class Users implements FromView
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {

        $this->request->searchMobile = MyHelpers::numberToEnglish($this->request->searchMobile);
        if($this->request->users){
            $user=$this->request->users;
        }else{
            $user = User::Query();
        }
        if($this->request->searchType)
            $user->where('type',$this->request->searchType);
        if($this->request->searchFirstname)
            $user->where('firstname','LIKE','%'.$this->request->searchFirstname.'%');
        if($this->request->searchLastname)
            $user->where('lastname','LIKE','%'.$this->request->searchLastname.'%');
        if($this->request->searchPhone)
            $user->where('mobile','LIKE','%'.$this->request->searchPhone.'%');
        if($this->request->searchEmail)
            $user->where('email','LIKE','%'.$this->request->searchEmail.'%');

        if($this->request->fromdate != ""){
            $this->request->fromdate = JDF::jalali_to_gregorian_(MyHelpers::numberToEnglish($this->request->fromdate), "/");

            $user->where('created_at','>', $this->request->fromdate);
        }
        if($this->request->todate != ""){
            $this->request->todate = JDF::jalali_to_gregorian_(MyHelpers::numberToEnglish($this->request->todate), "/");
            $user->where('created_at','<', $this->request->todate);
        }



        if($this->request->users){
            return view('admin.exports.normalUsers', [
                'users' => $this->request->users
            ]);
        }else{
            $users = $user->where('level',1)->orderBy('id','DESC')->get();
            return view('admin.exports.users', [
                'users' => $users
            ]);
        }

    }
}