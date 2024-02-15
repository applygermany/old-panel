<?php

namespace App\Http\Services\V1\User;

use App\Models\ResumeMotivationIds;
use App\Models\Upload;
use App\Models\User;
use App\Models\UserSupervisor;
use App\Providers\SMS;
use App\Models\Pricing;
use App\Models\Motivation;
use Illuminate\Http\Request;
use App\Providers\Notification;
use App\Mail\MailVerificationCode;
use Illuminate\Support\Facades\DB;
use App\Models\MotivationUniversity;
use Illuminate\Support\Facades\Mail;

class MotivationService
{
    public function motivation($id)
    {
        $motivation = auth()->guard('api')->user()->motivations()->where("status", ">", 0)->where("id", $id)->first();

        return $motivation;
    }

    public function motivations()
    {
        $motivations = auth()->guard('api')->user()->motivations()->orderBy('created_at', "DESC")->where("status", ">", 0)->get();
        foreach ($motivations as &$motivation) {
            $motivation->admin_attachment = json_decode($motivation->admin_attachment, true);
            if (!is_array($motivation->admin_attachment)) {
                $motivation->admin_attachment = [];
            }
        }

        return $motivations;
    }

    public function saveMotivation(Request $request)
    {
        $user = User::find(auth()->guard('api')->user()->id);
        $user->firstname_en = $request->motivation['name'];
        $user->lastname_en = $request->motivation['family'];
        if (!$user->email)
            $user->email = $request->motivation['email'];
        $user->save();

        $motivation = Motivation::find($request->motivation['id']);
        if (!$motivation) {
            $motivation = new Motivation();
            $motivation->user_id = auth()->guard('api')->id();
        }
        $to = 2;
        if (isset($request->motivation['to']) and $request->motivation['to'] == 'سفارت')
            $to = 1;
        $country = 2;
        if (isset($request->motivation['country']) and $request->motivation['country'] == 'ایران')
            $country = 1;
        $motivation->to = $to;
        $motivation->country = $country;
        $motivation->name = $request->motivation['name'];
        $motivation->family = $request->motivation['family'];
        $motivation->phone = $request->motivation['phone'];
        $motivation->birth_date = $request->motivation['birthDate'];
        $motivation->birth_place = $request->motivation['birthPlace'];
        $motivation->email = $request->motivation['email'];
        $motivation->address = $request->motivation['address'];
        $motivation->about = $request->motivation['about'];
        $motivation->resume = $request->motivation['resume'];
        $motivation->status =  auth()->guard('api')->user()->type == 2 ? 1 : 0;
        $motivation->why_germany = $request->motivation['whyGermany'];
        $motivation->after_graduation = $request->motivation['afterGraduation'];
        $motivation->extra_text = $request->motivation['extraText'];
        if ($motivation->save()) {
            foreach ($request->universities as $key => $university) {
                $muniversity = new MotivationUniversity();
                $muniversity->motivation_id = $motivation->id;
                $muniversity->name = $university['name'];
                $muniversity->field = $university['field'];
                $muniversity->grade = $university['grade'];
                $muniversity->language = $university['language'];
                $muniversity->text1 = $university['text1'];
                $muniversity->text2 = $university['text2'];
                $muniversity->save();
            }

            /**
             * Store motivation id in table
             */
            $storeId = new ResumeMotivationIds();
            $storeId->model_id = $motivation->id;
            $storeId->user_id = $motivation->user_id;
            $storeId->model_type = 'motivation';
            $storeId->save();

            if (auth()->guard('api')->user()->type == 2) {
                $send = (new SMS())->sendVerification($motivation->user->mobile, "motivation_added", "name=={$name}&order=={$motivation->id}");
                $send = User::sendMail(new MailVerificationCode("motivation_added", [
                    $name,
                    $motivation->id,
                ], "motivation_added"), $motivation->user->email);

                //to admin
                $name = $motivation->user->firstname . " " . $motivation->user->lastname;
                $order_admin = User::where('level', 4)->where("admin_permissions", "like", '%"orders":1%')->get();
                foreach ($order_admin as $admin) {
                    if($admin->email !='admin@applygermany.net')
                        try{
                            $send = User::sendMail(new MailVerificationCode("admin_motivation_added", [
                                $name,
                                $motivation->id,
                            ], "admin_motivation_added"), $admin->email);
                        }catch(\Exception $e){
                            continue;
                        }
                    $notif = (new Notification("admin_motivation_added", [$name, $motivation->id]))->send($admin->id);
                }
                $supps = UserSupervisor::where('user_id', $motivation->user_id)->get();
                foreach ($supps as $sup) {
                    if ($sup->supervisor->level === 2) {
                        try {
                            $send = User::sendMail(new MailVerificationCode("admin_motivation_added", [
                                $name,
                                $motivation->id,
                            ], "admin_motivation_added"), $sup->supervisor->email);
                        }catch(\Exception $e){
                            continue;
                        }
                        $notif = (new Notification("admin_motivation_added", [$name, $motivation->id]))->send($sup->supervisor->id);
                        break;
                    }
                }
                return [100, $motivation->id];
            }else {
                $invoice = new InvoiceService();
                return [
                    $action = $invoice->goPay($motivation->user_id, Pricing::find(1)->motivation_price + (count($request->universities) * Pricing::find(1)->extra_university), 3, $motivation->id),
                    $motivation->id,
                ];
            }
        }
        return 0;
    }

