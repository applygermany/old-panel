<?php

namespace App\Mail;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailVerificationCode
{
    public $temp;
    public $title;
    public $text;
    public $path;
    public $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($temp, $data, $path = 'error', $file = null)
    {
        $this->temp = $temp;
        $message = $this->messageBuild($temp, $data);
        $this->title = $message["title"];
        $this->text = $message["text"];
        $this->path = $path;
        $this->file = $file;
    }

    public function messageBuild($temp, $data)
    {
        $title = (object)[
            "terminate_contract" => "فسخ قرارداد",
            "forgot" => "فراموشی رمز",
            "signup" => "ثبت نام",
            //"tksignup"                     => "Authentication",
            "registered" => "تکمیل ثبت نام",
            "tel_reserved" => "رزرو تایم مشاوره",
            "tel_24h_remained" => "یادآوری تایم مشاوره",
            "new_acceptance" => "درخواست اپلای",
            "new_acceptance_supervisor" => "درخواست اپلای",
            "add_support" => "پشتیبان جدید",
            "add_supervisor" => "کارشناس ",
            "change_support" => "تغییر پشتیبان",
            "change_supervisor" => "تغییر کارشناس ",
            "invite_code" => "کد معرف",
            //"university_add"               => "اضافه شدن دانشگاه",
            //"university_remove"            => "حذف شدن دانشگاه",
            "motivation_added" => "دریافت سفارش",
            "motivation_edit_needed" => "سفارش شما نیاز به تغییر دارد",
            "motivation_done" => "سفارش شما آماده شد",
            "resume_added" => "دریافت سفارش",
            "resume_edit_needed" => "سفارش شما نیاز به تغییر دارد",
            "resume_done" => "سفارش شما آماده شد",
            //"add_duty"                     => "اضافه شدن وظیفه",
            //"edit_duty"                    => "تعییر وظیفه",
            "add_pre_invoice" => "پیش فاکتور به شماره {$data[1]} - {$data[2]}",
            "add_receipt_invoice" => "رسید به شماره {$data[1]} - {$data[2]}",
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
            //"expert_upload"                => "آپلود مدارک",
            //"new_time"                     => "وقت جدید",
            "add_writer" => "درخواست نگارش",
            "writer_uploaded_document" => "آپلود فایل توسط نگارنده",
            "admin_file_upload_accept" => "تایید نگارش",
            "writer_uploaded_document_declined" => "رد نگارش",
            "university_acceptance" => "اخذ پذیرش دانشگاه",
            "webinar_submit" => "ثبت نام وبینار",
            "tel_support_poll" => "ارزیابی عملکرد مشاور",
            "reject_file_by_expert"=>"رد مدارک توسط کارشناس"
        ];
            $data[1] ?? $data[1] = "";
            $data[2] ?? $data[2] = "";
            $data[3] ?? $data[3] = "";
            $data[0] ?? $data[0] = rand(9999, 99999);
        $text = (object)[
            "terminate_contract" => "
            {$data[0]} عزیز !
            قرارداد شما در اپلای جرمنی فسخ گردید.
            فرم فسخ:
            {$data[2]}
            ",
            "forgot" => "کد تایید فراموشی رمز : {$data[0]} این کد مختص شماست. لطفا برای دیگران ارسال نکنید.
            ",
            "signup" => "کد تایید شماره همراه شما : {$data[0]} این کد مختص شماست. لطفا برای دیگران ارسال نکنید.",
            //"tksignup"                     => "Your activation code is {$data[0]} please do not share this with anyone",
            "registered" => "{$data[0]} عزیز ثبت نام شما در پورتال با موفقیت انجام شد. لطفا در اسرع وقت نسبت به تکمیل پروفایل خود اقدام نمایید.",
            "invite_code" => "{$data[0]} عزیز یک کاربر با کد معرف شما در سایت ثبت نام انجام داد شما. میتوانید از قسمت کد معرف در پنل خود نسبت به پیگیری وضعیت ایشان اقدام نمایید.",
            //"university_add"               => "{$data[0]} عزیز لیست دانشگاه های شما فعال شد. شما میتوانید از قسمت انتخاب دانشگاه در پنل خود نسبت به انتخاب دانشگاه مورد نظر اقدام نمایید.",
            //"university_remove"            => "{$data[0]} عزیز دانشگاه {$data[1]} از سبد اپلای شما حذف گردید.",
            "motivation_added" => "{$data[0]} عزیز سفارش انگیزه نامه شما به شماره {$data[1]} دریافت شد.",
            "motivation_edit_needed" => "{$data[0]} عزیز سفارش انگیزه نامه شما به شماره {$data[1]} به وضعیت نیاز به ویرایش تغییر یافت. لطفا در اسرع وقت پنل خود را چک نمایید.",
            "motivation_done" => "{$data[0]} عزیز سفارش انگیزه نامه شما به شماره {$data[1]} آماده شد. لطفا در اسرع وقت پنل خود را چک نمایید.",
            "resume_added" => "{$data[0]} عزیز سفارش رزومه شما به شماره {$data[1]} دریافت شد.",
            "resume_edit_needed" => "{$data[0]} عزیز سفارش رزومه شما به شماره {$data[1]} به وضعیت نیاز به ویرایش تغییر یافت. لطفا در اسرع وقت پنل خود را چک نمایید.",
            "resume_done" => "{$data[0]} عزیز سفارش رزومه شما به شماره {$data[1]} آماده شد. لطفا در اسرع وقت پنل خود را چک نمایید.",
            //"add_duty"                     => "$data[0] عزیز یک وظیفه به بخش تایم لاین وظیفه شما اضافه شد. میتوانید نسبت به مشاهده آن از پنل خود اقدام نمایید.",
            //"edit_duty"                    => "$data[0] عزیز یبخش تایم لاین وظیفه شما تغییر کرد. میتوانید نسبت به مشاهده آن از پنل خود اقدام نمایید.",
            "add_pre_invoice" => "{$data[0]} عزیز، یک پیش فاکتور به شماره {$data[1]} جهت {$data[2]} {$data[3]} به پیوست، پیش فاکتور پرداخت تقدیم می شود.",
            "add_receipt_invoice" => "{$data[0]} عزیز، پرداخت فاکتور به شماره {$data[1]} توسط اپلای جرمنی تایید شد. به پیوست، رسید پرداخت تقدیم می شود.",
            "pay_inovice" => "{$data[0]} عزیز صورتحساب شما به شماره {$data[1]} پرداخت شد. با تشکر از پرداخت شما",
            "uni_apply" => "{$data[0]} عزیز وضعیت دانشگاه {$data[1]} به {$data[2]} تغییر کرد. شما میتوانید تمامی مراحل را از طریق پنل خود پیگیری نمایید.",
            "university_accept" => "{$data[0]} عزیز تبریک! شما موفق به اخذ پذیرش از دانشگاه {$data[1]} شدید. با ورود به قسمت پیگیری اپلای می‌توانید پذیرش خود را دانلود کنید.",
            "university_reject" => "{$data[0]} عزیز متأسفانه در خواست پذیرش شما از دانشگاه {$data[1]} با موفقیت همراه نشد. لطفا با کارشناس خود در ارتباط باشید.",
            "add_support" => "{$data[0]} عزیز پشتیبان {$data[1]} به پرونده شما اضافه شد. شما میتوانید از طریق سایت اقدام به برقراری با این پشتیبان کنید.",
            "add_supervisor" => "{$data[0]} عزیز کارشناس {$data[1]} به پرونده شما اضافه شد. شما میتوانید از طریق سایت اقدام به برقراری با این کارشناس کنید.",
            "change_support" => "{$data[0]} عزیز پشتیبان شما از {$data[1]} به {$data[2]} تغییر کرد.",
            "change_supervisor" => "{$data[0]} عزیز کارشناس شما از {$data[1]} به {$data[2]} تغییر کرد.",
            "new_acceptance" => "{$data[0]} عزیز درخواست شما با موفقیت ثبت شد. شما از هم اکنون جز کاربران ویژه سایت ما هستید و میتوانید از تمامی امکانات سایت استفاده کنید. لطفا در اسرع وقت نسبت به گرفتن وقت مشاوره اقدام نمایید.",
            "new_acceptance_supervisor" => "درخواست اخذ پذیرش توسط کاربر {$data[0]} به شماره تماس/ایمیل {$data[1]} در تاریخ {$data[2]} انجام شده است. ",
            "tel_reserved" => "{$data[0]} عزیز مشاوره شما در تاریخ {$data[1]} در بازه زمانی {$data[2]} تا {$data[3]} ثبت شد. لطفا در زمان مقرر از طریق اپلیکیشن واتس اپ با شماره {$data[4]} با مشاوره خود تماس بگیرید.",
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
            //"expert_upload"                => "کاربر {$data[0]} مدرک {$data[1]} خود را آپلود کرد.",
            //"new_time"                     => "کارشناس شما وقت جدیدی برای تاریخ {$data[0]}از ساعت {$data[1]} تا {$data[2]} باز کرده است!",
            "add_writer" => "نگارنده عزیز، کاربر {$data[0]} برای نگارش {$data[1]} شما ثبت  گردید.",
            "writer_uploaded_document" => "مدیر گرامی، فایل نگارش {$data[0]} برای کاربر {$data[1]} توسط نگارنده {$data[2]} به شماره سفارش {$data[3]} ثبت گردید.",
            "admin_file_upload_accept" => "سفارش نگارش {$data[0]} برای کاربر {$data[1]} توسط مدیر تایید گردید",
            "writer_uploaded_document_declined" => "سفارش نگارش {$data[0]} برای کاربر {$data[2]} به شماره سفارش {$data[2]} رد گردید.",
            "webinar_submit" => ":کاربر گرامی {$data[0]} ثبت نام شما در وبینار با موفقیت انجام گردید. برای مشاهده اطلاعات بیشتر برور روی لینک زیر کلیک نمایید\n {$data[1]}",
            "tel_support_poll" => "کاربر گرامی: {$data[0]} شما یک جلسه مشاوره با {$data[1]} داشته اید. لطفا نظر خود را با کلیک بر روی لینک {$data[2]} ثبت نمایید.",
            "reject_file_by_expert"=>"کاربر گرامی:{$data['name']} مدرک {$data['fileName']} شما به دلیل: {$data['reasonOfReject']} توسط کارشناس مربوطه رد شد! لطفاً مشکل مربوطه را برطرف و مجدداً آن را بارگذاری نمایید."
        ];
        return [
            "title" => $title->$temp,
            "text" => $text->$temp,
        ];
    }
}
