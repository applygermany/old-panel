<?php

namespace App\Http\Services\V1\Expert;

use App\Models\Acceptance;
use App\Models\Category;
use App\Models\TelSupport;
use App\Models\TelSupportTag;
use App\Models\Transaction;
use App\Models\Upload;
use App\Models\User;
use App\Models\UserSupervisor;
use App\Models\UserTelSupport;
use App\Models\UserTelSupportInformation;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TelSupportService
{
    public function getHistoryTel(Request $request)
    {
        $datefa = MyHelpers::numberToEnglish(JDF::jdate('Y-m-d'));
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . ' -7 day'));
        if ($request->day != 0)
            $date2 = date('Y-m-d', strtotime(date('Y-m-d') . ' -' . $request->day . ' day'));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2e = explode('-', $datefa2);
        $datefae = explode('-', $datefa);
        $telSupport = TelSupport::Query();
        if ($request->day != 0) {
            $telSupport->where('day_tel_fa', '<=', $datefae[0] . "-" . sprintf("%02d", $datefae[1]) . "-" . sprintf("%02d", $datefae[2]));
            $telSupport->where('day_tel_fa', '>=', $datefa2e[0] . "-" . sprintf("%02d", $datefa2e[1]) . "-" . sprintf("%02d", $datefa2e[2]));
        } else {
            $telSupport->where('day_tel_fa', '<=', $datefae[0] . "-" . sprintf("%02d", $datefae[1]) . "-" . sprintf("%02d", $datefae[2]));
        }
        if (auth()->guard('api')->user()->level == 3) {
            if ($request->type != 0)
                $telSupport->where('type', $request->type);
        } else {
            $telSupport->where('type', 1);
        }

        if ($request->name) {
            //$telSupport->join('user_tel_supports', 'tel_supports.id', '=', 'user_tel_supports.tel_support_id');
            $telSupport->join('users', 'user_tel_supports.user_id', '=', 'users.id');
            $telSupport->where(function ($query) use ($request) {
                $query->orWhereRaw("UPPER(`firstname`) LIKE '%" . strtoupper($request->name) . "%'");
                $query->orWhereRaw("UPPER(`lastname`) LIKE '%" . strtoupper($request->name) . "%'");
                $query->orWhere('users.mobile', 'LIKE', '%' . MyHelpers::numberToEnglish($request->name) . '%');
                $query->orWhere('users.email', 'LIKE', '%' . MyHelpers::numberToEnglish($request->name) . '%');
            });
        }

        $userTellSupports = UserTelSupport::where('supervisor_id', auth()->guard("api")->user()->id)->pluck('tel_support_id');
        $telSupports = $telSupport->select(
            'tel_supports.id',
            'tel_supports.user_id',
            'tel_supports.user_id',
            'tel_supports.day_tel',
            'tel_supports.day_tel_fa',
            'tel_supports.from_time',
            'tel_supports.to_time',
            'tel_supports.type',
            'tel_supports.status'
        )->orderBy('tel_supports.day_tel', 'DESC')
            ->whereIn('id', $userTellSupports)
            ->where("user_id", auth()->guard("api")->user()->id)->get();
        return [$telSupports, $datefa2, $datefa];
    }

    public function getTelSupportData7Days()
    {
        $datefa = MyHelpers::numberToEnglish(JDF::jdate('Y-m-d'));
        $datefa = date("Y-m-d");
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . ' -7 day'));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $user = auth()->guard('api')->user();
        $todayTelSupports = $user->telSupports()->where('day_tel_fa', $datefa)->count();
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $normalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $specialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
 day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $allTelSupports = DB::connection('mysql2')->select($select);
        $array = [];
        $allTelSupportsArray = [];
        $allTelSupportsCount = 0;
        for ($i = 0; $i <= 6; $i++) {
            $date = date('Y-m-d', strtotime(date('Y-m-d') . ' -' . $i . ' day'));
            $date = explode('-', $date);
            $date = JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '-');
            array_push($array, $date);
        }
        foreach ($array as $sarray)
            $allTelSupportsArray[$sarray] = 0;
        foreach ($allTelSupports as $telSupport) {
            foreach ($array as $sarray) {
                if ($sarray == $telSupport->day_tel_fa) {
                    $allTelSupportsCount += $telSupport->total;
                    $allTelSupportsArray[$sarray] = $telSupport->total;
                }
            }
        }
        $array = [];
        $specialTelSupportsArray = [];
        $specialTelSupportsCount = 0;
        for ($i = 0; $i <= 6; $i++) {
            $date = date('Y-m-d', strtotime(date('Y-m-d') . ' -' . $i . ' day'));
            $date = explode('-', $date);
            $date = JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '-');
            array_push($array, $date);
        }
        foreach ($array as $sarray)
            $specialTelSupportsArray[$sarray] = 0;
        foreach ($specialTelSupports as $telSupport) {
            foreach ($array as $sarray) {
                if ($sarray == $telSupport->day_tel_fa) {
                    $specialTelSupportsCount += $telSupport->total;
                    $specialTelSupportsArray[$sarray] = $telSupport->total;
                }
            }
        }
        $array = [];
        $normalTelSupportsArray = [];
        $normalTelSupportsCount = 0;
        for ($i = 0; $i <= 6; $i++) {
            $date = date('Y-m-d', strtotime(date('Y-m-d') . ' -' . $i . ' day'));
            $date = explode('-', $date);
            $date = JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '-');
            array_push($array, $date);
        }
        foreach ($array as $sarray)
            $normalTelSupportsArray[$sarray] = 0;
        foreach ($normalTelSupports as $telSupport) {
            foreach ($array as $sarray) {
                if ($sarray == $telSupport->day_tel_fa) {
                    $normalTelSupportsCount += $telSupport->total;
                    $normalTelSupportsArray[$sarray] = $telSupport->total;
                }
            }
        }
        return [
            $datefa,
            $todayTelSupports,
            $allTelSupportsCount,
            $specialTelSupportsCount,
            $normalTelSupportsCount,
            $normalTelSupportsArray,
            $specialTelSupportsArray,
            $allTelSupportsArray,
        ];
    }

    public function getTelSupportDataMonth()
    {
        //        $datefa = MyHelpers::numberToEnglish(JDF::jdate('Y-m-d'));
        $datefa = date("Y-m-d");
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . ' -7 day'));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fwNormalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fwSpecialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
 day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fwAllTelSupports = DB::connection('mysql2')->select($select);
        $datefa = date('Y-m-d', strtotime($datefa . " -7 day"));
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . " -14 day"));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $swNormalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $swSpecialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
 day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $swAllTelSupports = DB::connection('mysql2')->select($select);
        $datefa = date('Y-m-d', strtotime($datefa . " -14 day"));
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . " -21 day"));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $twNormalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $twSpecialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
 day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $twAllTelSupports = DB::connection('mysql2')->select($select);
        $datefa = date('Y-m-d', strtotime($datefa . " -21 day"));
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . " -28 day"));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fowNormalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fowSpecialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select day_tel_fa,count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
 day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fowAllTelSupports = DB::connection('mysql2')->select($select);
        $allUsers = [0, 0, 0, 0];
        $normalUsers = [0, 0, 0, 0];
        $specialUsers = [0, 0, 0, 0];
        if (count($fwAllTelSupports) > 0)
            $allUsers[0] = $fwAllTelSupports[0]->total;
        if (count($fwNormalTelSupports) > 0)
            $normalUsers[0] = $fwNormalTelSupports[0]->total;
        if (count($fwSpecialTelSupports) > 0)
            $specialUsers[0] = $fwSpecialTelSupports[0]->total;
        if (count($swAllTelSupports) > 0)
            $allUsers[1] = $swAllTelSupports[0]->total;
        if (count($swNormalTelSupports) > 0)
            $normalUsers[1] = $swNormalTelSupports[0]->total;
        if (count($swSpecialTelSupports) > 0)
            $specialUsers[1] = $swSpecialTelSupports[0]->total;
        if (count($twAllTelSupports) > 0)
            $allUsers[2] = $twAllTelSupports[0]->total;
        if (count($twNormalTelSupports) > 0)
            $normalUsers[2] = $twNormalTelSupports[0]->total;
        if (count($twSpecialTelSupports) > 0)
            $specialUsers[2] = $twSpecialTelSupports[0]->total;
        if (count($fowAllTelSupports) > 0)
            $allUsers[3] = $fowAllTelSupports[0]->total;
        if (count($fowNormalTelSupports) > 0)
            $normalUsers[3] = $fowNormalTelSupports[0]->total;
        if (count($fowSpecialTelSupports) > 0)
            $specialUsers[3] = $fowSpecialTelSupports[0]->total;
        return ['allUsers' => $allUsers, 'normalUsers' => $normalUsers, 'specialUsers' => $specialUsers];
    }

    public function getTelSupportData3Month()
    {
        $datefa = MyHelpers::numberToEnglish(JDF::jdate('Y-m-d'));
        $datefa = date("Y-m-d");
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . ' -30 day'));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fmNormalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fmSpecialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
 day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fmAllTelSupports = DB::connection('mysql2')->select($select);
        $datefa = date('Y-m-d', strtotime($datefa . " -30 day"));
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . " -60 day"));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $smNormalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $smSpecialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
 day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $smAllTelSupports = DB::connection('mysql2')->select($select);
        $datefa = date('Y-m-d', strtotime($datefa . " -60 day"));
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . " -90 day"));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $tmNormalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $tmSpecialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
 day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $tmAllTelSupports = DB::connection('mysql2')->select($select);
        $allUsers = [0, 0, 0];
        $normalUsers = [0, 0, 0];
        $specialUsers = [0, 0, 0];
        if (count($fmAllTelSupports) > 0)
            $allUsers[0] = $fmAllTelSupports[0]->total;
        if (count($fmNormalTelSupports) > 0)
            $normalUsers[0] = $fmNormalTelSupports[0]->total;
        if (count($fmSpecialTelSupports) > 0)
            $specialUsers[0] = $fmSpecialTelSupports[0]->total;
        if (count($smAllTelSupports) > 0)
            $allUsers[1] = $smAllTelSupports[0]->total;
        if (count($smNormalTelSupports) > 0)
            $normalUsers[1] = $smNormalTelSupports[0]->total;
        if (count($smSpecialTelSupports) > 0)
            $specialUsers[1] = $smSpecialTelSupports[0]->total;
        if (count($tmAllTelSupports) > 0)
            $allUsers[2] = $tmAllTelSupports[0]->total;
        if (count($tmNormalTelSupports) > 0)
            $normalUsers[2] = $tmNormalTelSupports[0]->total;
        if (count($tmSpecialTelSupports) > 0)
            $specialUsers[2] = $tmSpecialTelSupports[0]->total;
        return ['allUsers' => $allUsers, 'normalUsers' => $normalUsers, 'specialUsers' => $specialUsers];
    }

    public function getTelSupportData6Month()
    {
        $datefa = MyHelpers::numberToEnglish(JDF::jdate('Y-m-d'));
        $datefa = date("Y-m-d");
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . ' -90 day'));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fmNormalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fmSpecialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $fmAllTelSupports = DB::connection('mysql2')->select($select);
        $datefa = date('Y-m-d', strtotime($datefa . " -30 day"));
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . " -60 day"));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $smNormalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $smSpecialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
 day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $smAllTelSupports = DB::connection('mysql2')->select($select);
        $datefa = date('Y-m-d', strtotime($datefa . " -60 day"));
        $date2 = date('Y-m-d', strtotime(date('Y-m-d') . " -90 day"));
        $date2 = explode('-', $date2);
        $datefa2 = MyHelpers::numberToEnglish(JDF::gregorian_to_jalali($date2[0], $date2[1], $date2[2], '-'));
        $datefa2 = explode('-', $datefa2);
        $datefa3 = explode('-', $datefa);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 1
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $tmNormalTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
type = 2
and day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $tmSpecialTelSupports = DB::connection('mysql2')->select($select);
        $select = "select count(id) as total
