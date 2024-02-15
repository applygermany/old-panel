<?php

namespace App\Http\Services\V1\User;

use App\Models\User;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Providers\Notification;
use App\Mail\MailVerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Models\UserExtraUniversity;


class UniversityService
{
    public function universities(Request $request)
    {
        $select = "select `nag_user_universities`.`field` from `nag_user_universities`";
        $select .= " inner join `nag_users` on `nag_user_universities`.`user_id` = `nag_users`.`id`";
        $select .= " inner join `nag_universities` on `nag_user_universities`.`university_id` = `nag_universities`.`id`";
        $select .= " where `nag_user_universities`.`field` != ''";
        $select .= " and `nag_user_universities`.`user_id` = " . auth()->guard('api')->id();
        $select .= " group by `nag_user_universities`.`field`";
        $fields = DB::select($select);
        $select = "select `nag_universities`.`state` from `nag_universities`";
        $select .= " inner join `nag_user_universities` on `nag_universities`.`id` = `nag_user_universities`.`university_id`";
        $select .= " inner join `nag_users` on `nag_user_universities`.`user_id` = `nag_users`.`id`";
        $select .= " where `nag_universities`.`state` != ''";
        $select .= " and `nag_user_universities`.`user_id` = " . auth()->guard('api')->id();
        $select .= " group by `nag_universities`.`state`";
        $states = DB::select($select);
        $select = "select `nag_universities`.`city` from `nag_universities`";
        $select .= " inner join `nag_user_universities` on `nag_universities`.`id` = `nag_user_universities`.`university_id`";
        $select .= " inner join `nag_users` on `nag_user_universities`.`user_id` = `nag_users`.`id`";
        $select .= " where `nag_universities`.`city` != ''";
        $select .= " and `nag_user_universities`.`user_id` = " . auth()->guard('api')->id();
        $select .= " group by `nag_universities`.`city`";
        $cities = DB::select($select);
        $select = "select `nag_universities`.`geographical_location` from `nag_universities`";
        $select .= " inner join `nag_user_universities` on `nag_universities`.`id` = `nag_user_universities`.`university_id`";
        $select .= " inner join `nag_users` on `nag_user_universities`.`user_id` = `nag_users`.`id`";
        $select .= " where `nag_universities`.`geographical_location` != ''";
        $select .= " and `nag_user_universities`.`user_id` = " . auth()->guard('api')->id();
        $select .= " group by `nag_universities`.`geographical_location`";
        $geographicalLocations = DB::select($select);
        $select = "select `nag_user_universities`.`user_id`,
`nag_user_universities`.`id`,
`nag_user_universities`.`university_id`,
`nag_user_universities`.`field`,
`nag_user_universities`.chance_getting,
`nag_user_universities`.`description`,
`nag_user_universities`.`offer`,
`nag_user_universities`.`link`,
`nag_user_universities`.`status`,
`nag_user_universities`.`level_status`,
`nag_universities`.`title`,
`nag_universities`.`city`,
`nag_universities`.`state`,
`nag_universities`.`geographical_location`,
`nag_universities`.`city_crowd`,
`nag_universities`.`cost_living`,
`nag_universities`.`updated_at`
from `nag_user_universities`";
        $select .= " inner join `nag_users` on `nag_user_universities`.`user_id` = `nag_users`.`id`";
        $select .= " inner join `nag_universities` on `nag_user_universities`.`university_id` = `nag_universities`.`id`";
        $select .= " where `nag_user_universities`.`user_id` = " . auth()->guard('api')->id();
        $select .= " and `nag_user_universities`.`status` != 3";
        if ($request->field)
            $select .= " and nag_user_universities.field = '" . $request->field . "'";
        if ($request->state)
            $select .= " and nag_universities.state = '" . $request->state . "'";
        if ($request->city)
            $select .= " and nag_universities.city = '" . $request->city . "'";
        if ($request->geographicalLocation)
            $select .= " and nag_universities.geographical_location = '" . $request->geographicalLocation . "'";
        if ($request->order) {
            if ($request->order == 'جمعیت' || $request->order == 'cityCrowd') {
                $select .= ' order by nag_universities.city_crowd desc';
            } elseif ($request->order == "هزینه های زندگی" || $request->order == 'costLiving') {
                $select .= ' order by nag_universities.cost_living asc';
            } elseif ($request->order == "احتمال اخذ پذیرش" || $request->order == 'chanceGetting') {
                $select .= ' order by nag_user_universities.chance_getting desc';
            }
        } else {
            $select .= ' order by nag_user_universities.offer desc';

        }
        $universities = DB::select($select);
        return [
            'fields' => $fields,
            'states' => $states,
            'cities' => $cities,
            'geographicalLocations' => $geographicalLocations,
            'universities' => $universities,
        ];
    }

