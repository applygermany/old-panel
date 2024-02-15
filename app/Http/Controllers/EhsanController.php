<?php

namespace App\Http\Controllers;

use App\ExcelExports\Transactions;
use App\ExcelExports\Users;
use App\Exports\ContractExport;
use App\Exports\UserExport;
use App\Jobs\SendEmailWebinarJob;
use App\Jobs\SendEmailWebinarJobTest;
use App\Mail\SendEmail;
use App\Mail\WebinarEmail;
use App\Models\Acceptance;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Invoice;
use App\Models\Upload;
use App\Models\User;
use App\Models\UserComment;
use App\Models\UserSupervisor;
use App\Models\UserTelSupport;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Providers\SMS;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class EhsanController extends Controller
{
    function generateInvoiceCode()
    {
        $index = 1;
        $invoices = Invoice::orderBy('payment_at', 'asc')->where('invoice_title', 'receipt')->where('payment_at', '<>', null)->get();
        foreach ($invoices as $invoice) {
            $code = MyHelpers::numberToEnglish(JDF::jdate('Y'));
            if ($index > 999) {
                $code .= '' . $index;
            } elseif ($index > 99) {
                $code .= '0' . $index;
            } elseif ($index > 9) {
                $code .= '00' . $index;
            }
            if ($index <= 9) {
                $code .= '000' . $index;
            }
            $index++;

            $invoice->code = $code;
            $invoice->save();
        }
        dd('dfg');
    }

    function addTeam()
    {
        $users = User::whereIn('type', [2, 3])->get();
        foreach ($users as $user) {
            $support_id = null;
            $sup_id = 0;
            $sup = UserSupervisor::where('user_id', $user->id)->get();
            foreach ($sup as $item) {
                if ($item->supervisor->level === 2) {
                    $support_id = $item->supervisor;
                } elseif ($item->supervisor->level === 5) {
                    $sup_id = $item->supervisor->id;
                    break;
                }
            }

            if ($sup_id === 0 && $support_id != null) {
                $_sup = new UserSupervisor();
                $_sup->user_id = $user->id;
                $_sup->supervisor_id = $support_id->expert_id;
                $_sup->save();
            }
        }

        return "done";
    }

    function exportData()
    {
        $uploads = Upload::where('type', 7)->get();
        $uploadsData = new \stdClass();
        foreach ($uploads as $upload) {
            $std = new \stdClass();
            $std->firstName = $upload->user->firstname;
            $std->lastName = $upload->user->lastname;
            $std->mobile = $upload->user->mobile;
            $std->date = $upload->date;

            $uploadsData->data[] = $std;
        }

        return Excel::download(new ContractExport($uploadsData->data), 'contracts.xlsx');
    }

    function convertUser()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->firstname = $this->arabicToPersian($user->firstname);
            $user->lastname = $this->arabicToPersian($user->lastname);
            $user->email = strtolower($user->email);
            $user->save();
        }

        return true;
    }

    function arabicToPersian($string)
    {
        $characters = [
            'ك' => 'ک',
            'دِ' => 'د',
            'بِ' => 'ب',
            'زِ' => 'ز',
            'ذِ' => 'ذ',
            'شِ' => 'ش',
            'سِ' => 'س',
            'ى' => 'ی',
            'ي' => 'ی',
            '١' => '۱',
            '٢' => '۲',
            '٣' => '۳',
            '٤' => '۴',
            '٥' => '۵',
            '٦' => '۶',
            '٧' => '۷',
            '٨' => '۸',
            '٩' => '۹',
            '٠' => '۰',
        ];
        return str_replace(array_keys($characters), array_values($characters), $string);
    }

    function exportUser()
    {
        $categories = Category::Where('title', 'LIKE', "%وینتر 24%")
            ->orWhere('title', 'LIKE', "%وینتر 2024%")
            ->pluck('id');
        $comments = UserComment::Where('text', 'LIKE', "%وینتر 24%")
            ->orWhere('text', 'LIKE', "%وینتر 2024%")
            ->pluck('user_id');

        $users = User::whereIn('category_id', $categories)->orWhereIn('id', $comments)->get();

        $userArray = [];
        $index = 0;
        foreach ($users as $user) {
            //$contract = Upload::where('type', 7)->where('user_id', $user->id)->first();
            //if ($contract && ($contract->date >= '1401/10/15' || $contract->date <= '1402/04/15')) {
            $userArray[$index] = [
                'id' => $user->id,
                'FirstName' => $user->firstname,
                'LastName' => $user->lastname,
                'Mobile' => $user->mobile,
                'Email' => $user->email,
                'Package' => $user->type === 2 ? 'ویژه' : 'پایه',
                'admittance' => $user->acceptances->first() ? $user->acceptances->first()->admittance : '',
                'language' => $user->acceptances->first() ? $user->acceptances->first()?->language_favor : '',
                'field license' => $user->acceptances->first() ? $user->acceptances->first()->field_license : '',
                'average license' => $user->acceptances->first() ? $user->acceptances->first()->average_license : '',
                'createdAt' => $user->created_at,
                'date' => $contract->date
            ];
            $index++;
            //}
        }
        return Excel::download(new UserExport($userArray), 'users.xlsx');
    }

    function exportUser2()
    {
//        $time = strtotime('2023/05/31');
//        $dateStart = date('Y-m-d', $time);
//
//        $time = strtotime('2023/07/01');
//        $dateEnd = date('Y-m-d', $time);

        $time = strtotime('2023/03/21');
        $dateStart = date('Y-m-d', $time);

        $time = strtotime('2023/07/06');
        $dateEnd = date('Y-m-d', $time);

        $users = User::whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $userArray = [];
        $index = 0;
        foreach ($users as $item) {
            $comment = UserTelSupport::where('user_id', $item->id)->first();
            if (!$comment) {
                $userArray[$index] = [
                    'id' => $item->id,
                    'FirstName' => $item->firstname,
                    'LastName' => $item->lastname,
                    'Mobile' => $item->mobile,
                    'Email' => $item->email,
                    'Package' => $item->type === 2 ? 'ویژه' : ($item->type === 3 ? 'پایه' : 'عادی'),
                    'admittance' => $item->acceptances ? $item->acceptances->first() ? $item->acceptances->first()->admittance : '' : '',
                    'language' => $item->acceptances ? $item->acceptances->first() ? $item->acceptances->first()?->language_favor : '' : '',
                    'field license' => $item->acceptances ? $item->acceptances->first() ? $item->acceptances->first()->field_license : '' : '',
                    'average license' => $item->acceptances ? $item->acceptances->first() ? $item->acceptances->first()->average_license : '' : '',
                    'createdAt' => $item->created_at
                ];
                $index++;
            }
        }
        return Excel::download(new UserExport($userArray), 'users.xlsx');
    }

    function importDatabase()
    {
        $json = file_get_contents(public_path() . '/uploads/json/file.json');
        $data = json_decode($json);
        $array1 = (array)$data;

        foreach ($array1 as $data) {
            if (!Acceptance::find($data->id))
                Acceptance::create((array)$data);
        }
        return true;
    }

    function importUsersToDidar()
    {
        $users = User::where('level', 1)->get();
        $userArray = [];
        foreach ($users as $user) {
            $userArray[] = [
                'FirstName' => $user->firstname,
                'LastName' => $user->lastname,
                'MobilePhone' => $user->mobile,
                'Email' => $user->email,
                'Type' => 'Person',
                'DisplayName' => $user->firstname . ' ' . $user->lastname,
                'VisibilityType' => 'All',
                'Code' => $user->type,
                'Id' => $user->id,
                "Fields" => $user->type === 1 ? 'پایه' : ($user->type === 2 ? 'ویژه' : 'عادی')
            ];
        }
        return Excel::download(new UserExport($userArray), 'users.xlsx');
    }

    function noComment()
    {
        $users = User::orderBy('id', 'desc')->whereIn('type', ['2', '3'])
            ->where('level', 1)
            ->whereDate('created_at', '>=', DateTime::createFromFormat('Y-m-d h:i:s', '2022-09-22 00:00:00'))
            ->pluck('id');
        $comments = UserComment::whereIn('user_id', $users)->pluck('user_id');

        $users = User::orderBy('id', 'desc')
            ->whereIn('type', ['2', '3'])
            ->where('level', 1)
            ->whereNotIn('id', $comments)
            ->whereDate('created_at', '>=', DateTime::createFromFormat('Y-m-d h:i:s', '2022-09-22 00:00:00'))
            ->get();

        $userArray = [];
        foreach ($users as $user) {
            $userArray[] = [
                'FirstName' => $user->firstname,
                'LastName' => $user->lastname,
                'Mobile' => $user->mobile,
                "package" => $user->type === 2 ? 'ویژه' : ($user->type === 3 ? 'پایه' : ''),
                'language' => $user->acceptances[0]->language_favor,
                'admittance' => $user->acceptances[0]->admittance,
                'field_license' => $user->acceptances[0]->field_license,
                'average_license' => $user->acceptances[0]->average_license,
            ];
        }
        return Excel::download(new UserExport($userArray), 'users.xlsx');
    }

    function generateContractCode()
    {

        $contracts = Upload::orderBy('date', 'asc')->where('type', 7)->get();
        //$lastContractCodes = User::orderBy('id', 'asc')->whereIn('id', $contracts->pluck('user_id'))->get();

        $counter = 1;
        foreach ($contracts as $contract) {
            $counter = $counter + 1;
            $numLength = strlen((string)($counter));

            $number = '';
            if ($numLength === 1)
                $number .= '0000' . $counter;
            elseif ($numLength === 2)
                $number .= '000' . $counter;
            elseif ($numLength === 3)
                $number .= '00' . $counter;
            elseif ($numLength === 4)
                $number .= '0' . $counter;
            else
                $number = $counter;

            $user = $contract->user;
            if ($user) {
                $user->contract_code = MyHelpers::numberToEnglish(explode('/', $contract->date)[0]) . $number;
                $user->save();
            }
        }

        $lastContractCodes = User::orderBy('contract_code', 'desc')->where('contract_code', '<>', 'null')->get();
        dd($lastContractCodes->pluck('contract_code'));
    }

    function sendEmail()
    {
        Mail::to("e.shams1374@gmail.com")
            ->send(new SendEmail('تست', "تست متن", public_path("uploads\\invoices\\67.pdf")));
    }

    function invoiceDescription()
    {
        $invoices = Invoice::where('invoice_description', '<>', null)->get();
        return Excel::download(new Transactions($invoices), 'invoices.xlsx');
    }

    function userNormal()
    {
        $startAt = Carbon::create(2023, 7, 6);
        $endAt = Carbon::create(2023, 8, 6);
        $userArray = [];
        $users = User::where('type', 1)->whereBetween('created_at', [$startAt, $endAt])->get();
        foreach ($users as $user) {
            $comment = UserComment::where('user_id', $user->id)->first();
            if (!$comment) {
                $telSupport = UserTelSupport::where('user_id', $user->id)->where('tel_date', '>', '2023-08-06')->first();
                if (!$telSupport) {
                    $userArray[] = [
                        'FirstName' => $user->firstname,
                        'LastName' => $user->lastname,
                        'Email' => $user->email,
                        'Mobile' => $user->mobile,
                        "package" => 'عادی',
                    ];
                }
            }
        }
        return Excel::download(new UserExport($userArray), 'users.xlsx');
    }

    function userDaily()
    {
        //for ($i = 7; $i <= 14; $i++) {
        $i = 7;
        $date = Carbon::create(2023, 8, $i);
        $userArray = [];
        $users = User::whereDate('created_at', $date)->get();
        foreach ($users as $user) {
            $userArray[] = [
                'FirstName' => $user->firstname,
                'LastName' => $user->lastname,
                'Email' => $user->email,
                'Mobile' => $user->mobile,
                "package" => $user->type === 1 ? 'عادی' : 'ویژه',
            ];
        }
        return Excel::download(new UserExport($userArray), 'all-users-' . $i . '-signup.xlsx');

        $userArray = [];
        $users = User::where('type', 1)->whereDate('created_at', $date)->get();
        foreach ($users as $user) {
            $userArray[] = [
                'FirstName' => $user->firstname,
                'LastName' => $user->lastname,
                'Email' => $user->email,
                'Mobile' => $user->mobile,
                "package" => 'عادی',
            ];
        }
        Excel::download(new UserExport($userArray), 'normal-users-' . $i . '-signup.xlsx');


        $userArray = [];
        $users = User::whereIn('type', [2, 3])->whereDate('created_at', $date)->get();
        foreach ($users as $user) {
            $userArray[] = [
                'FirstName' => $user->firstname,
                'LastName' => $user->lastname,
                'Email' => $user->email,
                'Mobile' => $user->mobile,
                "package" => 'ویژه',
            ];
        }
        Excel::download(new UserExport($userArray), 'special-users-' . $i . '-signup.xlsx');

        $userArray = [];
        $users = User::whereIn('type', [2, 3])->where('tel_support_flag', true)->whereDate('created_at', $date)->get();
        foreach ($users as $user) {
            $userArray[] = [
                'FirstName' => $user->firstname,
                'LastName' => $user->lastname,
                'Email' => $user->email,
                'Mobile' => $user->mobile,
                "package" => 'ویژه',
            ];
        }
        Excel::download(new UserExport($userArray), 'special-users-' . $i . '-tel-support.xlsx');

        $userArray = [];
        $users = User::where('type', 1)->where('tel_support_flag', true)->whereDate('created_at', $date)->get();
        foreach ($users as $user) {
            $userArray[] = [
                'FirstName' => $user->firstname,
                'LastName' => $user->lastname,
                'Email' => $user->email,
                'Mobile' => $user->mobile,
                "package" => 'عادی',
            ];
        }
        Excel::download(new UserExport($userArray), 'normal-users-' . $i . '-tel-support.xlsx');
        //}

        dd('end');

    }

    function userTelSupport()
    {
        $date = Carbon::create(2023, 7, 6);

        $unique = User::whereDate('created_at', '>=', $date)->get()->unique(['email']);
        $users = User::whereDate('created_at', '>=', $date)->where('type', 1)->get()->diff($unique);
        $userArray = [];
        foreach ($users as $user) {
            $telSupport = UserTelSupport::where('user_id', $user->id)->first();
            if (!$telSupport) {
                $comment = UserComment::where('user_id', $user->id)->first();
                if (!$comment) {
                    $userArray[] = [
                        'Id' => $user->id,
                        'FirstName' => $user->firstname,
                        'LastName' => $user->lastname,
                        'Email' => $user->email,
                        'Mobile' => $user->mobile,
                        "package" => 'عادی',
                    ];
                }
            }
        }
        return Excel::download(new UserExport($userArray), 'users.xlsx');
    }

    function bankInvoices()
    {
        $invoices = Invoice::where('payment_method', 'bank')->where('bank_account_id', null)->get();
        return Excel::download(new Transactions($invoices), 'invoices.xlsx');
    }

    function contractsSign()
    {
        $uploads = Upload::where('type', 7)->where('date', '>=', '1401/07/01')->where('date', '<=', '1402/05/31')->get();
        $userArray = [];
        foreach ($uploads as $upload) {
            $userArray[] = [
                'FirstName' => $upload->user->firstname,
                'LastName' => $upload->user->lastname,
                'MobilePhone' => $upload->user->mobile,
                'Email' => $upload->user->email,
                "Package" => $upload->user->type === 2 ? 'ویژه' : 'پایه',
                "date" => $upload->date,
                'Id' => $upload->user->id,
            ];
        }
        return Excel::download(new UserExport($userArray), 'users.xlsx');
    }

    function nullTelSupport()
    {
        $startDate = JDF::jalali_to_gregorian(1402, 5, 15, '-');
        $endDate = JDF::jalali_to_gregorian(1402, 6, 15, '-');
        $users = User::where('level', 1)->get();
        $userArray = [];
        foreach ($users as $user) {
            $telSupport = UserTelSupport::where('user_id', $user->id)->whereBetween('tel_date', [$startDate, $endDate])->first();
            if ($telSupport) {
                $comment = UserComment::where('user_id', $user->id)->first();
                if (!$comment) {
                    $userArray[] = [
                        'FirstName' => $user->firstname,
                        'LastName' => $user->lastname,
                        'MobilePhone' => $user->mobile,
                        'Email' => $user->email,
                        "Package" => $user->type === 2 ? 'ویژه' : ($user->type === 3 ? 'پایه' : 'عادی'),
                        'Id' => $user->id,
                        'status' => 'No comment'
                    ];
                }
            } else {
                $userArray[] = [
                    'FirstName' => $user->firstname,
                    'LastName' => $user->lastname,
                    'MobilePhone' => $user->mobile,
                    'Email' => $user->email,
                    "Package" => $user->type === 2 ? 'ویژه' : ($user->type === 3 ? 'پایه' : 'عادی'),
                    'Id' => $user->id,
                    'status' => 'No tell support'
                ];
            }
        }

        return Excel::download(new UserExport($userArray), 'none-tel-support.xlsx');
    }

    function nullTelSupport2()
    {
        $startDate = JDF::jalali_to_gregorian(1402, 5, 15, '-');
        $endDate = JDF::jalali_to_gregorian(1402, 6, 15, '-');
        $endDate2 = JDF::jalali_to_gregorian(1402, 7, 6, '-');
        $users = User::where('level', 1)->where('unable_to_work', false)->whereIn('type', [1])->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate2)->get();
        $userArray = [];
        foreach ($users as $user) {
            $telSupport = UserTelSupport::where('user_id', $user->id)->where('tel_date', '>=', $startDate)->where('tel_date', '<=', $endDate2)->first();
            if ($telSupport) {
                $comment = UserComment::where('user_id', $user->id)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate2)->first();
                if (!$comment) {
                    $userArray[] = [
                        'FirstName' => $user->firstname,
                        'LastName' => $user->lastname,
                        'MobilePhone' => $user->mobile,
                        'Email' => $user->email,
                        "Package" => $user->type === 2 ? 'ویژه' : ($user->type === 3 ? 'پایه' : 'عادی'),
                        'Id' => $user->id,
                        'status' => 'No comment',
                        'createdAt' => $user->created_at
                    ];
                }
            } else {
                $userArray[] = [
                    'FirstName' => $user->firstname,
                    'LastName' => $user->lastname,
                    'MobilePhone' => $user->mobile,
                    'Email' => $user->email,
                    "Package" => $user->type === 2 ? 'ویژه' : ($user->type === 3 ? 'پایه' : 'عادی'),
                    'Id' => $user->id,
                    'status' => 'No tell support',
                    'createdAt' => $user->created_at
                ];
            }
        }

        return Excel::download(new UserExport($userArray), 'none-tel-support-special.xlsx');
    }

    function teamAssign()
    {
        $date = Carbon::create(2023, 8, 1);
        $users = User::where('level', 1)->whereDate('created_at', '>=', $date)->get();
        foreach ($users as $user) {
            $supervisors = $user->supervisors;
            foreach ($supervisors as $sup) {
                $hasSup = false;
                $hasSupervisor = false;
                $ss = null;
                if ($sup->supervisor->level === 2) {
                    $hasSup = true;
                    $ss = $sup->supervisor;
                }
                if ($sup->supervisor->level === 5) {
                    $hasSupervisor = true;
                }
                if ($hasSup && !$hasSupervisor) {
                    $s = new UserSupervisor();
                    $s->user_id = $user->id;
                    $s->supervisor_id = $ss->expert_id;
                    $s->save();

                    $user = User::find($sup->supervisor->id);
                    $user->expert_assign_date = Carbon::now();
                    $user->save();
                }
            }
        }
        return true;
    }
    function updateMaxUniversity(){
        $users = User::where('type', 2)->get();
        foreach ($users as $user){
            $user->max_university_count = 8;
            $user->save();
        }
        return true;
    }
}
