<?php

namespace App\Http\Controllers\Api\V1\Expert;

use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\UserResumeResource;
use App\Http\Services\V1\Expert\OrderService;
use App\Mail\MailVerificationCode;
use App\Models\Invoice;
use App\Models\Motivation;
use App\Models\Resume;
use App\Models\Upload;
use App\Models\User;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Providers\Notification;
use App\Providers\SMS;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    function orders(Request $request)
    {
        $rules = [
            'name' => 'nullable|bad_chars',
            'category' => 'nullable|should_be_nums',
        ];
        $customMessages = [
            'name.bad_chars' => 'نام وارد شده حاوی کاراکتر های غیر مجاز است',
            'category.should_be_nums' => 'دسته بندی معتبر نیست',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $orders = $this->service->getAllRM($request);
        if ($orders) {
            return response([
                'status' => 1,
                'msg' => 'لیست سفارشات',
                'orders' => $orders[0],
                'categories' => $orders[1],
                'ordersCount' => $orders[2],
                'usersCount' => $orders[3],
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    function getMotivation($id)
    {
        $motivation = Motivation::find($id);

        $output = new \stdClass();
        $output = $motivation;
        $output->universities = $motivation->universities;

        $files = array();
        if ($motivation->url_uploaded_from_user) {
            $index = 0;
            foreach (json_decode($motivation->url_uploaded_from_user) as $key => $item) {
                $file = new \stdClass();
                $file->title = "دانلود فایل " . ($key + 1);
                $file->file = $item;
                $files[$index] = $file;
                $index++;
            }
        }
        $output->files = $files;
        $output->pdf = route('admin.downloadMotivationPreview', ['id' => $motivation->id]);

        return response([
            'status' => 1,
            'msg' => 'اطلاعات انگیزه نامه',
            'motivation' => $output,
            'acceptance' => $motivation->user->acceptances[0],
        ]);
    }

    function getResume($id)
    {
        $resume = Resume::find($id);

        $output = new \stdClass();
        $output = $resume;
        $output->image = route('imageResume', ['id' => $resume->id, 'ua' => strtotime($resume->user->updated_at)]);
        $output->educations = $resume->educationRecords;
        $output->languages = $resume->languages;
        $output->works = $resume->works;
        $output->softwareKnowledges = $resume->softwareKnowledges;
        $output->courses = $resume->courses;
        $output->researchs = $resume->researchs;
        $output->hobbies = $resume->hobbies;

        $files = array();
        if ($resume->url_uploaded_from_user) {
            $index = 0;
            foreach (json_decode($resume->url_uploaded_from_user) as $key => $item) {
                $file = new \stdClass();
                $file->title = "دانلود فایل " . ($key + 1);
                $file->file = $item;
                $files[$index] = $file;
                $index++;
            }
        }
        $output->files = $files;
        $output->pdf = route('admin.downloadResumePreview', ['id' => $resume->id]);

        return response([
            'status' => 1,
            'msg' => 'اطلاعات رزومه',
            'resume' => $output,
            'acceptance' => $resume->user->acceptances[0],
        ]);
    }

    function acceptFile($id, $type)
    {
        $data = null;
        if ($type === 'motivation') {
            $data = Motivation::find($id);

            $admins = json_decode($data->url_uploaded_from_admin, true);
            if (!is_array($admins)) {
                $admins = [];
            }
            $admins[] = $data->admin_accepted_filename;
            $data->url_uploaded_from_admin = json_encode($admins);
            $data->is_accepted = true;

            $upload = new Upload();
            $upload->type = 13;
            $upload->title = 'انگیزه نامه';
            $upload->text = $data->id;
            $upload->user_id = $data->user_id;
            $upload->date = MyHelpers::numberToEnglish(JDF::jdate('Y/m/d'));
            $upload->save();


            $sourceFilePath = public_path('uploads/WriterFile/' . explode('/', $data->admin_accepted_filename)[4]);
            if (!is_file($sourceFilePath)) {
                $sourceFilePath = public_path('uploads/motivationAdminFile/' . explode('/', $data->admin_accepted_filename)[4]);
            }
            $destinationPath = public_path('uploads/madarek/' . $upload->id . '.' . pathinfo($sourceFilePath)['extension']);
            \File::copy($sourceFilePath, $destinationPath);

        } else {
            $data = Resume::find($id);

            $data->url_uploaded_from_admin = $data->admin_accepted_filename;
            $data->is_accepted = true;

            $upload = new Upload();
            $upload->type = 12;
            $upload->title = 'رزومه';
            $upload->user_id = $data->user_id;
            $upload->text = $data->id;
            $upload->date = MyHelpers::numberToEnglish(JDF::jdate('Y/m/d'));
            $upload->save();

            $sourceFilePath = public_path('uploads/WriterFile/' . explode('/', $data->admin_accepted_filename)[4]);
            if (!is_file($sourceFilePath)) {
                $sourceFilePath = public_path('uploads/resumeAdminFile/' . explode('/', $data->admin_accepted_filename)[4]);
            }
            $destinationPath = public_path('uploads/madarek/' . $upload->id . '.' . pathinfo($sourceFilePath)['extension']);
            \File::copy($sourceFilePath, $destinationPath);
        }
        $data->status = 5;

        if ($data->save()) {
            $name = $data->user->firstname;
            $order = $data->id;

            if ($type === 'motivation') {

                $send = (new SMS())->sendVerification($data->user->mobile, "motivation_done", "name=={$name}&order=={$order}");
                $send = User::sendMail(new MailVerificationCode("motivation_done", [
                    $name,
                    $order,
                ], "motivation_done"), $data->user->email);
                $notif = (new Notification("motivation_done", [$name, $order]))->send($data->user->id);
            } else {
                $send = (new SMS())->sendVerification($data->user->mobile, "resume_done", "name=={$name}&order=={$order}");
                $send = User::sendMail(new MailVerificationCode("resume_done", [
                    $name,
                    $order,
                ], "resume_done"), $data->user->email);
                $notif = (new Notification("resume_done", [$name, $order]))->send($data->user->id);
            }

            return response([
                'status' => 1,
                'msg' => 'ثبت با موفقیت انجام گردید',
            ]);
        }
        return response([
            'status' => 0,
            'msg' => 'ثبت با شکست مواجه گردید',
        ]);
    }

    function declineAsUser(Request $request){
        if($request->type=='motivation'){//agar angize name bood
            $motivation = Motivation::where('id', $request->id)->first();
        }else{//agar resume bood
            $motivation = Resume::where('id', $request->id)->first();
        }
        $motivation->edit_request = $request->editRequestText;
        $motivation->user_comment = $request->editRequestText;
        $motivation->is_accepted = null;
        $motivation->status = 4;
        $motivation->admin_accepted_filename = null;
        if ($motivation->save()) {
            return 1;
        }
        return 0;
    }

    function declineFile($id, $type)
    {
        $data = null;
        if ($type === 'motivation') {
            $data = Motivation::find($id);

            $admins = json_decode($data->url_uploaded_from_admin, true);
            if (!is_array($admins)) {
                $admins = [];
            }
            $data->is_accepted = false;
            $data->admin_accepted_filename = null;

        } else {
            $data = Resume::find($id);

            $data->is_accepted = false;
            $data->admin_accepted_filename = null;
        }
        $data->status = 6;

        if ($data->save()) {

            $name = $data->user->firstname . ' ' . $data->user->lastname;
            $writerName = $data->writer->firstname . ' ' . $data->writer->lastname;
            $order_admin = User::where('level', 4)->where("admin_permissions", "like", '%"orders":1%')->get();
            foreach ($order_admin as $admin) {
                $send = User::sendMail(new MailVerificationCode("writer_uploaded_document_declined", [
                    $type === 'motivation' ? 'انگیزه نامه' : 'رزومه',
                    $name,
                    $writerName,
                    $data->id,
                ], "writer_uploaded_document_declined"), $admin->email);
                $notif = (new Notification("writer_uploaded_document_declined", [
                    $type === 'motivation' ? 'انگیزه نامه' : 'رزومه',
                    $name,
                    $writerName,
                    $data->id,
                ]))->send($admin->id);
            }

            return response([
                'status' => 1,
                'msg' => 'رد با موفقیت انجام گردید',
            ]);
        }
        return response([
            'status' => 0,
            'msg' => 'ثبت با شکست مواجه گردید',
        ]);
    }
}
