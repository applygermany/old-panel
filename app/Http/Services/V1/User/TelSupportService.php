<?php

namespace App\Http\Services\V1\User;

use App\Http\Resources\Expert\ExpertResource;
use App\Http\Resources\User\CommentResource;
use App\Http\Resources\User\FirstDayTelSupportsResource;
use App\Http\Resources\User\NextTelSupportResource;
use App\Mail\MailVerificationCode;
use App\Models\Acceptance;
use App\Models\Comment;
use App\Models\TelSupport;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserSupervisor;
use App\Models\UserTelSupport;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Providers\Notification;
use App\Providers\SMS;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\UserTelSupportInformation;

class TelSupportService
{
    public function telSupports()
    {
        $user = auth()->guard('api')->user();
        $superviserId = 0;
        foreach ($user->supervisors as $supervisor) {
            if ($supervisor->supervisor->level === 5) {
                $superviserId = $supervisor->supervisor->id;
                break;
            }
        }

        $select = "select `nag_tel_supports`.`user_id`,`nag_users`.`firstname`,`nag_users`.`updated_at`,`nag_users`.`lastname`,`nag_users`.`admin_permissions`,`nag_users`.`sup_level` from `nag_tel_supports`";
        $select .= " inner join `nag_users` on `nag_tel_supports`.`user_id` = `nag_users`.`id`";
        if ($superviserId > 0) {
            $select .= " where `nag_tel_supports`.`user_id` = " . $superviserId;
            $select .= " and `nag_users`.`status` = 1";
        } else {
            $select .= " where `nag_users`.`status` = 1";

            if ($user->sup_level === 'one') {
                $select .= " and `nag_users`.`sup_level` = 'both'";
                $select .= " or `nag_users`.`sup_level` = 'one'";
            } elseif ($user->sup_level === 'two') {
                $select .= " and `nag_users`.`sup_level` = 'both'";
                $select .= " or `nag_users`.`sup_level` = 'two'";
            }
        }
        $select .= " group by `nag_tel_supports`.`user_id`";
        $telSupports = DB::connection('mysql2')->select($select);
        return $telSupports;
    }

    public function expertTelSupports(Request $request)
    {
        $expert = User::find($request->id);

        $type = auth()->guard('api')->user()->type;
        if ($type > 1) {
            $type = 2;
        }

        $index = 0;
        $items = array();

        $begin = new \DateTime(Carbon::today()->addDays(1)->toDateTimeString());
        $end = new \DateTime(Carbon::today()->addDays(21)->toDateTimeString());
        $dateRange = CarbonPeriod::create($begin, $end);
        foreach ($dateRange->toArray() as $date) {
            $items[]["time"] = $date->format("Y-m-d");
        }

        $select = "select * from `nag_tel_supports`";
//        if ($expert->level == 3) {
//            $select .= " where `nag_tel_supports`.`type` = " . $type;
//            $select .= " and `nag_tel_supports`.`user_id` = " . $request->id;
//        } else {
//            $select .= " where `nag_tel_supports`.`user_id` = " . $request->id;
//        }
        $select .= " where `nag_tel_supports`.`user_id` = " . $request->id;

        foreach ($items as $item) {
            [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($item['time']));
            $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
            $dayInfo = JDF::jgetdate($timestamp);
            $items[$index]['week'] = $dayInfo['weekday'];
            $monthName = JDF::jdate_words(['mm' => $month]);
            $items[$index]['day'] = $day;
            $items[$index]['month'] = $monthName['mm'];
            $select2 = $select . " and `nag_tel_supports`.`day_tel` = '" . $item['time'] . "'";
            $fdTelSupports = DB::select($select2 . " order by TIME(from_time) asc");
            $items2 = array();
            foreach ($fdTelSupports as $fdTelSupport) {
                $dataArray = [
                    'id' => $fdTelSupport->id,
                    'title' => '',
                    'date' => $fdTelSupport->day_tel_fa,
                    'fromTime' => $fdTelSupport->from_time,
                    'toTime' => $fdTelSupport->to_time,
                    'price' => $fdTelSupport->price,
                    'reserved' => 2
                ];

                $telSupport = DB::select("select * from `nag_user_tel_supports` where tel_support_id = " . $fdTelSupport->id);
                if (count($telSupport) > 0) {
                    $dataArray['reserved'] = 1;
                    if ($telSupport[0]->user_id == auth()->guard('api')->id()) {
                        $dataArray['reserved'] = 3;
                        $dataArray['title'] = $telSupport[0]->title;
                    }
                }

                $items2[] = $dataArray;
            }
            $items[$index]["times"] = $items2;
            $index++;
        }

        $comments = Comment::where('owner', $request->id)->where('status', 1)->orderBy('id', 'DESC')->get();
        $comments = CommentResource::collection($comments);

        $nextTelSupport = NULL;
        $nextTelSupports = auth()->guard('api')->user()->userTelSupports()->where("supervisor_id", $request->id)->where('tel_date', '>=', date('Y-m-d'))->orderBy('tel_date', 'DESC')->get();
        foreach ($nextTelSupports as $snextTelSupport) {
            $nextTelSupport = $snextTelSupport;
            if ($snextTelSupport->telSupport->day_tel == date('Y-m-d'))
                if (strtotime(date('H:i')) > strtotime($snextTelSupport->telSupport->from_time))
                    $nextTelSupport = NULL;
        }

        return ['times' => $items, 'expert' => $expert,
            'comments' => $comments, 'nextTelSupport' => $nextTelSupport ? new NextTelSupportResource($nextTelSupport) : $nextTelSupport];
    }

