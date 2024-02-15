<?php

namespace App\Http\Controllers\Api\V1;

use App\Jobs\SendEmailJob;
use App\Jobs\SendEmailWebinarJob;
use App\Models\Option;
use App\Models\User;
use App\Providers\MyHelpers;
use App\Providers\SMS;
use App\Models\Comment;
use App\Models\Pricing;
use App\Models\Setting;
use App\Models\NewAccepted;
use App\Models\UserWebinar;
use App\Models\AdminComment;
use Illuminate\Http\Request;
use App\Models\ResumeTemplate;
use App\Providers\Notification;
use App\Mail\MailVerificationCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Services\V1\SiteService;
use App\Http\Resources\WebinarResource;
use App\Http\Resources\UserWebinarResource;
use App\Http\Resources\Expert\TeamsResource;

class SiteController extends Controller
{
    protected $site;

    public function __construct(SiteService $site)
    {
        $this->site = $site;
    }

    public function settings()
    {
        $settings = $this->site->settings();
        return response()->json([
            'status' => 1,
            'msg' => 'تنظیمات',
            'settings' => [
                'email' => $settings->email,
                'mobile' => $settings->mobile,
                'phone' => $settings->phone,
                'address' => $settings->address,
                'responseTime' => $settings->response_time,
            ],
        ]);
    }

    public function pricing()
    {
        $pricing = Pricing::first();
        return response()->json([
            'status' => 1,
            'msg' => 'مبالغ',
            'prices' => [
                'resume' => $pricing->resume_price,
                'resume_bi' => $pricing->resume_bi_price,
                'motivation' => $pricing->motivation_price,
                'invite' => $pricing->invite_action,
                'extra_university' => $pricing->extra_university_price,
                'add_college_price' => $pricing->add_college_price,
                'package' => [
                    "IRT" => round($pricing->package_price * $pricing->euro_price),
                    "IRT2" => round($pricing->package_2_price * $pricing->euro_price),
                    "EUR" => $pricing->package_price,
                    "EUR2" => $pricing->package_2_price,
                ],
            ],
        ]);
    }

    public function resumeTemps()
    {
        $temps = ResumeTemplate::get();
        return response()->json([
            'status' => 1,
            'msg' => 'قالب های رزومه',
            'data' => $temps,
        ]);
    }

    public function accepted()
    {
        header('Cache-Control: no-cache, must-revalidate');
        $accepted = NewAccepted::Query();
        $accepteds = $accepted->with('user:id,firstname,lastname')->orderBy('id', 'DESC')->paginate(9);
        foreach ($accepteds as $item) {

            if ($item->photo == "") {
                $item->photo = "https://api.applygermany.net/avatar_light.svg";
            }
        }
        return response()->json([
            'status' => 1,
            'msg' => 'پذیرفته شدگان اپلای جرمنی',
            'data' => $accepteds,
        ]);
    }

    public function contact()
    {
        $contact = Setting::first();
        return response()->json([
            'status' => 1,
            'msg' => 'تماس با ما',
            'data' => $contact,
        ]);
    }

