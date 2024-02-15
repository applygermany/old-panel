<?php

namespace App\Providers;
class Notification
{
    public $temp;
    public $title;
    public $text;


    public function __construct($temp, $data
    )
    {
        $this->temp = $temp;
        $message = $this->messageBuild($temp, $data);
        $this->title = $message["title"];
        $this->text = $message["text"];
    }

    public function messageBuild($temp, $data)
    {
        $title = (object)[
            "forgot" => "فراموشی رمز",
            "signup" => "ثبت نام",
            "registered" => "تکمیل ثبت نام",
            "tel_reserved" => "رزرو تایم مشاوره",
            "tel_24h_remained" => "یادآوری تایم مشاوره",
            "new_acceptance" => "درخواست اپلای",
            "add_support" => "پشتیبان جدید",
            "add_supervisor" => "کارشناس جدید",
            "change_support" => "تغییر پشتیبان",
            "change_supervisor" => "تغییر کارشناس",
            "invite_code" => "کد معرف",
            "university_add" => "اضافه شدن دانشگاه",
            "university_remove" => "حذف شدن دانشگاه",
            "motivation_added" => "دریافت سفارش",
            "motivation_edit_needed" => "سفارش شما نیاز به تغییر دارد",
            "motivation_done" => "سفارش شما آماده شد",
            "resume_added" => "دریافت سفارش",
            "resume_edit_needed" => "سفارش شما نیاز به تغییر دارد",
            "resume_done" => "سفارش شما آماده شد",
            "add_duty" => "اضافه شدن وظیفه",
            "edit_duty" => "تعییر وظیفه",
            "add_pre_invoice" => "پیش فاکتور جدید",
            "receipt_invoice" => "رسید جدید",
            "pay_inovice" => "پرداخت موفق",
            "uni_apply" => "وضعیت جدید",
            "university_accept" => "اخذ پذیرش",
            "university_reject" => "اخذ پذیرش موفقیت آمیز نبود",
            "expert_tel_reserved" => "رزرو مشاوره",
            "expert_tel_24h_remained" => "یادآوری تایم مشاوره",
            "expert_new_comment" => "کامنت جدید",
            "expert_user_added" => "کاربر جدید",
            "expert_apply_bascket" => "انتخاب دانشگاه",
            "admin_resume_added" => "سفارش رزومه",
            "admin_motivation_added" => "سفارش انگیزه نامه",
            "admin_resume_edit_needed" => "رزومه: ادیت از طرف کاربر",
            "admin_motivation_edit_needed" => "انگیزه نامه: ادیت از طرف کاربر",
            "co_request" => "درخواست همکاری",
            "admin_factor_paid" => "پرداخت فاکتور",
            "expert_contract" => "آپلود قرارداد",
            "expert_upload" => "آپلود مدارک",
            "new_acceptance_supervisor" => "درخواست اپلای",
            "add_writer" => "درخواست نگارش",
            "writer_uploaded_document" => "آپلود فایل توسط نگارنده",
            "admin_file_upload_accept" => "تایید نگارش",
            "writer_uploaded_document_declined" => "رد نگارش",
            "new_discount_code" => "کد تخفیف",
            "reject_file_by_expert"=>"رد مدرک توسط کارشناس"
        ];
            $data[1] ?? $data[1] = "";
            $data[2] ?? $data[2] = "";
            $data[3] ?? $data[3] = "";
            $data[0] ?? $data[0] = rand(9999, 99999);
        $text = (object)[
            "forgot" => "کد تایید فراموشی رمز : {$data[0]} این کد مختص شماست. لطفا برای دیگران ارسال نکنید.",
            "signup" => "کد تایید شماره همراه شما : {$data[0]} این کد مختص شماست. لطفا برای دیگران ارسال نکنید.",
            "registered" => "{$data[0]} عزیز به پرتال اپلای جرمنی خوش آمدین. شما در حال حاضر کاربر عادی هستین و میتوانید به صورت رایگان از مقالات مراحل اپلای استفاده کنین و هر سوالی داشتین با کارشناسان پشتیبانی ما مطرح کنید. همینطور میتونین با پر کردن فرم درخواست اخذ پذیرش در منوی سمت راست تبدیل به کاربر ویژه شوید و از تمام امکانات پرتال استفاده کنید.",
            "invite_code" => "{$data[0]} عزیز یک کاربر با کد معرف شما در سایت ثبت نام انجام داد شما. میتوانید از قسمت کد معرف در پنل خود نسبت به پیگیری وضعیت ایشان اقدام نمایید.",
            "university_add" => "{$data[0]} عزیز لیست دانشگاه های شما فعال شد. شما میتوانید از قسمت انتخاب دانشگاه در پنل خود نسبت به انتخاب دانشگاه مورد نظر اقدام نمایید.",
            "university_remove" => "{$data[0]} عزیز دانشگاه {$data[1]} از سبد اپلای شما حذف گردید.",
            "motivation_added" => "{$data[0]} عزیز سفارش انگیزه نامه شما به شماره {$data[1]} دریافت شد.",
            "motivation_edit_needed" => "{$data[0]} عزیز سفارش انگیزه نامه شما به شماره {$data[1]} به وضعیت نیاز به ویرایش تغییر یافت. لطفا در اسرع وقت پنل خود را چک نمایید.",
            "motivation_done" => "{$data[0]} عزیز سفارش انگیزه نامه شما به شماره {$data[1]} آماده شد. لطفا در اسرع وقت پنل خود را چک نمایید.",
            "resume_added" => "{$data[0]} عزیز سفارش رزومه شما به شماره {$data[1]} دریافت شد.",
            "resume_edit_needed" => "{$data[0]} عزیز سفارش رزومه شما به شماره {$data[1]} به وضعیت نیاز به ویرایش تغییر یافت. لطفا در اسرع وقت پنل خود را چک نمایید.",
            "resume_done" => "{$data[0]} عزیز سفارش رزومه شما به شماره {$data[1]} آماده شد. لطفا در اسرع وقت پنل خود را چک نمایید.",
            "add_duty" => "{$data[0]} عزیز یک وظیفه به بخش تایم لاین وظیفه شما اضافه شد. میتوانید نسبت به مشاهده آن از پنل خود اقدام نمایید.",
            "edit_duty" => "{$data[0]} عزیز یبخش تایم لاین وظیفه شما تغییر کرد. میتوانید نسبت به مشاهده آن از پنل خود اقدام نمایید.",
            "add_pre_invoice" => "{$data[0]} عزیز، یک پیش فاکتور به شماره {$data[1]} جهت {$data[2]} در حساب کاربری شما صادر شد.",
            "receipt_invoice" =>"{$data[0]} عزیز، پرداخت فاکتور به شماره {$data[1]} توسط اپلای جرمنی تایید شد.",
            "pay_inovice" => "{$data[0]} عزیز صورتحساب شما به شماره {$data[1]} پرداخت شد. با تشکر از پرداخت شما",
            "uni_apply" => "{$data[0]} عزیز وضعیت دانشگاه {$data[1]} به {$data[2]} تغییر کرد. شما میتوانید تمامی مراحل را از طریق پنل خود پیگیری نمایید.",
            "university_accept" => "{$data[0]} عزیز شما موفق به اخذ پذیرش از دانشگاه {$data[1]} شدید. با ورود به قسمت پیگیری اپلای میتوانید پذیرش خود را دانلود کنید.",
            "university_reject" => "{$data[0]} عزیز شما موفق به اخذ پذیرش از دانشگاه {$data[1]} نشدید. لطفا با کارشناس خود در ارتباط باشید.",
            "add_support" => "{$data[0]} عزیز پشتیبان {$data[1]} به پرونده شما اضافه شد. شما میتوانید از طریق سایت اقدام به برقراری با این پشتیبان کنید.",
            "add_supervisor" => "{$data[0]} عزیز کارشناس {$data[1]} به پرونده شما اضافه شد. شما میتوانید از طریق سایت اقدام به برقراری با این کارشناس کنید.",
            "change_support" => "{$data[0]} عزیز پشتیبان شما از {$data[1]} به {$data[2]} تغییر کرد.",
            "change_supervisor" => "{$data[0]} عزیز کارشناس شما از {$data[1]} به {$data[2]} تغییر کرد.",
            "new_acceptance" => "{$data[0]} عزیز ...شما از هم اکنون جزو کاربران ویژه وب‌سایت ما هستید و می‌توانید از تمامی امکانات پورتال استفاده کنید. لطفاً در اسرع وقت نسبت به گرفتن وقت مشاوره اقدام نمایید.",
            "tel_reserved" => "{$data[0]} عزیز مشاوره شما در تاریخ {$data[1]} در بازه زمانی {$data[2]} تا {$data[3]} ثبت شد. شماره تماس کارشناس {$data[4]} می باشد.",
            "tel_24h_remained" => " {$data[0]} عزیز شما یک درخواست وقت مشاوره فعال در تاریخ {$data[1]} و در بازه زمانی {$data[2]} تا {$data[3]} دارید.لطفا در ساعت مقرر با کارشناس خود تماس حاصل فرمایید.",
            "expert_tel_reserved" => "{$data[0]} عزیز تایم مشاوره شما در تاریخ {$data[1]} در بازه زمانی {$data[2]} تا {$data[3]} رزرو شد.",
            "expert_tel_24h_remained" => "{$data[0]} عزیز شما یک درخواست وقت مشاوره فعال در تاریخ {$data[1]} و در بازه زمانی {$data[2]} تا {$data[3]} دارید. لطفا در ساعت مقرر در دسترس باشید.",
            "expert_new_comment" => "{$data[0]} عزیز برای شما یک نظر جدید ثبت شد.",
            "expert_user_added" => "{$data[0]} عزیز کاربر {$data[1]} به پنل شما اضافه شد.",
            "expert_apply_bascket" => "کاربر {$data[0]}  دانشگاه {$data[1]} را برای اپلای انتخاب کرد",
            "admin_resume_added" => "کاربر {$data[0]} سفارش رزومه خود را به شماره {$data[1]} ثبت کرده است.",
            "admin_motivation_added" => "کاربر {$data[0]} سفارش انگیزه نامه خود را به شماره {$data[1]} ثبت کرده است.",
            "admin_resume_edit_needed" => "سفارش انگیزه نامه کاربر {$data[0]} به شماره {$data[1]} به وضعیت نیاز به ویرایش تغییر یافت. لطفا در اسرع وقت پنل خود را چک نمایید.",
            "admin_motivation_edit_needed" => "سفارش انگیزه نامه کاربر {$data[0]} به شماره {$data[1]} به وضعیت نیاز به ویرایش تغییر یافت. لطفا در اسرع وقت پنل خود را چک نمایید.",
            "co_request" => "یک درخواست همکاری در سایت ثبت شد.",
            "admin_factor_paid" => "کاربر {$data[0]} فاکتور به شماره {$data[1]} بابت {$data[2]} را پرداخت کرد.",
            "expert_contract" => " کاربر {$data[0]} قرارداد خود را آپلود کرد.",
            "expert_upload" => "کاربر {$data[0]} مدرک {$data[1]} خود را آپلود کرد.",
            "new_acceptance_supervisor" => "درخواست اخذ پذیرش توسط کاربر {$data[0]} به شماره تماس/ایمیل {$data[1]} در تاریخ {$data[2]} انجام شده است. ",
            "add_writer" => "نگارنده عزیز، کاربر {$data[0]} برای نگارش {$data[1]} شما ثبت  گردید.",
            "writer_uploaded_document" => "مدیر گرامی، فایل نگارش {$data[0]} برای کاربر {$data[1]} توسط نگارنده {$data[2]} به شماره سفارش {$data[3]} ثبت گردید.",
            "admin_file_upload_accept" => "سفارش نگارش {$data[0]} برای کاربر {$data[1]} توسط مدیر تایید گردید",
            "writer_uploaded_document_declined" => "سفارش نگارش {$data[0]} برای کاربر {$data[2]} به شماره سفارش {$data[2]} رد گردید.",
            "new_discount_code" => "کاربر گرامی کد تخفیف {$data[0]} از تاریخ {$data[1]} تا تاریخ {$data[2]} برای استفاده در {$data[3]} برای شما تعلق گرفته است.",
            "reject_file_by_expert"=>"کاربر گرامی:{$data['name']} مدرک {$data['fileName']} شما به دلیل: {$data['reasonOfReject']} توسط کارشناس مربوطه رد شد! لطفاً مشکل مربوطه را برطرف و مجدداً آن را بارگذاری نمایید."
        ];
        return [
            "title" => $title->$temp,
            "text" => $text->$temp,
        ];
    }

    public function send($user)
    {

        $client = new \GuzzleHttp\Client();
        try {
            $client->request("POST", "https://chat.applygermany.net/notification", [
                'json' => [
                    'to' => $user,
                    'body' => $this->text,
                    'title' => $this->title,
                ],
                "headers" => [
                    "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
                ],
            ]);
        } catch (\Exception $e) {
            //echo $e->getMessage();
        }
    }

}
