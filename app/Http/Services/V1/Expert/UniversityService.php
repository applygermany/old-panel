<?php

namespace App\Http\Services\V1\Expert;

use App\Mail\MailVerificationCode;
use App\Models\ApplyLevel;
use App\Models\Invoice;
use App\Models\University;
use App\Models\User;
use App\Models\UserUniversity;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Providers\Notification;
use App\Providers\SMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UniversityService
{
    public function getAllUniversities(Request $request)
    {
        $select = "select `nag_universities`.`state` from `nag_universities`";
        $select .= " where `nag_universities`.`state` != ''";
        $select .= " group by `nag_universities`.`state`";
        $states = DB::select($select);
        $stateArray = [];
        foreach ($states as $state)
            array_push($stateArray, $state->state);

        $select = "select `nag_universities`.`city` from `nag_universities`";
        $select .= " where `nag_universities`.`city` != ''";
        $select .= " group by `nag_universities`.`city`";
        $cities = DB::select($select);
        $cityArray = [];
        foreach ($cities as $city)
            array_push($cityArray, $city->city);

        $select = "select `nag_universities`.`geographical_location` from `nag_universities`";
        $select .= " where `nag_universities`.`geographical_location` != ''";
        $select .= " group by `nag_universities`.`geographical_location`";
        $geographicalLocations = DB::select($select);

        $geographicalLocationArray = [];
        foreach ($geographicalLocations as $geographicalLocation)
            array_push($geographicalLocationArray, $geographicalLocation->geographical_location);

        $university = University::Query();
        if ($request->title)
            $university->whereRaw("UPPER(title) LIKE '%" . strtoupper(mb_convert_encoding($request->title, 'UTF-8')) . "%'");
        if ($request->state)
            $university->where('state', $request->state);
        if ($request->city)
            $university->where('city', $request->city);
        if ($request->geographicalLocation)
            $university->where('geographical_location', $request->geographicalLocation);
        if ($request->order) {
            if ($request->order == 'cityCrowd')
                $university->orderBy('city_crowd', 'DESC');
            if ($request->order == 'costLiving')
                $university->orderBy('cost_living', 'asc');
        }
        $universities = $university->get();
        return [$stateArray, $cityArray, $geographicalLocationArray, $universities];
    }

    public function submitUniversities(Request $request, User $user)
    {
        $logged_user = auth()->guard('api')->user();
        $universities = array_unique($request->universities);
        if ($logged_user->users()->where('user_id', $user->id)->first()) {
            foreach ($universities as $university) {
                $user->universities()->create([
                    'university_id' => $university,
                ]);
            }
            return 1;
        } else {
            return 0;
        }
    }

    public function updateMaxUniversityCount(Request $request)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $request->id)->first()) {
            $user = User::find($request->id);
            $user->max_university_count = $request->count;
            $user->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function getUniversities(User $user)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $user->id)->first()) {
            return $user->universities;
        } else {
            return 0;
        }
    }

    public function updateUniversity(UserUniversity $userUniversity, Request $request)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $userUniversity->user_id)->first()) {
            if ($request->field)
                $userUniversity->field = $request->field;
            else
                $userUniversity->field = NULL;
            $userUniversity->chance_getting = $request->chanceGetting;
            if ($request->description)
                $userUniversity->description = $request->description;
            else
                $userUniversity->description = NULL;
            $userUniversity->offer = $request->offer;
            if ($request->link)
                $userUniversity->link = $request->link;
            else
                $userUniversity->link = NULL;
            $userUniversity->status = 2;
            $userUniversity->save();
            $user = $userUniversity->user;

            $send = (new SMS())->sendVerification($user->mobile, "university_add", "name==" . $user->firstname . " " . $user->lastname);
            //$send = User::sendMail(new MailVerificationCode("university_add", [$user->firstname . " " . $user->lastname], "university_add"), $user->email);
            return 1;
        } else {
            return 0;
        }
    }

    public function deleteUserUniversity(UserUniversity $userUniversity)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $userUniversity->user_id)->first()) {

            $userUniversity->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function deleteUniversity(UserUniversity $userUniversity)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $userUniversity->user_id)->first()) {

            $userUniversity->status = 2;
            $userUniversity->save();
            $send = (new SMS())->sendVerification($userUniversity->user->mobile, "university_remove", "name==" . $userUniversity->user->firstname . " " . $userUniversity->user->lastname
                . "&university==" . $userUniversity->university->title);
//			$send = User::sendMail(new MailVerificationCode("university_remove", [
//				$userUniversity->user->firstname . " " . $userUniversity->user->lastname,
//				$userUniversity->university->title,
//			], "university_remove"), $userUniversity->user->email);
            $notif = (new Notification("university_remove", [
                $userUniversity->user->firstname . " " . $userUniversity->user->lastname,
                $userUniversity->university->title,
            ]))->send($userUniversity->user->id);
            return 1;
        } else {
            return 0;
        }
    }

    public function cloneUniversity(UserUniversity $userUniversity)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $userUniversity->user_id)->first()) {

            $newUserUniversity = new UserUniversity();
            $newUserUniversity->user_id = $userUniversity->user_id;
            $newUserUniversity->university_id = $userUniversity->university_id;
            $newUserUniversity->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function deleteAllUniversities(Request $request)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $request->id)->first()) {

            $user = User::find($request->id);
            $userUniversities = $user->universities()->get();
            foreach ($userUniversities as $userUniversity)
                $userUniversity->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function changeUniversityStatus(Request $request)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $request->id)->first()) {

            $user = User::find($request->id);
            $userUniversity = $user->universities()->find($request->universityId);
            if ($userUniversity) {
                $userUniversity->level_status = $request->status;
                $userUniversity->save();
            }
            $name = $userUniversity->user->firstname . " " . $userUniversity->user->lastname;
            $status = "";
            switch ($request->status) {
                case 1:
                    $status = "آپلود مدارک در سایت";
                    break;
                case 2:
                    $status = "در دست اپلای";
                    break;
                case 3:
                    $status = "ارسال مدارک به دانشگاه";
                    break;
                case 4:
                    $status = "بررسی توسط دانشگاه";
                    break;
                case 5:

//                    $applyLevels = UserUniversity::where('user_id', $user->id)->where('level_status', 5)->count();
//                    if ($applyLevels === 1) {
//                        $invoice = new Invoice();
//                        $invoice->user_id = $user->id;;
//                        $invoice->invoice_type = 'final';
//                        $invoice->invoice_title = 'pre-invoice';
//                        $invoice->payment_method = 'cash';
//                        $invoice->ir_amount = 0;
//                        $invoice->euro_amount = $user->type === 2 ? 850 : 800;
//                        $invoice->discount_amount = 0;
//                        $invoice->status = 'drafted';
//                        $invoice->code = MyHelpers::numberToEnglish(JDF::jdate('Y')) . '' . (Invoice::count() + 1);
//                        $invoice->payment_status = 'unpaid';
//                        $invoice->save();
//                    }

                    $send = (new SMS())->sendVerification($userUniversity->user->mobile, "university_accept", "name=={$name}&university==" . $userUniversity->university->title);
                    $send = User::sendMail(new MailVerificationCode("university_accept", [
                        $name,
                        $userUniversity->university->title,
                    ], "university_accept"), $userUniversity->user->email);
                    $notif = (new Notification("university_accept", [
                        $name,
                        $userUniversity->university->title,
                    ]))->send($userUniversity->user->id);

                    break;
                case 6:
//					$send = (new SMS())->sendVerification($userUniversity->user->mobile, "university_reject", "name=={$name}&university==" . $userUniversity->university->title);
                    $send = User::sendMail(new MailVerificationCode("university_reject", [
                        $name,
                        $userUniversity->university->title,
                    ], "university_reject"), $userUniversity->user->email);
                    $notif = (new Notification("university_reject", [
                        $name,
                        $userUniversity->university->title,
                    ]))->send($userUniversity->user->id);
                    break;
            }
            if ($status != "") {
                $send = (new SMS())->sendVerification($userUniversity->user->mobile, "uni_apply", "name=={$name}&university==" . $userUniversity->university->title . "&status==" . $status);
                $send = User::sendMail(new MailVerificationCode("uni_apply", [
                    $name,
                    $userUniversity->university->title,
                    $status,
                ], "uni_apply"), $userUniversity->user->email);
                $notif = (new Notification("uni_apply", [
                    $name,
                    $userUniversity->university->title,
                    $status,
                ]))->send($userUniversity->user->id);
            }
            return 1;
        }
        return 0;
    }
}
