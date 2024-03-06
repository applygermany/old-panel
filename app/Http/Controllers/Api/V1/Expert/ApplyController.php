<?php

namespace App\Http\Controllers\Api\V1\Expert;

use App\Mail\MailVerificationCode;
use App\Models\Motivation;
use App\Models\MotivationUniversity;
use App\Models\Resume;
use App\Models\ResumeMotivationIds;
use App\Models\University;
use App\Models\Upload;
use App\Models\UserSupervisor;
use App\Models\UserUniversity;
use App\Providers\MyHelpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\UserResource;
use App\Http\Resources\User\UniversitiesResource;
use App\Http\Services\V1\Expert\ApplyService;
use App\Models\User;
use App\Providers\Notification;
use App\Providers\SMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplyController extends Controller
{
    protected $applyService;

    public function __construct(ApplyService $applyService)
    {
        $this->applyService = $applyService;
    }

    public function modalTerminationByExpert(Request $request){
        $user=User::where('id',$request->user_id)->first();
        $user->category_id=0;
        $user->contract_type='gov-university';
        $user->upload_access=0;
        $user->type=1;
        $user->contract_code=null;
        $user->contract_open_id = null;
        if($user->save()){
            Upload::where("user_id", $request->user_id)->where('type', 7)->delete();
            $fullName=$user['firstname'].' '.$user['lastname'];
            try{
                User::sendMail(new MailVerificationCode("terminate_contract",[$fullName], "terminate_contract",public_path('uploads/contracts/termination.pdf')), $user->email);
            }catch(\Exception $e){

            }
            return ['msg'=>'قرارداد فسخ گردید !','type'=>'success'];
        }else{
            return ['msg'=>'خطایی رخ داد، مجدداً تلش نمایید.','type'=>'danger'];
        }
    }
    public function modalMotivationByExpert(Request $request){
        $oldMotive=Motivation::where('id',$request->motivation_id)->first();
        $motivation=new Motivation();
//      $motivation->extra_text=$request->description;
        $motivation->to = 1;
        $motivation->country = 1;
        $motivation->user_id = $request->user_id;
        $motivation->name = $oldMotive->name;
        $motivation->family = $oldMotive->family;
        $motivation->phone = $oldMotive->phone;
        $motivation->birth_date = $oldMotive->birth_date;
        $motivation->birth_place = $oldMotive->birth_place;
        $motivation->email = $oldMotive->email;
        $motivation->address = $oldMotive->address;
        $motivation->about = $oldMotive->about;
        $motivation->resume = $oldMotive->resume;
        $motivation->status =   1;
        $motivation->why_germany = $oldMotive->why_germany;
        $motivation->after_graduation = $oldMotive->after_graduation;
        $motivation->extra_text = $oldMotive->extraText;
        $motivation->url_uploaded_from_user = $oldMotive->url_uploaded_from_user;
        $motivation->url_uploaded_from_admin = $oldMotive->url_uploaded_from_admin;
        $motivation->admin_attachment = $oldMotive->admin_attachment;
        $motivation->writer_id = null;
        if($motivation->save()){
            foreach ($request->universities as $key => $university) {
                $muniversity = new MotivationUniversity();
                $muniversity->motivation_id = $motivation->id;
                $muniversity->name = $university['name'];
                $muniversity->field = $university['field'];
                $muniversity->text1 = $request->description;
                $muniversity->save();
            }

            $storeId = new ResumeMotivationIds();
            $storeId->model_id = $motivation->id;
            $storeId->user_id = $motivation->user_id;
            $storeId->model_type = 'motivation';
            $storeId->save();

//            $send = (new SMS())->sendVerification($motivation->user->mobile, "motivation_added", "name=={$name}&order=={$motivation->id}");
//            $send = User::sendMail(new MailVerificationCode("motivation_added", [
//                $motivation->name,
//                $motivation->id,
//            ], "motivation_added"), $motivation->user->email);

            //to admin
            $name = $motivation->user->firstname . " " . $motivation->user->lastname;
            $order_admin = User::where('level', 4)->where("admin_permissions", "like", '%"orders":1%')->get();
            foreach ($order_admin as $admin) {
                $send = User::sendMail(new MailVerificationCode("admin_motivation_added", [
                    $name,
                    $motivation->id,
                ], "admin_motivation_added"), 'marzie.tarighi7596@gmail.com');
                $notif = (new Notification("admin_motivation_added", [$name, $motivation->id]))->send($admin->id);
            }

            $supps = UserSupervisor::where('user_id', $motivation->user_id)->get();
            foreach ($supps as $sup) {
                if ($sup->supervisor->level === 2) {
                    $send = User::sendMail(new MailVerificationCode("admin_motivation_added", [
                        $name,
                        $motivation->id,
                    ], "admin_motivation_added"), 'marzie.tarighi7596@gmail.com');
                    $notif = (new Notification("admin_motivation_added", [$name, $motivation->id]))->send($sup->supervisor->id);

                    break;
                }
            }
            return ['msg'=>'با موفقیت ثبت شد','type'=>'success'];
        }else{
            return ['msg'=>'با موفقیت ثبت شد','type'=>'error'];
        }
    }

    public function modalResumeByExpert(Request $request){

        $oldResume=Resume::where('id',$request->resume_id)->first();
        $resume=new Resume();
        $resume->theme=$oldResume->theme;
        $resume->user_id=$request->user_id;
        $resume->language=$oldResume->language;
        $resume->name=$oldResume->name;
        $resume->family=$oldResume->family;
        $resume->birth_date=$oldResume->birth_date;
        $resume->birth_place=$oldResume->birth_place;
        $resume->phone=$oldResume->phone;
        $resume->email=$oldResume->email;
        $resume->text=$request->description;
        $resume->address=$oldResume->address;
        $resume->socialmedia_links=$oldResume->socialmedia_links;
        $resume->url_uploaded_from_user=$oldResume->url_uploaded_from_user;
        $resume->url_uploaded_from_admin=$oldResume->url_uploaded_from_admin;
        $resume->admin_comment=$oldResume->admin_comment;
        $resume->user_comment=$oldResume->user_comment;
        $resume->edit_request=$oldResume->edit_request;
        $resume->color=$oldResume->color;
        if($resume->save()){
            $storeId = new ResumeMotivationIds();
            $storeId->model_id = $resume->id;
            $storeId->user_id = $resume->user_id;
            $storeId->model_type = 'resume';
            $storeId->save();
            $name = $resume->user->firstname . " " . $resume->user->lastname;
            $supps = UserSupervisor::where('user_id', $resume->user_id)->with('supervisor')->get();
            foreach ($supps as $sup) {
                if ($sup->supervisor->level == 2) {
//                    $send = User::sendMail(new MailVerificationCode("admin_resume_added", [
//                        $name,
//                        $resume->id,
//                    ], "admin_resume_added"), $sup->supervisor->email);
                    $notif = (new Notification("admin_resume_added", [$name, $resume->id]))->send($sup->supervisor->id);
                    break;
                }
            }
            return ['msg'=>'با موفقیت ثبت شد','type'=>'success'];
        }else{
            return ['msg'=>'با موفقیت ثبت شد','type'=>'error'];
        }
    }
    public function modalMotivationByExpertOld(Request $request){
        $oldMotive=Motivation::where('id',$request->motivation_id)->first();
        $motivation=new Motivation();
//        $motivation->extra_text=$request->description;
        $motivation->to = 1;
        $motivation->country = 1;
        $motivation->user_id = $request->user_id;
        $motivation->name = $oldMotive->name;
        $motivation->family = $oldMotive->family;
        $motivation->phone = $oldMotive->phone;
        $motivation->birth_date = $oldMotive->birth_date;
        $motivation->birth_place = $oldMotive->birth_place;
        $motivation->email = $oldMotive->email;
        $motivation->address = $oldMotive->address;
        $motivation->about = $oldMotive->about;
        $motivation->resume = $oldMotive->resume;
        $motivation->status =   1;
        $motivation->why_germany = $oldMotive->why_germany;
        $motivation->after_graduation = $oldMotive->after_graduation;
        $motivation->extra_text = $oldMotive->extraText;
        $motivation->url_uploaded_from_user = $oldMotive->url_uploaded_from_user;
        $motivation->url_uploaded_from_admin = $oldMotive->url_uploaded_from_admin;
        $motivation->admin_attachment = $oldMotive->admin_attachment;
        $motivation->writer_id = $oldMotive->writer_id;
        if($motivation->save()){
            foreach ($request->universities as $key => $university) {
                $muniversity = new MotivationUniversity();
                $muniversity->motivation_id = $motivation->id;
                $muniversity->name = $university['name'];
                $muniversity->field = $university['field'];
                $muniversity->text1 = $request->description;
                $muniversity->save();
            }
            return ['msg'=>'با موفقیت ثبت شد','type'=>'success'];
        }else{
            return ['msg'=>'با موفقیت ثبت شد','type'=>'error'];
        }
    }
    public function getApply(User $user)
    {
        $apply = $this->applyService->getApply($user);
        if ($apply != 0) {
            return response([
                'status' => 1,
                'msg' => 'لیست دانشگاه ها',
                'universities' => UniversitiesResource::collection($apply),
                'user' => new UserResource($user),
            ]);
        }
        return response([
            'status' => 0,
            'msg' => 'کاربر یافت نشد',
        ]);
    }

    public function uploadApplyFile(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:pdf|max:50000',
        ];
        $customMessages = [
            'file.required' => 'فایل را انتخاب کنید',
            'file.mimes' => 'پسوند فایل معتبر نیست',
            'file.max' => 'حجم فایل باید کمتر از 50 مگابایت باشد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $upload = $this->applyService->uploadApplyFile($request);
        $file = public_path('uploads/applies/' . $request->userId . '/' . $request->id . '.pdf');
        $dataArray = [];
        if (is_file($file)) {
            $dataArray['applyFile'] = route('applyFile', ['userId' => $request->userId, 'id' => $request->id]);
            $dataArray['applyFileSize'] = MyHelpers::formatSizeUnits(filesize($file));
        }
        if ($upload) {
            return response([
                'status' => 1,
                'msg' => 'فایل با موفقیت ارسال شد',
                'data' => $dataArray,
            ]);
        }
        return response([
            'status' => 0,
            'msg' => 'خطا در ذخیره فایل (PDF Version 1.7)',
        ]);
    }


    public function deleteApplyFile(Request $request)
    {
        $document = $this->applyService->deleteApplyFile($request);
        if ($document) {
            return response([
                'status' => 1,
                'msg' => 'فایل با موفقیت حذف شد',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }
}