    public function updateMotivationExtra(Request $request)
    {
        $motivation = auth()->guard('api')->user()->motivations()->where('id', $request->id)->first();
        if ($motivation) {
            $motivation->edit_request = $request->extraText;
            $motivation->user_comment = $request->extraText;
            $motivation->status = 4;
            $motivation->is_accepted = false;
            $motivation->admin_accepted_filename = null;
            if ($motivation->save()) {

                $upload = Upload::where('type', 13)->where('text', $motivation->id)->delete();

                return 1;
            } else
                return 0;
        }
        return 0;
    }

    public function editMotivation(Request $request)
    {
        $motivation = auth()->guard('api')->user()->motivations()->where('id', $request->id)->first();
        if (!$motivation)
            return 0;
        if ($request->file('file')) {
            $folder = '/uploads/motivationUserFile/';
            $file = $request->file('file');
            $fileName = $this->getFileName(public_path() . $folder, $motivation->id);
            $file->move(public_path() . $folder, $fileName);
            $users = json_decode($motivation->url_uploaded_from_user, true);
            if (!is_array($users)) {
                $users = [];
            }
            $users[] = route('motivationUserFile', ["id" => str_replace('.pdf', '', $fileName)]);
            $motivation->url_uploaded_from_user = json_encode($users);
        }
        $motivation->edit_request = $request->editRequestText;
        $motivation->user_comment = $request->editRequestText;
        $motivation->is_accepted = false;
        $motivation->status = 4;
        $motivation->admin_accepted_filename = null;
        if ($motivation->save()) {

            $upload = Upload::where('type', 13)->where('text', $motivation->id)->delete();

            //to admin
            $name = $motivation->user->firstname . " " . $motivation->user->lastname;
            $order_admin = User::where('level', 4)->where("admin_permissions", "like", '%"orders":1%')->first();
            try{
                $send = User::sendMail(new MailVerificationCode("admin_motivation_edit_needed", [
                    $name,
                    $motivation->id,
                ], "admin_motivation_edit_needed"), $order_admin->email);
            }catch(\Exception $e){

            }
            
            $notif = (new Notification("admin_motivation_edit_needed", [
                $name,
                $motivation->id,
            ]))->send($order_admin->id);
            return 1;
        }
        return 0;
    }

    private function getFileName($string, $id, $index = 0)
    {
        if (is_file($string . $id . "_" . $index . '.pdf')) {
            return $this->getFileName($string, $id, $index + 1);
        }
        return $id . "_" . $index . '.pdf';
    }

    public function updateMotivation(Request $request)
    {
        $motivation = auth()->guard('api')->user()->motivations()->where('id', $request->motivation['id'])->first();
        if (!$motivation)
            return 0;
        $to = 2;
        if ($request->motivation['to'] == 'سفارت')
            $to = 1;
        $country = 2;
        if ($request->motivation['country'] == 'ایران')
            $country = 1;
        $motivation->to = $to;
        $motivation->country = $country;
        $motivation->name = $request->motivation['name'];
        $motivation->family = $request->motivation['family'];
        $motivation->phone = $request->motivation['phone'];
        $motivation->birth_date = $request->motivation['birthDate'];
        $motivation->birth_place = $request->motivation['birthPlace'];
        $motivation->email = $request->motivation['email'];
        $motivation->address = $request->motivation['address'];
        $motivation->about = $request->motivation['about'];
        $motivation->resume = $request->motivation['resume'];
        $motivation->why_germany = $request->motivation['whyGermany'];
        $motivation->after_graduation = $request->motivation['afterGraduation'];
        $motivation->extra_text = $request->motivation['extraText'];
        if ($motivation->save()) {
            foreach ($request->universities as $university) {
                $muniversity = $motivation->universities()->find($university['id']);
                $muniversity->name = $university['name'];
                $muniversity->field = $university['field'];
                $muniversity->grade = $university['grade'];
                $muniversity->language = $university['language'];
                $muniversity->text1 = $university['text1'];
                $muniversity->text2 = $university['text2'];
                $muniversity->save();
            }
            return 1;
        }
        return 0;
    }

    public function uploadResume(Request $request)
    {
        $motivation = auth()->guard('api')->user()->motivations()->where('id', $request->id)->first();
        if ($motivation) {
            if ($request->file('file')) {
                $folder = '/uploads/motivationUserFile/';
                $file = $request->file('file');
                $fileName = $this->getFileName(public_path() . $folder, $motivation->id);
                $file->move(public_path() . $folder, $fileName);
                $users = json_decode($motivation->url_uploaded_from_user, true);
                if (!is_array($users)) {
                    $users = [];
                }
                $users[] = route('motivationUserFile', ["id" => str_replace('.pdf', '', $fileName)]);
                $motivation->url_uploaded_from_user = json_encode($users);
                $motivation->save();
            }
            return 1;
        }
        return 1;
    }

    public function newMotivation()
    {
        $motivation = Motivation::where('user_id', auth()->guard('api')->id())->where("status", -1)->first();
        if (!$motivation) {
            $motivation = new Motivation();
            $motivation->user_id = auth()->guard('api')->id();
            $motivation->status = -1;
            $motivation->save();
        }
        return $motivation;
    }

    public function deletePDF(Request $request)
    {

        $file = 'uploads/motivationUserFile/' . $request->id . '_0.pdf';
        if (!unlink(public_path($file))) {
            return 0;
        }
        $resume = auth()->guard('api')->user()->motivations()->where("id", $request->id)->first();
        $resume->url_uploaded_from_user = json_encode([]);
        $resume->save();
        return 1;
    }

    public function universities()
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
        return count($universities) > 0;
    }
}