    public function expertTelSupportsOld(Request $request)
    {
        $weeks = [];
        $days = [];
        $months = [];
        $date1 = date('Y-m-d');
        $date2 = date('Y-m-d', strtotime($date1 . "+1 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date2));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['z'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['z'] = $day;
        $months['z'] = $monthName['mm'];
        $date3 = date('Y-m-d', strtotime($date1 . "+2 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date3));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['o'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['o'] = $day;
        $months['o'] = $monthName['mm'];
        $date4 = date('Y-m-d', strtotime($date1 . "+3 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date4));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['t'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['t'] = $day;
        $months['t'] = $monthName['mm'];
        $date5 = date('Y-m-d', strtotime($date1 . "+4 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date5));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['th'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['th'] = $day;
        $months['th'] = $monthName['mm'];
        $date6 = date('Y-m-d', strtotime($date1 . "+5 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date6));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['f'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['f'] = $day;
        $months['f'] = $monthName['mm'];
        $date7 = date('Y-m-d', strtotime($date1 . "+6 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date7));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['fi'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['fi'] = $day;
        $months['fi'] = $monthName['mm'];
        $date8 = date('Y-m-d', strtotime($date1 . "+7 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date8));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['s'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['s'] = $day;
        $months['s'] = $monthName['mm'];
        $date9 = date('Y-m-d', strtotime($date1 . "+8 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date9));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['z1'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['z1'] = $day;
        $months['z1'] = $monthName['mm'];
        $date10 = date('Y-m-d', strtotime($date1 . "+9 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date10));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['o1'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['o1'] = $day;
        $months['o1'] = $monthName['mm'];
        $date11 = date('Y-m-d', strtotime($date1 . "+10 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date11));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['t1'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['t1'] = $day;
        $months['t1'] = $monthName['mm'];
        $date12 = date('Y-m-d', strtotime($date1 . "+11 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date12));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['th1'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['th1'] = $day;
        $months['th1'] = $monthName['mm'];
        $date13 = date('Y-m-d', strtotime($date1 . "+12 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date13));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['f1'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['f1'] = $day;
        $months['f1'] = $monthName['mm'];
        $date14 = date('Y-m-d', strtotime($date1 . "+13 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date14));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['fi1'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['fi1'] = $day;
        $months['fi1'] = $monthName['mm'];
        $date15 = date('Y-m-d', strtotime($date1 . "+14 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date15));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['z2'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['z2'] = $day;
        $months['z2'] = $monthName['mm'];
        $date16 = date('Y-m-d', strtotime($date1 . "+15 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date16));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['o2'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['o2'] = $day;
        $months['o2'] = $monthName['mm'];
        $date17 = date('Y-m-d', strtotime($date1 . "+16 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date17));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['t2'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['t2'] = $day;
        $months['t2'] = $monthName['mm'];
        $date18 = date('Y-m-d', strtotime($date1 . "+17 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date18));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['th2'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['th2'] = $day;
        $months['th2'] = $monthName['mm'];
        $date19 = date('Y-m-d', strtotime($date1 . "+18 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date19));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['f2'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['f2'] = $day;
        $months['f2'] = $monthName['mm'];
        $date20 = date('Y-m-d', strtotime($date1 . "+19 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date20));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['fi2'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['fi2'] = $day;
        $months['fi2'] = $monthName['mm'];
        $date21 = date('Y-m-d', strtotime($date1 . "+20 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date21));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['s2'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['s2'] = $day;
        $months['s2'] = $monthName['mm'];
        $date22 = date('Y-m-d', strtotime($date1 . "+21 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date22));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['z3'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['z3'] = $day;
        $months['z3'] = $monthName['mm'];
        $date23 = date('Y-m-d', strtotime($date1 . "+22 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date23));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['o3'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['o3'] = $day;
        $months['o3'] = $monthName['mm'];
        $expert = User::find($request->id);
        $select = "select * from `nag_tel_supports`";
        $type = auth()->guard('api')->user()->type;
        if ($type > 1) {
            $type = 2;
        }
        if ($expert->level == 3) {
            $select .= " where `nag_tel_supports`.`type` = " . $type;
            $select .= " and `nag_tel_supports`.`user_id` = " . $request->id;
        } else {
            $select .= " where `nag_tel_supports`.`user_id` = " . $request->id;
        }

        $select2 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date2 . "'";
        $select3 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date3 . "'";
        $select4 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date4 . "'";
        $select5 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date5 . "'";
        $select6 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date6 . "'";
        $select7 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date7 . "'";
        $select8 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date8 . "'";
        $select9 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date9 . "'";
        $select10 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date10 . "'";
        $select11 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date11 . "'";
        $select12 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date12 . "'";
        $select13 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date13 . "'";
        $select14 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date14 . "'";
        $select15 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date15 . "'";
        $select16 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date16 . "'";
        $select17 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date17 . "'";
        $select18 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date18 . "'";
        $select19 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date19 . "'";
        $select20 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date20 . "'";
        $select21 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date21 . "'";
        $fdTelSupports = DB::select($select2 . " order by TIME(from_time) asc");
        $sdTelSupports = DB::select($select3 . " order by TIME(from_time) asc");
        $tdTelSupports = DB::select($select4 . " order by TIME(from_time) asc");
        $fodTelSupports = DB::select($select5 . " order by TIME(from_time) asc");
        $fidTelSupports = DB::select($select6 . " order by TIME(from_time) asc");
        $sidTelSupports = DB::select($select7 . " order by TIME(from_time) asc");
        $sedTelSupports = DB::select($select8 . " order by TIME(from_time) asc");
        $fdTelSupports1 = DB::select($select9 . " order by TIME(from_time) asc");
        $sdTelSupports1 = DB::select($select10 . " order by TIME(from_time) asc");
        $tdTelSupports1 = DB::select($select11 . " order by TIME(from_time) asc");
        $fodTelSupports1 = DB::select($select12 . " order by TIME(from_time) asc");
        $fidTelSupports1 = DB::select($select13 . " order by TIME(from_time) asc");
        $sidTelSupports1 = DB::select($select14 . " order by TIME(from_time) asc");
        $sedTelSupports1 = DB::select($select15 . " order by TIME(from_time) asc");
        $fdTelSupports2 = DB::select($select16 . " order by TIME(from_time) asc");
        $sdTelSupports2 = DB::select($select17 . " order by TIME(from_time) asc");
        $tdTelSupports2 = DB::select($select18 . " order by TIME(from_time) asc");
        $fodTelSupports2 = DB::select($select19 . " order by TIME(from_time) asc");
        $fidTelSupports2 = DB::select($select20 . " order by TIME(from_time) asc");
        $sidTelSupports2 = DB::select($select21 . " order by TIME(from_time) asc");
        $comments = Comment::where('owner', $request->id)->where('status', 1)->orderBy('id', 'DESC')->get();
        $nextTelSupport = NULL;
        $nextTelSupports = auth()->guard('api')->user()->userTelSupports()->where("supervisor_id", $request->id)->where('tel_date', '>=', date('Y-m-d'))->orderBy('tel_date', 'DESC')->get();
        foreach ($nextTelSupports as $snextTelSupport) {
            $nextTelSupport = $snextTelSupport;
            if ($snextTelSupport->telSupport->day_tel == date('Y-m-d'))
                if (strtotime(date('H:i')) > strtotime($snextTelSupport->telSupport->from_time))
                    $nextTelSupport = NULL;
        }
        return [
            'expert' => $expert,
            'weeks' => $weeks,
            'days' => $days,
            'months' => $months,
            'firstDayTelSupports' => $fdTelSupports,
            'secondDayTelSupports' => $sdTelSupports,
            'thirdDayTelSupports' => $tdTelSupports,
            'fourthDayTelSupports' => $fodTelSupports,
            'fifthDayTelSupports' => $fidTelSupports,
            'sixthDayTelSupports' => $sidTelSupports,
            'seventhDayTelSupports' => $sedTelSupports,
            'firstDayTelSupports1' => $fdTelSupports1,
            'secondDayTelSupports1' => $sdTelSupports1,
            'thirdDayTelSupports1' => $tdTelSupports1,
            'fourthDayTelSupports1' => $fodTelSupports1,
            'fifthDayTelSupports1' => $fidTelSupports1,
            'sixthDayTelSupports1' => $sidTelSupports1,
            'seventhDayTelSupports1' => $sedTelSupports1,
            'firstDayTelSupports2' => $fdTelSupports2,
            'secondDayTelSupports2' => $sdTelSupports2,
            'thirdDayTelSupports2' => $tdTelSupports2,
            'fourthDayTelSupports2' => $fodTelSupports2,
            'fifthDayTelSupports2' => $fidTelSupports2,
            'sixthDayTelSupports2' => $sidTelSupports2,
            'comments' => $comments,
            'nextTelSupport' => $nextTelSupport,
        ];
    }

    public function expertTelSupportsById($id)
    {
        $weeks = [];
        $days = [];
        $months = [];
        $date1 = date('Y-m-d');
        $date2 = date('Y-m-d', strtotime($date1 . "+1 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date2));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['z'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['z'] = $day;
        $months['z'] = $monthName['mm'];
        $date3 = date('Y-m-d', strtotime($date1 . "+2 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date3));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['o'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['o'] = $day;
        $months['o'] = $monthName['mm'];
        $date4 = date('Y-m-d', strtotime($date1 . "+3 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date4));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['t'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['t'] = $day;
        $months['t'] = $monthName['mm'];
        $date5 = date('Y-m-d', strtotime($date1 . "+4 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date5));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['th'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['th'] = $day;
        $months['th'] = $monthName['mm'];
        $date6 = date('Y-m-d', strtotime($date1 . "+5 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date6));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['f'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['f'] = $day;
        $months['f'] = $monthName['mm'];
        $date7 = date('Y-m-d', strtotime($date1 . "+6 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date7));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['fi'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['fi'] = $day;
        $months['fi'] = $monthName['mm'];
        $date8 = date('Y-m-d', strtotime($date1 . "+7 day"));
        [$year, $month, $day] = explode('/', MyHelpers::dateToJalali2($date8));
        $timestamp = JDF::jmktime(0, 0, 0, $month, $day, $year);
        $dayInfo = JDF::jgetdate($timestamp);
        $weeks['s'] = $dayInfo['weekday'];
        $monthName = JDF::jdate_words(['mm' => $month]);
        $days['s'] = $day;
        $months['s'] = $monthName['mm'];
        $expert = User::find($id);
        $select = "select * from `nag_tel_supports`";

        $user = User::find(auth()->guard('api')->id());
        if ($expert->level === 3) {
            if ($user->type > 1) {
                $select .= " where `nag_tel_supports`.`type` = " . '2';
                $select .= " and `nag_tel_supports`.`user_id` = " . $id;
            } else {
                $select .= " where `nag_tel_supports`.`type` = " . '1';
                $select .= " and `nag_tel_supports`.`user_id` = " . $id;
            }
        } else {
            $select .= " where `nag_tel_supports`.`user_id` = " . $id;
        }
        $select2 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date2 . "'";
        $select3 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date3 . "'";
        $select4 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date4 . "'";
        $select5 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date5 . "'";
        $select6 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date6 . "'";
        $select7 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date7 . "'";
        $select8 = $select . " and `nag_tel_supports`.`day_tel` = '" . $date8 . "'";
        $fdTelSupports = DB::select($select2 . " order by TIME(from_time) asc");
        $sdTelSupports = DB::select($select3 . " order by TIME(from_time) asc");
        $tdTelSupports = DB::select($select4 . " order by TIME(from_time) asc");
        $fodTelSupports = DB::select($select5 . " order by TIME(from_time) asc");
        $fidTelSupports = DB::select($select6 . " order by TIME(from_time) asc");
        $sidTelSupports = DB::select($select7 . " order by TIME(from_time) asc");
        $sedTelSupports = DB::select($select8 . " order by TIME(from_time) asc");
        $comments = Comment::where('owner', $id)->where('status', 1)->orderBy('id', 'DESC')->get();
        $nextTelSupport = NULL;
        $nextTelSupports = auth()->guard('api')->user()->userTelSupports()->where("supervisor_id", $id)->where('tel_date', '>=', date('Y-m-d'))->orderBy('tel_date', 'DESC')->get();
        foreach ($nextTelSupports as $snextTelSupport) {
            $nextTelSupport = $snextTelSupport;
            if ($snextTelSupport->telSupport->day_tel == date('Y-m-d'))
                if (strtotime(date('H:i')) > strtotime($snextTelSupport->telSupport->from_time))
                    $nextTelSupport = NULL;
        }
        return [
            'expert' => $expert,
            'weeks' => $weeks,
            'days' => $days,
            'months' => $months,
            'firstDayTelSupports' => $fdTelSupports,
            'secondDayTelSupports' => $sdTelSupports,
            'thirdDayTelSupports' => $tdTelSupports,
            'fourthDayTelSupports' => $fodTelSupports,
            'fifthDayTelSupports' => $fidTelSupports,
            'sixthDayTelSupports' => $sidTelSupports,
            'seventhDayTelSupports' => $sedTelSupports,
            'comments' => $comments,
            'nextTelSupport' => $nextTelSupport,
        ];
    }

    public function chooseTelSupport(Request $request)
    {
        $telSupport = TelSupport::where('id', $request->id)->where('status', 1)->first();
        if (!$telSupport) {
            return ["success" => false];
        }
        $isReserved = UserTelSupport::where('tel_support_id', $request->id)->first();
        if ($isReserved) {
            return [
                "success" => false,
                "msg" => "این زمان توسط کاربر دیگری رزرو شده است",
            ];
        }
        $t = UserTelSupport::where(DB::raw("UNIX_TIMESTAMP(tel_date) "), ">", time())->where('user_id', auth()->guard('api')->id())->first();
        if ($t) {
            return [
                "success" => false,
                "msg" => "در حال حاضر شما یک تایم مشاوره فعال دارید",
                "t" => $t->tel_date . ' 23:59:00',
            ];
        }

        $user = User::find(auth()->guard('api')->id());
        if (!$user->mobile) {
            $user->mobile = $request->mobile;
        }
        if (!$user->email) {
            $user->email = $request->email;
        }

        $user->save();

        $userTelSupport = new UserTelSupport();
        $userTelSupport->tel_support_id = $telSupport->id;
        $userTelSupport->supervisor_id = $telSupport->user_id;
        if (auth()->guard('api')->user()->type > 1) {
            $userTelSupport->user_id = auth()->guard('api')->id();
            $userTelSupport->price = 0;
        } else {
            if ($telSupport->type == 1 && $user->tel_support_flag) {
                $userTelSupport->user_id = auth()->guard('api')->id();
                $userTelSupport->price = 0;
            } else {
                $userTelSupport->user_id = $telSupport->type == 2 ? auth()->guard('api')->id() : 0;
                $userTelSupport->price = $telSupport->price;
            }
        }

        $userTelSupport->tel_date = $telSupport->day_tel;
        $userTelSupport->title = $request->title;
        if ($userTelSupport->save()) {

            /**
             * Store tel support form information for normal users
             */
            if ($telSupport->type == 1) {
                $userTelSupportInformation = new UserTelSupportInformation();
                $userTelSupportInformation->user_id = auth()->guard('api')->id();
                $userTelSupportInformation->user_tel_support_information = $userTelSupport->id;
                $userTelSupportInformation->tel_support_id = $telSupport->id;
                $userTelSupportInformation->title = $request->title;
                $userTelSupportInformation->military = $request->military;
                $userTelSupportInformation->mobile = $request->mobile;
                $userTelSupportInformation->email = $request->email;
                $userTelSupportInformation->birthDate = $request->birthDate;
                $userTelSupportInformation->language = $request->language;
                $userTelSupportInformation->languageDocument = $request->languageDocument;
                $userTelSupportInformation->grade = $request->grade;
                $userTelSupportInformation->save();
            }

            if (($telSupport->type == 1 && $user->tel_support_flag) || auth()->guard('api')->user()->type > 1) {
                $user->tel_support_flag = false;
                $user->save();

                $user = $userTelSupport->user;
                $name = $user->firstname . " " . $user->lastname;
                //to user
                $send = (new SMS())->sendVerification($user->mobile, "tel_reserved", "name=={$name}&date==" . $userTelSupport->telSupport->day_tel_fa . "&clock_1==" . $userTelSupport->telSupport->from_time .
                    "&clock_2==" . $userTelSupport->telSupport->to_time . "&mobile_supervisor==" . $userTelSupport->supervisor->mobile);
                $send = User::sendMail(new MailVerificationCode("tel_reserved", [
                    $name,
                    $userTelSupport->telSupport->day_tel_fa,
                    $userTelSupport->telSupport->from_time,
                    $userTelSupport->telSupport->to_time,
                    $userTelSupport->supervisor->mobile,
                ], "tel_reserved"), $user->email);
                $notif = (new Notification("tel_reserved", [
                    $name,
                    $userTelSupport->telSupport->day_tel_fa,
                    $userTelSupport->telSupport->from_time,
                    $userTelSupport->telSupport->to_time,
                    $userTelSupport->supervisor->mobile,
                ]))->send($user->id);
                $tel = $userTelSupport->telSupport;

                //to  expert
                if ($tel->user->id !== 26) {
                    $notif = (new Notification("expert_tel_reserved", [
                        $tel->user->firstname,
                        $tel->day_tel_fa,
                        $tel->from_time,
                        $tel->to_time,
                    ]))->send($tel->user->id);

                    $send = User::sendMail(new MailVerificationCode("expert_tel_reserved", [
                        $tel->user->firstname,
                        $tel->day_tel_fa,
                        $tel->from_time,
                        $tel->to_time,
                    ], "expert_tel_reserved"), $tel->user->email);
                }

                return ["hash" => 0, "success" => true];
            }
            if ($telSupport->type == 2) {
                $user = $userTelSupport->user;
                $name = $user->firstname . " " . $user->lastname;
                //to user
                $send = (new SMS())->sendVerification($user->mobile, "tel_reserved", "name=={$name}&date==" . $userTelSupport->telSupport->day_tel_fa . "&clock_1==" . $userTelSupport->telSupport->from_time .
                    "&clock_2==" . $userTelSupport->telSupport->to_time . "&mobile_supervisor==" . $userTelSupport->supervisor->mobile);
                $send = User::sendMail(new MailVerificationCode("tel_reserved", [
                    $name,
                    $userTelSupport->telSupport->day_tel_fa,
                    $userTelSupport->telSupport->from_time,
                    $userTelSupport->telSupport->to_time,
                    $userTelSupport->supervisor->mobile,
                ], "tel_reserved"), $user->email);
                $notif = (new Notification("tel_reserved", [
                    $name,
                    $userTelSupport->telSupport->day_tel_fa,
                    $userTelSupport->telSupport->from_time,
                    $userTelSupport->telSupport->to_time,
                    $userTelSupport->supervisor->mobile,
                ]))->send($user->id);
                $tel = $userTelSupport->telSupport;

                //to  expert
                if ($tel->user->id !== 26) {
                    $notif = (new Notification("expert_tel_reserved", [
                        $tel->user->firstname,
                        $tel->day_tel_fa,
                        $tel->from_time,
                        $tel->to_time,
                    ]))->send($tel->user->id);
                    //                $send = User::sendMail(new MailVerificationCode("expert_tel_reserved", [
                    //                    $tel->user->firstname,
                    //                    $tel->day_tel_fa,
                    //                    $tel->from_time,
                    //                    $tel->to_time,
                    //                ], "expert_tel_reserved"), $tel->user->email);
                }

                return ["hash" => 0, "success" => true];
            }
            $invoice = new InvoiceService();
            return [
                "hash" => $invoice->goPay(auth()->guard('api')->id(), $telSupport->price, 6, $telSupport->id),
                "success" => true,
            ];
        }
        return ["success" => false];
    }

    public function updateTelSupport(Request $request)
    {
        $userTelSupport = UserTelSupport::where('tel_support_id', $request->id)->where('user_id', auth()->guard('api')->id())->first();
        if (!$userTelSupport)
            return 0;
        $userTelSupport->title = $request->title;
        if ($userTelSupport->save())
            return 1;
        return 2;
    }

    public function sendComment(Request $request)
    {
        $user = auth()->guard('api')->user();
        $create = Comment::create([
            'text' => $request->text,
            'author' => $user->id,
            'owner' => $request->expert_id,
            'score' => $request->score,
            'type' => 2,
            'status' => 1,
        ]);
        if ($create) {
            $client = new \GuzzleHttp\Client();
            try {

                $res = $client->request("POST", "https://chat.applygermany.net/deletenotification", [
                    'json' => [
                        'userid' => $request->userid,
                        'timestamp' => $request->timestamp,
                    ],
                    "headers" => [
                        "authentication" => "MVKI2w5oCRGKSoStcUih368ZT5VrktKGTMDj9YotxWPpcgS6FyEoWlUSMgilveXWlVJcrkwa9TznyP52e1WnpQf0jqshOSp66mhyYBmbr6tVklcDwU4y2XyuQgR",
                    ],
                ]);
            } catch (\Exception $e) {
                //echo $e->getMessage();
            }
        }
        return $create;
    }

    public function TelSupportTimer()
    {
        $client = new \GuzzleHttp\Client();
        $telSupports = TelSupport::where(DB::raw("UNIX_TIMESTAMP(day_tel)"), ">", time() - 86400)->where("user_id", ">", 0)->get();
        foreach ($telSupports as $telSupport) {

            if ((floor((time() - strtotime($telSupport->day_tel . " " . $telSupport->to_time)) / 300) == 0)) {
                $userTel = UserTelSupport::where('tel_support_id', $telSupport->id)->where("user_id", ">", "0")->first();
                $user = User::find($userTel->user_id);
                //if ($user->type == 1) {
                if ($userTel) {
                    $expert = User::find($userTel->supervisor_id);
                    $data = [
                        "user_id" => $userTel->user_id,
                        "tel_support_id" => $telSupport->id,
                        "expert_id" => $userTel->supervisor_id,
                        "expertId" => $userTel->supervisor_id,
                        "expert_name" => $expert->firstname . " " . $expert->lastname,
                        "expert_level" => $expert->level,
                        "expert_photo" => str_replace("api/", "", route('imageUser', [
                            'id' => $expert->id,
                            'ua' => strtotime($expert->updated_at),
                        ])),
                    ];
                    try {
                        echo $userTel->user_id . "<br>";
                        $res = $client->request("POST", "https://chat.applygermany.net/notification", [
                            'json' => [
                                'to' => $userTel->user_id,
                                'title' => "نظرسنجی",
                                'body' => "شما اخیرا یک جلسه مشاوره تلفنی داشتید. لطفا برای بهبود خدمات در نظرسنجی شرکت کنید.",
                                'arguments' => $data,
                            ],
                            "headers" => [
                                "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
                            ],
                        ]);
                        print_r($res);

                        $mail = new MailVerificationCode("tel_support_poll", [$user->firstname . ' ' . $user->lastname, $expert->firstname . " " . $expert->lastname,
                            "https://applygermany.net/user/poll/expert/" . $userTel->id], "tel_support_poll");
                        User::sendMail($mail, $user->email);

                        $name = $user->firstname . ' ' . $user->lastname;
                        $expertName = $expert->firstname . " " . $expert->lastname;
                        $link = "https://applygermany.net/user/poll/expert/" . $userTel->id;
                        $send = (new SMS())->sendVerification($user->mobile, "tel_support_poll", "name=={$name}&name2=={$expertName}&link=={$link}");

                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                }
                // }
            }
        }
    }

    public function cancelTelSupport(Request $request)
    {
        $userTelSupport = UserTelSupport::where('tel_support_id', $request->id)->where('user_id', auth()->guard('api')->id())->first();
        if (!$userTelSupport)
            return 0;
        $telSupport = TelSupport::where('id', $userTelSupport->tel_support_id)->first();
        $time2 = date('Y-m-d H:i:s', strtotime($telSupport->day_tel . " " . $telSupport->from_time . " -24 hour"));
        $time1 = new \DateTime(date('Y-m-d H:i:s'));
        $time2 = new \DateTime($time2);
        if ($time1 > $time2) {
            return 2;
        } else {
            DB::beginTransaction();
            try {
                $user = auth()->guard('api')->user();
                $user->charge += $telSupport->price;
                $user->save();
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $telSupport->price;
                $transaction->status = 1;
                $transaction->pay_type = 1;
                $transaction->save();
                $userTelSupport->delete();
                $telSupport->status = 1;
                $telSupport->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return 0;
            }
            return 1;
        }
    }


    function deleteExpiredUnreservedTimes()
    {
        $Usupps = UserTelSupport::where("user_id", "0")->where(DB::raw("UNIX_TIMESTAMP(tel_date)"), ">", time() + 60 * 1)->get();
        foreach ($Usupps as $Usupp) {
            $transaction = Transaction::where('associate_id', $Usupp->tel_support_id)->where('type', 6)->first();
            if ($transaction) {
                $transaction->delete();
            }

            $Usupp->delete();
        }
    }

    function notify24hRemainsOld()
    {
        $Usupps = UserTelSupport::where("user_id", ">", 0)->get();
        foreach ($Usupps as $UtelSupp) {
            $tel = $UtelSupp->telSupport;
            $strTime=$tel->day_tel . '' . $tel->from_time;
            $strToTime=strtotime($strTime);
            $strToTimeMinuten=($strToTime-time())/60;
            $roundAction=round(round(($strToTime - time()) / 60, 0) / 2, 0);
            if ($strToTimeMinuten < 120  and $tel->has_been_sent==0 and $strToTimeMinuten > 0) {
                $tel->has_been_sent =1;
                $tel->save();
                $user = $UtelSupp->user;
                $name = $user->firstname . " " . $user->lastname;
                //to user
                $send = (new SMS())->sendVerification($user->mobile, "tel_24h_remained", "name=={$name}&date==" . $tel->day_tel_fa . "&clock_1==" . $tel->from_time .
                    "&clock_2==" . $tel->to_time);
                $send = User::sendMail(new MailVerificationCode("tel_24h_remained", [
                    $name,
                    $tel->day_tel_fa,
                    $tel->from_time,
                    $tel->to_time,
                ], "tel_24h_remained"), $user->email);
                $notif = (new Notification("tel_24h_remained", [
                    $name,
                    $tel->day_tel_fa,
                    $tel->from_time,
                    $tel->to_time,
                ]))->send($user->id);

                //to  expert
                $sups = UserSupervisor::where('supervisor_id', '<>', 26)->where('user_id', $user->id)->get();
                foreach ($sups as $sup) {
                    if ($sup->supervisor->level === 5 || $sup->supervisor->level === 7) {
                        $notif = (new Notification("expert_tel_24h_remained", [
                            $sup->supervisor->firstname,
                            $tel->day_tel_fa,
                            $tel->from_time,
                            $tel->to_time,
                        ]))->send($sup->supervisor->id);

                        $send = User::sendMail(new MailVerificationCode("expert_tel_24h_remained", [
                            $sup->supervisor->firstname,
                            $tel->day_tel_fa,
                            $tel->from_time,
                            $tel->to_time,
                        ], "expert_tel_24h_remained"), $sup->supervisor->email);
                    }
                }
            }
        }
    }
    function notify24hRemains()
    {
        $Usupps = UserTelSupport::where("user_id", ">", 0)->get();
        foreach ($Usupps as $UtelSupp) {
            $tel = $UtelSupp->telSupport;
            $strTime = $tel->day_tel . '' . $tel->from_time;
            $strToTime = strtotime($strTime);
            $strToTimeMinuten = ($strToTime - time()) / 60;
            $roundAction = round(round(($strToTime - time()) / 60, 0) / 2, 0);
            if (($strToTimeMinuten < 120 and $tel->has_been_sent != 1 and $strToTimeMinuten > 0) || ($strToTimeMinuten < 1440 and $tel->has_been_sent != 2 and $strToTimeMinuten  > 120)) {
                if($strToTimeMinuten < 120){
                    $tel->has_been_sent = 1;
                }else{
                    $tel->has_been_sent = 2;
                }

                $tel->save();
                $user = $UtelSupp->user;
                $name = $user->firstname . " " . $user->lastname;
                //to user
                $send = (new SMS())->sendVerification($user->mobile, "tel_24h_remained", "name=={$name}&date==" . $tel->day_tel_fa . "&clock_1==" . $tel->from_time .
                    "&clock_2==" . $tel->to_time);
                $send = User::sendMail(new MailVerificationCode("tel_24h_remained", [
                    $name,
                    $tel->day_tel_fa,
                    $tel->from_time,
                    $tel->to_time,
                ], "tel_24h_remained"), $user->email);
                $notif = (new Notification("tel_24h_remained", [
                    $name,
                    $tel->day_tel_fa,
                    $tel->from_time,
                    $tel->to_time,
                ]))->send($user->id);

                //to  expert
                $sups = UserSupervisor::where('supervisor_id', '<>', 26)->where('user_id', $user->id)->get();
                foreach ($sups as $sup) {
                    if ($sup->supervisor->level === 5 || $sup->supervisor->level === 7) {
                        $notif = (new Notification("expert_tel_24h_remained", [
                            $sup->supervisor->firstname,
                            $tel->day_tel_fa,
                            $tel->from_time,
                            $tel->to_time,
                        ]))->send($sup->supervisor->id);

                        $send = User::sendMail(new MailVerificationCode("expert_tel_24h_remained", [
                            $sup->supervisor->firstname,
                            $tel->day_tel_fa,
                            $tel->from_time,
                            $tel->to_time,
                        ], "expert_tel_24h_remained"), $sup->supervisor->email);
                    }
                }
            }
        }
    }
}