from nag_tel_supports
where 
user_id = " . auth()->guard("api")->user()->id . " and
 day_tel <= '" . $datefa3[0] . "-" . sprintf("%02d", $datefa3[1]) . "-" . sprintf("%02d", $datefa3[2]) . "'
and day_tel >= '" . $date2[0] . "-" . sprintf("%02d", $date2[1]) . "-" . sprintf("%02d", $date2[2]) . "'
group by day_tel_fa";
        $tmAllTelSupports = DB::connection('mysql2')->select($select);
        $allUsers = [0, 0, 0];
        $normalUsers = [0, 0, 0];
        $specialUsers = [0, 0, 0];
        if (count($fmAllTelSupports) > 0)
            $allUsers[0] = $fmAllTelSupports[0]->total;
        if (count($fmNormalTelSupports) > 0)
            $normalUsers[0] = $fmNormalTelSupports[0]->total;
        if (count($fmSpecialTelSupports) > 0)
            $specialUsers[0] = $fmSpecialTelSupports[0]->total;
        if (count($smAllTelSupports) > 0)
            $allUsers[1] = $smAllTelSupports[0]->total;
        if (count($smNormalTelSupports) > 0)
            $normalUsers[1] = $smNormalTelSupports[0]->total;
        if (count($smSpecialTelSupports) > 0)
            $specialUsers[1] = $smSpecialTelSupports[0]->total;
        if (count($tmAllTelSupports) > 0)
            $allUsers[2] = $tmAllTelSupports[0]->total;
        if (count($tmNormalTelSupports) > 0)
            $normalUsers[2] = $tmNormalTelSupports[0]->total;
        if (count($tmSpecialTelSupports) > 0)
            $specialUsers[2] = $tmSpecialTelSupports[0]->total;
        return ['allUsers' => $allUsers, 'normalUsers' => $normalUsers, 'specialUsers' => $specialUsers];
    }

    public function getMonthTel()
    {
        $monthName = JDF::jdate('F');
        $datefa = MyHelpers::numberToEnglish(JDF::jdate('Y-m-d'));
        $datefa2 = date('Y-m-d', strtotime($datefa . " +30 day"));
        $datefae = explode('-', $datefa2);
        $datefa2e = explode('-', $datefa);
        $telSupports = auth()->guard('api')->user()->telSupports()->where('day_tel_fa', '<=', $datefae[0] . "-" . sprintf("%02d", $datefae[1]) . "-" . sprintf("%02d", $datefae[2]))->where('day_tel_fa', '>=', $datefa2e[0] . "-" . sprintf("%02d", $datefa2e[1]) . "-" . sprintf("%02d", $datefa2e[2]))->orderBy('day_tel', 'ASC')->orderBy('from_time', 'ASC')->get();
        $allTelSupportsCount = auth()->guard('api')->user()->telSupports()->where('day_tel_fa', '<=', $datefae[0] . "-" . sprintf("%02d", $datefae[1]) . "-" . sprintf("%02d", $datefae[2]))->where('day_tel_fa', '>=', $datefa2e[0] . "-" . sprintf("%02d", $datefa2e[1]) . "-" . sprintf("%02d", $datefa2e[2]))->orderBy('day_tel', 'ASC')->count();
        $normalTelSupportsCount = auth()->guard('api')->user()->telSupports()->where('type', 1)->where('day_tel_fa', '<=', $datefae[0] . "-" . sprintf("%02d", $datefae[1]) . "-" . sprintf("%02d", $datefae[2]))->where('day_tel_fa', '>=', $datefa2e[0] . "-" . sprintf("%02d", $datefa2e[1]) . "-" . sprintf("%02d", $datefa2e[2]))->orderBy('day_tel', 'ASC')->count();
        $specialTelSupportsCount = auth()->guard('api')->user()->telSupports()->where('type', 2)->where('day_tel_fa', '<=', $datefae[0] . "-" . sprintf("%02d", $datefae[1]) . "-" . sprintf("%02d", $datefae[2]))->where('day_tel_fa', '>=', $datefa2e[0] . "-" . sprintf("%02d", $datefa2e[1]) . "-" . sprintf("%02d", $datefa2e[2]))->orderBy('day_tel', 'ASC')->count();

        $date = Carbon::now();
        $dateFa =  JDF::jdate('Y/m/d', strtotime($date));;
        $acceptances = Acceptance::whereDate('created_at', '<=', $date)->pluck('user_id');
        $suppervisors = UserSupervisor::wherein('user_id', $acceptances)
            ->where('supervisor_id', '<>', auth()->guard('api')->id())->pluck('user_id');


        $acceptances = Acceptance::whereDate('created_at', '<=', $date)->pluck('user_id');

        $_telSupports = UserTelSupport::select(DB::raw('DISTINCT user_id, COUNT(*) AS count'))
            ->whereIn('user_id', $acceptances)
            ->whereDate('created_at', '<=', $date)
            ->where('supervisor_id',  auth()->guard('api')->id())
            ->groupBy('user_id')
            ->get();

        $contracts = 0;
        foreach ($_telSupports as $telSupport) {
            $user = User::find($telSupport->user_id);
            $uploadContract = Upload::where('user_id', $user->id)->where('type', 7)->first();
            if ($uploadContract) {
                $contractDate = explode('/', $uploadContract->date);
                $contractDate = \App\Providers\JDF::jalali_to_gregorian($contractDate[0], $contractDate[1], $contractDate[2], '-');
                $firstSup = UserTelSupport::orderBy('id','asc')->where('user_id', $user->id)->where('tel_date', '<=', $contractDate)->first();
                if ($firstSup->supervisor_id === intval(auth()->guard('api')->id())) {
                    $contracts = $contracts + 1;
                }
            }
        }

        return [$monthName, $telSupports, $allTelSupportsCount, $normalTelSupportsCount, $specialTelSupportsCount, count($_telSupports), $contracts];
    }

    public function getTags()
    {
        $moneyTags = TelSupportTag::where('type', 2)->get();
        $dateTags = TelSupportTag::where('type', 1)->get();
        return [$moneyTags, $dateTags];
    }

    public function cancelUserTelSupport(TelSupport $telSupport)
    {
        if (auth()->guard('api')->user()->telSupports->contains('id', $telSupport->id)) {

            $time2 = strtotime($telSupport->day_tel . " " . $telSupport->from_time) - 24 * 60 * 60;
            $time1 = time();
            if ($time1 > $time2) {
                return 2;
            } else {
                DB::beginTransaction();
                try {
                    $userTelSupport = UserTelSupport::where('tel_support_id', $telSupport->id)->first();
                    $user = $userTelSupport->user;
                    $user->charge += $telSupport->price;
                    $user->save();
                    $transaction = new Transaction();
                    $transaction->user_id = $user->id;
                    $transaction->amount = $telSupport->price;
                    $transaction->status = 1;
                    $transaction->pay_type = 1;
                    $transaction->save();
                    UserTelSupport::where('tel_support_id', $telSupport->id)->first()->delete();
                    $telSupport->delete();
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return 0;
                }
                return 1;
            }
        } else {
            return 0;
        }
    }

    public function deleteTelSupport(TelSupport $telSupport)
    {
        if (auth()->guard('api')->user()->telSupports->contains('id', $telSupport->id)) {
            if ($telSupport->userTel !== NULL)
                return 2;
            return $telSupport->delete();
        } else {
            return 0;
        }
    }

    public function saveSession(Request $request)
    {
        $date = explode('-', $request->dayTel);
        $date = JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '-');
        if (TelSupport::where("from_time", $request->fromTime)
            ->where("to_time", $request->toTime)
            ->where("day_tel", $request->dayTel)
            ->where('user_id', auth()->guard('api')->id())->count()
        ) {
            return 2;
        }

        $telSupport = new TelSupport();
        $telSupport->user_id = auth()->guard('api')->id();
        $telSupport->day_tel = $request->dayTel;
        $telSupport->day_tel_fa = $date;
        $telSupport->from_time = $request->fromTime;
        $telSupport->to_time = $request->toTime;
        $telSupport->price = $request->price == "رایگان" ?
            0 : $request->price;
        $telSupport->type = $request->type;
        if ($telSupport->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function saveAutoSession(Request $request)
    {
        $date = explode('-', $request->dayTel);
        $startTime = explode(':', $request->fromTime);
        $date = JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '-');
        $usedTimes = [];
        $index = 0;

        $minIn24 = 60 * 24;
        $startInMin = (60 * intval($startTime[0])) + intval($startTime[1]);
        $useableTime = $minIn24 - $startInMin;
        $useableCount = $useableTime / intval($request->time);
        if ($useableCount < intval($request->count)) {
            return 0;//no free time with this count
        }

        $mm = [];
        $hour = intval($startTime[0]);
        $min = intval($startTime[1]);
        for ($i = 0; $i < intval($request->count); $i++) {
            if ($min === 60) {
                $hour++;
                $min = 0;
                $startTime = $hour . ':' . $min;
                $min = $min + intval($request->time);
                $endTime = $hour . ':' . $min;

                if (TelSupport::where("from_time", $startTime)
                    ->where("to_time", $endTime)
                    ->where("day_tel", $request->dayTel)
                    ->where('user_id', auth()->guard('api')->id())->count()
                ) {
                    $usedTimes[$index] = [
                        "startTime" => $startTime,
                        "endTime" => $endTime,
                    ];
                    $index++;
                } else {
                    $telSupport = new TelSupport();
                    $telSupport->user_id = auth()->guard('api')->id();
                    $telSupport->day_tel = $request->dayTel;
                    $telSupport->day_tel_fa = $date;
                    $telSupport->from_time = $startTime;
                    $telSupport->to_time = $endTime;
                    $telSupport->price = $request->price == "رایگان" ? 0 : $request->price;
                    $telSupport->type = $request->type;
                    $telSupport->save();
                }
            } elseif (($min + intval($request->time)) > 60) {
                $startTime = $hour . ':' . $min;
                $hour++;
                $minT = (-1) * (intval($request->time) - $min);
                $min = intval($request->time) - $minT;
                $endTime = $hour . ':' . $min;

                if (TelSupport::where("from_time", $startTime)
                    ->where("to_time", $endTime)
                    ->where("day_tel", $request->dayTel)
                    ->where('user_id', auth()->guard('api')->id())->count()
                ) {
                    $usedTimes[$index] = [
                        "startTime" => $startTime,
                        "endTime" => $endTime,
                    ];
                    $index++;
                } else {
                    $telSupport = new TelSupport();
                    $telSupport->user_id = auth()->guard('api')->id();
                    $telSupport->day_tel = $request->dayTel;
                    $telSupport->day_tel_fa = $date;
                    $telSupport->from_time = $startTime;
                    $telSupport->to_time = $endTime;
                    $telSupport->price = $request->price == "رایگان" ? 0 : $request->price;
                    $telSupport->type = $request->type;
                    $telSupport->save();
                }
            } elseif (($min + intval($request->time)) <= 60) {
                $startTime = $hour . ':' . $min;
                $min = $min + intval($request->time);

                if ($min === 60) {
                    $hour += 1;
                    $min = 0;
                }
                $endTime = $hour . ':' . $min;

                if (TelSupport::where("from_time", $startTime)
                    ->where("to_time", $endTime)
                    ->where("day_tel", $request->dayTel)
                    ->where('user_id', auth()->guard('api')->id())->count()
                ) {
                    $usedTimes[$index] = [
                        "startTime" => $startTime,
                        "endTime" => $endTime,
                    ];
                    $index++;
                } else {
                    $telSupport = new TelSupport();
                    $telSupport->user_id = auth()->guard('api')->id();
                    $telSupport->day_tel = $request->dayTel;
                    $telSupport->day_tel_fa = $date;
                    $telSupport->from_time = $startTime;
                    $telSupport->to_time = $endTime;
                    $telSupport->price = $request->price == "رایگان" ? 0 : $request->price;
                    $telSupport->type = $request->type;
                    $telSupport->save();
                }
            }
        }

        if ($useableTime) {
            return 1;
        } else {
            return 2;
        }
    }

    public function getComment()
    {
        return auth()->guard('api')->user()->ownerComments()->where('status', 1)->where('type', 2)->get();
    }
}
