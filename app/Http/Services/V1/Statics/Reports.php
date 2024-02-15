<?php

namespace App\Http\Services\V1\Statics;

use App\Models\Acceptance;
use App\Models\Category;
use App\Models\Factor;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\University;
use App\Models\Upload;
use App\Models\User;
use App\Models\UserComment;
use App\Models\UserSupervisor;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Reports
{
    public $monthTitle = [
        "فروردین",
        "اردیبهشت",
        "خرداد",
        "تیر",
        "مرداد",
        "شهریور",
        "مهر",
        "آبان",
        "آذر",
        "دی",
        "بهمن",
        "اسفند",
    ];

    public $acquaintedWays = [
        "تلگرام",
        "اینستاگرام",
        "یوتیوب",
        "تبلیغات",
        "معرفی آشنایان",
        "موتورهای جستجو",
        "سایر",
    ];
    public $transactionType = [
        "رزومه و انگیزه نامه",
        "تسویه نهایی",
        "مشاوره تلفنی",
        "سایر",
    ];

    function allSpecialContracts()
    {
        $users = User::where('type', 2)->where('status', 1)->pluck('id');
        return Upload::where('type', 7)->whereIn('user_id', $users)->count();
    }

    function allBaseContracts()
    {
        $users = User::where('type', 3)->where('status', 1)->pluck('id');
        return Upload::where('type', 7)->whereIn('user_id', $users)->count();
    }

    function normalUser()
    {
        return User::where('type', 1)->count();
    }

    function allUsers()
    {
        return User::all()->count();
    }

    function specialUser()
    {
        return User::where('type', 2)->count();
    }

    function baseUser()
    {
        return User::Where('type', 3)->count();
    }

    function transactionTypes()
    {
        $out = [];
        $transactions = Invoice::where("payment_status", 'paid')->get();
        foreach ($transactions as $transaction) {

            $type = '';
            if ($transaction->invoice_type === 'resume')
                $type = 0;
            elseif ($transaction->invoice_type === 'final')
                $type = 1;
            elseif ($transaction->invoice_type === 'tel-support')
                $type = 2;
            else
                $type = 3;

            $index = $this->transactionType[$type];
            if (isset($out[$index])) {
                $out[$index] += $transaction->final_amount_ir;
            } else {
                $out[$index] = $transaction->final_amount_ir;;
            }
        }

        return $out;
    }

    function acquainteds()
    {
        $out = [];
        foreach ($this->acquaintedWays as $acquaintedWay) {
            $out[$acquaintedWay] = User::where("acquainted_way", $acquaintedWay)->count();
        }
        return collect($out)->sort()->reverse();
    }

    function factor($status, $mode)
    {
        if ($mode == "sum") {
            if ($status == -1) {
                return Factor::sum('amount');
            }
            return Factor::where('status', $status)->sum('amount');
        } elseif ($mode == "count") {
            if ($status == -1) {
                return Factor::count();
            }
            return Factor::where('status', $status)->count();
        }
    }

    function university()
    {
        return University::count();
    }

    function acceptance()
    {
        $date = date("Y-m-d", strtotime(date('Y-m-d') . "0 days"));
        $todayAcceptances = Acceptance::whereDate('created_at', $date . ' 00:00:00')->where('last_form_submit', 1)->orderBy("id", "desc")->get();
        $date = date("Y-m-d", strtotime(date('Y-m-d') . " -1 days"));
        $dayAcceptances = Acceptance::whereDate('created_at', $date . ' 00:00:00')->where('last_form_submit', 1)->orderBy("id", "desc")->get();
        $date = date("Y-m-d", strtotime(date('Y-m-d') . " -7 days"));
        $weekAcceptances = Acceptance::whereDate('created_at', '>', $date . ' 00:00:00')->where('last_form_submit', 1)->orderBy("id", "desc")->get();
        $date = date("Y-m-d", strtotime(date('Y-m-d') . " -30 days"));
        $monthAcceptances = Acceptance::whereDate('created_at', '>', $date . ' 00:00:00')->where('last_form_submit', 1)->orderBy("id", "desc")->get();
        return [
            "today" => $todayAcceptances ?? [],
            "day" => $dayAcceptances ?? [],
            "week" => $weekAcceptances ?? [],
            "month" => $monthAcceptances ?? [],
        ];
    }

    function transaction($status, $mode)
    {
        if ($mode == "sum") {
            $trans_euro = 0;
            foreach (Invoice::where('ir_amount', '<>' , '0')->where('status', 'published')->where('payment_status', 'paid')->get() as $trans) {
                $trans_euro += $trans->final_amount_ir;
            }
            return $trans_euro;
        } elseif ($mode == "count") {
            if ($status == -1) {
                return Invoice::count();
            }
            return Invoice::where('payment_status', 'paid')->count();
        }
    }

    public function userChart($searchMonth = null)
    {
        if (!$searchMonth) {
            //last 12 month: 86400*12*30
            $time = 86400 * 12 * 30;
            $target = time() - $time;
            $users = DB::select("select * from nag_users where status = 1 and verified = 1 and level = 1 and unix_timestamp(created_at) > {$target} order by created_at desc");
            $month = [];
            foreach ($users as $user) {

                $num = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($user->created_at)))[1]);
                $year = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($user->created_at)))[0]);
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["normal_user"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["normal_user"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["special_user"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["special_user"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["base_user"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["base_user"] = 0;
                }
                if ($user->type == 1) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["normal_user"] += 1;
                } else if ($user->type == 2) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["special_user"] += 1;
                } else {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["base_user"] += 1;
                }
            }
            return array_reverse($month);
        } else {
            $time = 86400 * 12 * 30;
            $target = time() - $time;
            $users = DB::select("select * from nag_users where status = 1 and verified = 1 and level = 1 and unix_timestamp(created_at) > {$target} order by created_at desc");
            $month = [];
            for ($i = 30; $i >= 1; --$i) {
                if (!isset($month[$i]["normal_user"])) {
                    $month[$i]["normal_user"] = 0;
                }
                if (!isset([$i]["special_user"])) {
                    $month[$i]["special_user"] = 0;
                }
                if (!isset([$i]["base_user"])) {
                    $month[$i]["base_user"] = 0;
                }
            }
            foreach ($users as $user) {
                $num = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($user->created_at)))[1]);
                $day = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($user->created_at)))[2]);
                if ($this->monthTitle[$num - 1] === $searchMonth) {
                    for ($i = 30; $i >= 1; --$i) {
                        if ($i === intVal($day)) {
                            if ($user->type == 1) {
                                $month[$i]["normal_user"] += 1;
                            } else if ($user->type == 2) {
                                $month[$i]["special_user"] += 1;
                            } else {
                                $month[$i]["base_user"] += 1;
                            }
                        }
                    }
                }
            }

            return array_reverse($month);
        }
    }

    public function contractChart($searchMonth)
    {
        if (!$searchMonth) {
            $time = 86400 * 12 * 30;
            $target = time() - $time;
            $usersSpecial = User::where('status', 1)->where('verified', 1)->where('level', 1)->where('type', 2)->pluck('id');
            $usersBase = User::where('status', 1)->where('verified', 1)->where('level', 1)->where('type', 3)->pluck('id');
            $contracts = Upload::where('type', 7)->orderBy('date', 'desc')->get();
            $month = [];
            $month2 = [];
            foreach ($contracts->whereIn('user_id', $usersSpecial) as $contract) {
                $d = JDF::jalali_to_gregorian_($contract->date);
                $date = $d[0] . '/' . $d[1] . '/' . $d[2];
                if ((strtotime($date)) > $target) {
                    $num = MyHelpers::numberToEnglish(explode("/", $contract->date)[1]);
                    $year = MyHelpers::numberToEnglish(explode("/", $contract->date)[0]);
                    if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["special_contracts"])) {
                        $month[$this->monthTitle[$num - 1] . " " . $year]["special_contracts"] = 0;
                    }
                    $month[$this->monthTitle[$num - 1] . " " . $year]["special_contracts"] += 1;
                    $month2[] =$contract->user_id;
                }
            }
            foreach ($contracts->whereIn('user_id', $usersBase) as $contract) {
                $d = JDF::jalali_to_gregorian_($contract->date);
                $date = $d[0] . '/' . $d[1] . '/' . $d[2];
                if ((strtotime($date)) > $target) {
                    $num = MyHelpers::numberToEnglish(explode("/", $contract->date)[1]);
                    $year = MyHelpers::numberToEnglish(explode("/", $contract->date)[0]);
                    if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["base_contracts"])) {
                        $month[$this->monthTitle[$num - 1] . " " . $year]["base_contracts"] = 0;
                    }
                    $month[$this->monthTitle[$num - 1] . " " . $year]["base_contracts"] += 1;
                    $month2[] =$contract->user_id;
                }
            }
            return array_reverse($month);
        } else {
            $time = 86400 * 12 * 30;
            $target = time() - $time;
            $usersSpecial = User::where('status', 1)->where('verified', 1)->where('level', 1)->where('type', 2)->pluck('id');
            $usersBase = User::where('status', 1)->where('verified', 1)->where('level', 1)->where('type', 3)->pluck('id');
            $contracts = Upload::where('type', 7)->orderBy('date', 'desc')->get();
            $month = [];
            for ($i = 30; $i >= 1; --$i) {
                if (!isset($month[$i]["special_contracts"])) {
                    $month[$i]["special_contracts"] = 0;
                }
                if (!isset($month[$i]["base_contracts"])) {
                    $month[$i]["base_contracts"] = 0;
                }
            }

            foreach ($contracts->whereIn('user_id', $usersSpecial) as $contract) {
                $d = JDF::jalali_to_gregorian_($contract->date);
                $date = $d[0] . '/' . $d[1] . '/' . $d[2];
                if ((strtotime($date)) > $target) {
                    $num = MyHelpers::numberToEnglish(explode("/", $contract->date)[1]);
                    $day = MyHelpers::numberToEnglish(explode("/", $contract->date)[2]);
                    if ($this->monthTitle[$num - 1] === $searchMonth) {
                        for ($i = 30; $i >= 1; --$i) {
                            if ($i === intVal($day)) {
                                $month[$i]["special_contracts"] += 1;
                            }
                        }
                    }

                }
            }
            foreach ($contracts->whereIn('user_id', $usersBase) as $contract) {
                $d = JDF::jalali_to_gregorian_($contract->date);
                $date = $d[0] . '/' . $d[1] . '/' . $d[2];
                if ((strtotime($date)) > $target) {
                    $num = MyHelpers::numberToEnglish(explode("/", $contract->date)[1]);
                    $day = MyHelpers::numberToEnglish(explode("/", $contract->date)[2]);
                    if ($this->monthTitle[$num - 1] === $searchMonth) {
                        for ($i = 30; $i >= 1; --$i) {
                            if ($i === intVal($day)) {
                                $month[$i]["base_contracts"] += 1;
                            }
                        }
                    }
                }
            }
            return array_reverse($month);
        }
    }

    function transactionChart($searchMonth)
    {
        //last 12 month: 86400*12*30
        $time = 86400 * 12 * 30;
        $target = time() - $time;
        $transactions = DB::select("select * from nag_invoices where payment_status = 'paid' and unix_timestamp(created_at) > {$target} order by created_at desc");
        $month = [];
        if (!$searchMonth) {
            foreach ($transactions as $transaction) {

                $num = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($transaction->created_at)))[1]);
                $year = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($transaction->created_at)))[0]);
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["0"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["0"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["1"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["1"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["2"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["2"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["3"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["3"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["4"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["4"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["5"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["5"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["6"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["6"] = 0;
                }
                $month[$this->monthTitle[$num - 1] . " " . $year][strval($transaction->type)] += $transaction->amount;
            }
            return array_reverse($month);
        } else {
            for ($i = 30; $i >= 1; --$i) {
                if (!isset($month[$i]["0"])) {
                    $month[$i]["0"] = 0;
                }
                if (!isset($month[$i]["1"])) {
                    $month[$i]["1"] = 0;
                }
                if (!isset($month[$i]["2"])) {
                    $month[$i]["2"] = 0;
                }
                if (!isset($month[$i]["3"])) {
                    $month[$i]["3"] = 0;
                }
                if (!isset($month[$i]["4"])) {
                    $month[$i]["4"] = 0;
                }
                if (!isset($month[$i]["5"])) {
                    $month[$i]["5"] = 0;
                }
                if (!isset($month[$i]["6"])) {
                    $month[$i]["6"] = 0;
                }
            }
            foreach ($transactions as $transaction) {

                $num = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($transaction->created_at)))[1]);
                $day = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($transaction->created_at)))[2]);
                if ($this->monthTitle[$num - 1] === $searchMonth) {
                    for ($i = 30; $i >= 1; --$i) {
                        if ($i === intVal($day)) {
                            $month[$i][strval($transaction->type)] += $transaction->amount;
                        }
                    }
                }

            }
            return array_reverse($month);
        }
    }

    function acquaintedChart($searchMonth)
    {
        //last 12 month: 86400*12*30
        $time = 86400 * 12 * 30;
        $target = time() - $time;
        foreach ($this->acquaintedWays as $acquaintedWay) {
            $out[$acquaintedWay] = User::where("acquainted_way", $acquaintedWay)->count();
        }
        $users = DB::select("select * from nag_users where status = 1 and verified = 1 and level = 1 and unix_timestamp(created_at) > {$target} order by created_at desc");
        $month = [];
        if (!$searchMonth) {
            foreach ($users as $user) {

                $num = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($user->created_at)))[1]);
                $year = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($user->created_at)))[0]);
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["0"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["0"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["1"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["1"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["2"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["2"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["3"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["3"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["4"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["4"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["5"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["5"] = 0;
                }
                if (!isset($month[$this->monthTitle[$num - 1] . " " . $year]["6"])) {
                    $month[$this->monthTitle[$num - 1] . " " . $year]["6"] = 0;
                }
                $index = $this->indexOf($user->acquainted_way, $this->acquaintedWays);
                if ($index != -1) {
                    $month[$this->monthTitle[$num - 1] . " " . $year][strval($index)] += 1;
                }
            }
            return array_reverse($month);
        } else {
            for ($i = 30; $i >= 1; --$i) {
                if (!isset($month[$i]["0"])) {
                    $month[$i]["0"] = 0;
                }
                if (!isset($month[$i]["1"])) {
                    $month[$i]["1"] = 0;
                }
                if (!isset($month[$i]["2"])) {
                    $month[$i]["2"] = 0;
                }
                if (!isset($month[$i]["3"])) {
                    $month[$i]["3"] = 0;
                }
                if (!isset($month[$i]["4"])) {
                    $month[$i]["4"] = 0;
                }
                if (!isset($month[$i]["5"])) {
                    $month[$i]["5"] = 0;
                }
                if (!isset($month[$i]["6"])) {
                    $month[$i]["6"] = 0;
                }
            }
            foreach ($users as $user) {
                $num = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($user->created_at)))[1]);
                $day = MyHelpers::numberToEnglish(explode("-", JDF::jdate("Y-m-d", strtotime($user->created_at)))[2]);
                if ($this->monthTitle[$num - 1] === $searchMonth) {
                    for ($i = 30; $i >= 1; --$i) {
                        if ($i === intVal($day)) {
                            $index = $this->indexOf($user->acquainted_way, $this->acquaintedWays);
                            if ($index != -1) {
                                $month[$i][strval($index)] += 1;
                            }
                        }
                    }
                }
            }
            return array_reverse($month);
        }
    }

    function indexOf($needle, $array)
    {
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i] == $needle) {
                return $i;
            }
        }
        return -1;
    }
}