    public function comments()
    {
        $comments = AdminComment::orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => 1,
            'msg' => 'کامنت های کاربران',
            'data' => $comments,
        ]);
    }

    public function comment()
    {

        return response()->json([
            'status' => 1,
            'msg' => 'نظرات کاربران',
            'comments' => Comment::orderBy('id', 'DESC')->get(),
        ]);
    }

    public function teams()
    {
        $teams = $this->site->teams();
        return response()->json([
            'status' => 1,
            'msg' => 'تیم اپلای جرمنی',
            'teamHeader' => route('teamHeader', ['ua' => strtotime(date('Y-m-d H:i:s'))]),
            'teams' => TeamsResource::collection($teams),
        ]);
    }

    public function faqs(Request $request)
    {
        $rules = [
            'question' => 'nullable|bad_chars',
        ];
        $customMessages = [
            'question.bad_chars' => 'متن حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        }
        $faqs = $this->site->faqs($request);
        return response()->json([
            'status' => 1,
            'msg' => 'سوالات متداول',
            'faqs' => $faqs,
        ]);
    }

    public function cfaqs(Request $request)
    {
        $rules = [
            'question' => 'nullable|bad_chars',
        ];
        $customMessages = [
            'question.bad_chars' => 'متن حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        }
        $faqs = $this->site->cfaqs($request);
        return response()->json([
            'status' => 1,
            'msg' => 'سوالات متداول',
            'faqs' => $faqs,
        ]);
    }

    public function webinar(Request $request)
    {
        $rules = [
            'id' => 'required',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        }
        $webinar = $this->site->webinar($request);
        if ($webinar) {
            return response()->json([
                'status' => 1,
                'msg' => 'وبینار',
                'webinar' => new WebinarResource($webinar),
            ]);
        }
        return response()->json([
            'status' => 0,
            'msg' => 'وبینار یافت نشد',
        ]);
    }

    public function userWebinar(Request $request)
    {
        $rules = [
            'id' => 'required',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        }
        $userWebinar = UserWebinar::find($request->id);
        $webinar = $this->site->webinarId($userWebinar->webinar_id);
        $userWebinar = $this->site->userWebinar($request);
        if ($webinar) {
            return response()->json([
                'status' => 1,
                'msg' => 'وبینار',
                'webinar' => new WebinarResource($webinar),
                'userWebinar' => new UserWebinarResource($userWebinar),
            ]);
        }
        return response()->json([
            'status' => 0,
            'msg' => 'وبینار یافت نشد',
        ]);
    }

    public function webinarBanners()
    {

        $webinar = $this->site->webinarBanners();
        if ($webinar) {
            return response()->json([
                'status' => 1,
                'msg' => 'وبینار',
                'webinar' => array_values($webinar->map(function ($item) {
                    return new WebinarResource($item);
                })->toArray()),
            ]);
        }
        return response()->json([
            'status' => 0,
            'msg' => 'وبینار یافت نشد',
        ]);
    }

    public function uploadWebinarReceipt(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:jpeg,png,jpg,pdf|max:5000',
        ];
        $customMessages = [
            'file.required' => 'فایل را انتخاب کنید',
            'file.mimes' => 'پسوند فایل معتبر نیست',
            'file.max' => 'حجم فایل باید کمتر از 5 مگابایت باشد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        }
        $uploadWebinarReceipt = $this->site->uploadWebinarReceipt($request);
        if ($uploadWebinarReceipt) {
            return response()->json([
                'status' => 1,
                'msg' => 'فایل با موفقیت آپلود شد',
                'imageName' => $uploadWebinarReceipt,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'خطا در آپلود فایل',
            ]);
        }
    }

    public function submitWebinar(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name' => 'required|min:2|max:250|bad_chars',
            'family' => 'required|min:2|max:250|bad_chars',
            'mobile' => 'required|max:250',
            'email' => 'required|max:250|email',
            //            'price' => 'maximum_num:100000000|should_be_nums|should_be_pos',
            'field' => 'required|min:2|max:250|bad_chars',
            'grade' => 'required|min:2|max:250|bad_chars',
            'instagram' => 'nullable|max:250|bad_chars',
            'telegram' => 'nullable|max:250|bad_chars',
        ];
        $customMessages = [
            'name.id' => 'ویبنار مشخص نیست',
            'name.required' => 'ورود نام الزامی است',
            'name.min' => 'نام حداقل باید 2 حرف باشد',
            'name.max' => 'نام حداکثر باید 250 حرف باشد',
            'name.bad_chars' => 'نام حاوی کاراکتر های غیر مجاز است',
            'family.required' => 'ورود نام خانوادگی الزامی است',
            'family.min' => 'نام خانوادگی حداقل باید 2 حرف باشد',
            'family.max' => 'نام خانوادگی حداکثر باید 250 حرف باشد',
            'family.bad_chars' => 'نام خانوادگی حاوی کاراکتر های غیر مجاز است',
            'mobile.required' => 'ورود موبایل الزامی است',
            'mobile.max' => 'موبایل حداکثر 250 رقم است',
            'email.required' => 'ورود ایمیل الزامی است',
            'email.max' => 'ایمیل حداکثر باید 250 حرف باشد',
            'email.email' => 'ایمیل معتبر نیست',
            //			'price.required'       => 'ورود مبلغ واریزی الزامی است',
            //			'price.maximum_num'    => 'مبلغ واریزی باید کمتر از 10 میلیون باشد',
            //			'price.should_be_nums' => 'مبلغ واریزی معتبر نیست',
            //			'price.should_be_pos'  => 'مبلغ واریزی معتبر نیست',
            'field.required' => 'ورود رشته الزامی است',
            'field.min' => 'رشته حداقل باید 2 حرف باشد',
            'field.max' => 'رشته حداکثر باید 250 حرف باشد',
            'field.bad_chars' => 'رشته حاوی کاراکتر های غیر مجاز است',
            'grade.required' => 'ورود مقطع الزامی است',
            'grade.min' => 'مقطع حداقل باید 2 حرف باشد',
            'grade.max' => 'مقطع حداکثر باید 250 حرف باشد',
            'grade.bad_chars' => 'مقطع حاوی کاراکتر های غیر مجاز است',
            'instagram.max' => 'نام خانوادگی حداکثر باید 250 حرف باشد',
            'instagram.bad_chars' => 'نام خانوادگی حاوی کاراکتر های غیر مجاز است',
            'telegram.max' => 'نام خانوادگی حداکثر باید 250 حرف باشد',
            'telegram.bad_chars' => 'نام خانوادگی حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'msg' => 'لطفا صحت اطلاعات وارد شده را بررسی کنید',
                'errors' => $validator->errors(),
            ]);
        }
        $submitWebinar = $this->site->submitWebinar($request);
        if (is_array($submitWebinar)) {

            $fullName = $request->name . " " . $request->family;
            $link = "https://applygermany.net/success/" . $submitWebinar["userWebinar"]["id"];
            try{
                $mail = new MailVerificationCode("webinar_submit", [$fullName, $link], 'webinar_submit');
                User::sendMail($mail, $request->email);
                $this->dispatchSync(new SendEmailWebinarJob($request->email, $request->name, $request->family));
                
            }catch(\Exception $e){

            }
            $send = (new SMS())->sendVerification(MyHelpers::numberToEnglish(strval(intval($request->mobile))),
                "webinar", "name==" . $fullName . "&email==" . $request->email);

//            (new SMS())->sendVerification(strval(intval($request->mobile)), 'webinar', "name=={$fullName}&name_2=={$submitWebinar['webinar']['title']}");

            return response()->json([
                'status' => 1,
                'data' => $submitWebinar,
                'msg' => 'تبریک : اطلاعات با موفقیت ارسال و ثبت شد',
            ]);
        }
        if ($submitWebinar == 1) {
            return response()->json([
                'status' => 1,
                'data' => $submitWebinar,
                'msg' => 'تبریک : اطلاعات با موفقیت ارسال و ثبت شد',
            ]);
        } elseif ($submitWebinar == 2) {
            return response()->json([
                'status' => 0,
                'msg' => 'لطفاً ابتدا تصویر رسید را ارسال کنید',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'خطا در ثبت اطلاعات',
            ]);
        }
    }

    public function uploadResumeCollaboration(Request $request)
    {
        $rules = [
            'file' => 'required|max:50000',
        ];
        $customMessages = [
            'file.required' => 'فایل را انتخاب کنید',
//            'file.mimes' => 'پسوند فایل معتبر نیست',
            'file.max' => 'حجم فایل باید کمتر از 50 مگابایت باشد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        }
        $uploadResumeCollaboration = $this->site->uploadResumeCollaboration($request);
        if ($uploadResumeCollaboration) {
            return response()->json([
                'status' => 1,
                'msg' => 'فایل با موفقیت آپلود شد',
                'name' => $uploadResumeCollaboration,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'خطا در آپلود فایل',
            ]);
        }
    }

    public function sendCollaboration(Request $request)
    {
        $rules = [
            'name' => 'required|max:250|bad_chars',
            'family' => 'required|max:250|bad_chars',
            'email' => 'required|max:250',
            'field' => 'required|max:250|bad_chars',
            'text' => 'required',
            'birthDate' => 'required|max:250',
        ];
        $customMessages = [
            'name.required' => 'ورود نام الزامی است',
            'name.max' => 'نام حداکثر باید 250 حرف باشد',
            'name.bad_chars' => 'نام حاوی کاراکتر های غیر مجاز است',
            'family.required' => 'ورود نام خانوادگی الزامی است',
            'family.max' => 'نام خانوادگی حداکثر باید 250 حرف باشد',
            'family.bad_chars' => 'نام خانوادگی حاوی کاراکتر های غیر مجاز است',
            'email.required' => 'ورود ایمیل یا موبایل الزامی است',
            'email.max' => 'ایمیل یا موبایل حداکثر باید 250 حرف باشد',
            'field.required' => 'ورود رشته الزامی است',
            'field.max' => 'رشته حداکثر باید 250 حرف باشد',
            'field.bad_chars' => 'رشته حاوی کاراکتر های غیر مجاز است',
            'text.required' => 'ورود شرح درخواست الزامی است',
            'birthDate.required' => 'ورود تاریخ تولد الزامی است',
            'birthDate.max' => 'تاریخ تولد حداکثر باید 250 حرف باشد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        }
        $sendCollaboration = $this->site->sendCollaboration($request);
        if ($sendCollaboration == 1) {
            //to admin
            $super_admin = User::where("isSuperAdmin", "1")->first();
            $send = User::sendMail(new MailVerificationCode("co_request", [], "co_request"), $super_admin->email);
            $notif = (new Notification("co_request", []))->send($super_admin->id);
            return response()->json([
                'status' => 1,
                'msg' => 'اطلاعات با موفقیت ارسال شد',
            ]);
        } elseif ($sendCollaboration == 2) {
            return response()->json([
                'status' => 0,
                'msg' => 'خطا در ذخیره سازی اطلاعات',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'خطا در ثبت اطلاعات',
            ]);
        }
    }

    function getVersion($panel)
    {
        if ($panel === 'expert') {
            return Option::where('name', 'support_version')->first()->value;
        } elseif ($panel === 'user') {
            return Option::where('name', 'user_version')->first()->value;
        } elseif ($panel === 'writer') {
            return Option::where('name', 'writer_version')->first()->value;
        }
    }

}