    public function chooseUniversity(Request $request)
    {
        $all = auth()->guard('api')->user()->userUniversities()->where('status', 1)->count();
        if (auth()->guard('api')->user()->max_university_count <= $all){//extra universities
            $userUniversity = auth()->guard('api')->user()->userUniversities()->find($request->id);
            $extraUniversity=new UserExtraUniversity();
            $extraUniversity->user_id=$userUniversity->user->id;
            $extraUniversity->university_id=$userUniversity->university_id;
            $pricing=Pricing::first();
            $extraUniversity->extra_price_euro=$pricing->add_college_price;
            $extraUniversity->save();
//            if($extraUniversity->save())
                $userUniversity->status = 1;
//            if($userUniversity->save()){
//                return 1;
//            }
        }
        $userUniversity = auth()->guard('api')->user()->userUniversities()->find($request->id);
        if (!$userUniversity)
            return 0;
        $userUniversity->status = 1;
        if ($userUniversity->save()) {
            //to  expert
            $expert_id = 0;
            $experts = auth()->guard('api')->user()->supervisor()->get();
            foreach ($experts as $expert) {
                $e = $expert->supervisor;
                if ($e->level == 5 || $e->level === 2) {
                    $expert_id = $e->id;

                    $notif = (new Notification("expert_apply_bascket", [
                        $userUniversity->user->firstname . " " . $userUniversity->user->lastname,
                        $userUniversity->university->title,
                    ]))->send($expert_id);

                    $send = User::sendMail(new MailVerificationCode("expert_apply_bascket", [
                        $userUniversity->user->firstname . " " . $userUniversity->user->lastname,
                        $userUniversity->university->title,
                    ], "expert_apply_bascket"), $e->email);
                }
            }

            return 1;
        }
        return 0;
    }

    public function applyStatus()
    {
        $select = "select `nag_user_universities`.`user_id`,
`nag_user_universities`.`id`,
`nag_user_universities`.`university_id`,
`nag_user_universities`.`field`,
`nag_user_universities`.chance_getting,
`nag_user_universities`.`description`,
`nag_user_universities`.`offer`,
`nag_user_universities`.`link`,
`nag_user_universities`.`status`,
`nag_user_universities`.`level_status`,
`nag_universities`.`title`,
`nag_universities`.`city`,
`nag_universities`.`state`,
`nag_universities`.`geographical_location`,
`nag_universities`.`city_crowd`,
`nag_universities`.`cost_living`,
`nag_universities`.`updated_at`
from `nag_user_universities`";
        $select .= " inner join `nag_users` on `nag_user_universities`.`user_id` = `nag_users`.`id`";
        $select .= " inner join `nag_universities` on `nag_user_universities`.`university_id` = `nag_universities`.`id`";
        $select .= " where `nag_user_universities`.`user_id` = " . auth()->guard('api')->id();
        $select .= " and `nag_user_universities`.`status` = 1";
        $select .= ' order by nag_user_universities.id desc';
        $universities = DB::select($select);
        return $universities;
    }
}
