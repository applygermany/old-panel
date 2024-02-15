<?php

namespace App\Http\Controllers\Api\V1\Expert;

use App\Models\Category;
use App\Models\User;
use App\Models\UserTelSupport;
use App\Providers\JDF;
use App\Providers\SMS;
use App\Models\Pricing;
use App\Models\TelSupport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Mail\MailVerificationCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\CommentCollection;
use App\Http\Services\V1\Expert\TelSupportService;
use App\Http\Resources\Expert\TelSupportCollection;
use App\Http\Resources\Expert\TelSupportTagResource;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Securities\Price;

class TelSupportController extends Controller
{
    protected $telService;

    public function __construct(TelSupportService $telSupportService)
    {
        $this->telService = $telSupportService;
    }

    // get history of expert tel support
    public function getHistoryTel(Request $request)
    {
        $history = $this->telService->getHistoryTel($request);
        return response([
            'status' => 1,
            'telSupports' => new TelSupportCollection($history[0]),
            'startDate' => $history[1],
            'endDate' => $history[2],
        ]);
    }

    // get data
    public function getTelSupportData()
    {
        $history = $this->telService->getTelSupportData7Days();
        $history2 = $this->telService->getTelSupportDataMonth();
        $history3 = $this->telService->getTelSupportData3Month();
        $history4 = $this->telService->getTelSupportData6Month();
        return response([
            'status' => 1,
            'data' => [
                "7d" => $history,
                "1m" => [
                    'allUsers' => $history2['allUsers'],
                    'normalUsers' => $history2['normalUsers'],
                    'specialUsers' => $history2['specialUsers'],
                ],
                "3m" => [
                    'allUsers' => $history3['allUsers'],
                    'normalUsers' => $history3['normalUsers'],
                    'specialUsers' => $history3['specialUsers'],
                ],
                "6m" => [
                    'allUsers' => $history4['allUsers'],
                    'normalUsers' => $history4['normalUsers'],
                    'specialUsers' => $history4['specialUsers'],
                ],
            ],
        ]);
    }

    public function getTelSupportDataMonth()
    {
        $history = $this->telService->getTelSupportDataMonth();
        return response([
            'status' => 1,
            'allUsers' => $history['allUsers'],
            'normalUsers' => $history['normalUsers'],
            'specialUsers' => $history['specialUsers'],
        ]);
    }

    public function getTelSupportData3Month()
    {
        $history = $this->telService->getTelSupportData3Month();
        return response([
            'status' => 1,
            'allUsers' => $history['allUsers'],
            'normalUsers' => $history['normalUsers'],
            'specialUsers' => $history['specialUsers'],
        ]);
    }

    public function getTelSupportData6Month()
    {
        $history = $this->telService->getTelSupportData6Month();
        return response([
            'status' => 1,
            'allUsers' => $history['allUsers'],
            'normalUsers' => $history['normalUsers'],
            'specialUsers' => $history['specialUsers'],
        ]);
    }

    // get today tel support
    public function getMonthTel()
    {
        $getMonthTel = $this->telService->getMonthTel();
        return response([
            'status' => 1,
            'monthName' => $getMonthTel[0],
            'telSupports' => new TelSupportCollection($getMonthTel[1]),
            'allTelSupportsCount' => $getMonthTel[2],
            'normalTelSupportsCount' => $getMonthTel[3],
            'specialTelSupportsCount' => $getMonthTel[4],
            'telSupportsCount' => $getMonthTel[5],
            'contractCount' => $getMonthTel[6],
        ]);
    }

    // get tags
    public function getTags()
    {
        $getTags = $this->telService->getTags();
        $month = JDF::jdate("m");
        $month = (int)JDF::fa_to_en($month);
        $names = [];
        $lastThreeMonths = [];
        $lastSixMonths = [];
        $day = JDF::jdate("w");
        $day = (int)JDF::fa_to_en($day);
        $days = [];
        $dayList = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'];
        for ($i = 7; $i >= 0; $i--) {
            if ($day > $i) {
                $days[] = $dayList[$i];
            }
        }
        $size = sizeof($days);
        if ($size < 7) {
            $diff = 7 - $size;
            for ($i = 6; $i > (6 - $diff); $i--) {
                $days[] = $dayList[$i];
            }
        }
        for ($i = 12; $i >= 1; $i--) {
            if ($month >= $i) {
                $lastThreeMonths[] = JDF::jdate_words(['mm' => $i])['mm'];
            }
        }
        $size = sizeof($lastThreeMonths);
        if ($size < 3) {
            $diff = 3 - $size;
            for ($i = 12; $i > (12 - $diff); $i--) {
                $lastThreeMonths[] = JDF::jdate_words(['mm' => $i])['mm'];
            }
        }
        for ($i = 12; $i >= 1; $i--) {
            if ($month >= $i) {
                $lastSixMonths[] = JDF::jdate_words(['mm' => $i])['mm'];
            }
        }
        $size = sizeof($lastSixMonths);
        if ($size < 6) {
            $diff = 6 - $size;
            for ($i = 12; $i > (12 - $diff); $i--) {
                $lastSixMonths[] = JDF::jdate_words(['mm' => $i])['mm'];
            }
        }
        $lastSixMonths = array_reverse($lastSixMonths);
        $telMaxPrice = Pricing::first()->tel_maximum_price;

        return response([
            'status' => 1,
            'moneyTags' => TelSupportTagResource::collection($getTags[0]),
            'dateTags' => TelSupportTagResource::collection($getTags[1]),
            'monthLabels' => [
                'days' => array_reverse($days),
                'last3Month' => array_reverse($lastThreeMonths),
                'last6Month' => [
                    $lastSixMonths[0],
                    $lastSixMonths[2],
                    $lastSixMonths[5],
                ],
            ],
            "telMaxPrice" => $telMaxPrice,
            'categories' => Category::all()
        ]);
    }

    // cancel user tel support
    public function cancelUserTelSupport(TelSupport $telSupport)
    {
        $cancel = $this->telService->cancelUserTelSupport($telSupport);
        if ($cancel == 1) {
            return response([
                'status' => 1,
                'msg' => 'وقت جلسه شما با موفقیت لغو شد',
            ]);
        } elseif ($cancel == 2) {
            return response([
                'status' => 0,
                'msg' => 'جلسه نمی تواند حذف شود . شرط زمانی برای لغو جلسه گذشته است',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'جلسه مشاوره یافت نشد',
            ]);
        }
    }

    // delete tel support
    public function deleteTelSupport(TelSupport $telSupport)
    {
        $delete = $this->telService->deleteTelSupport($telSupport);
        if ($delete == 1) {
            return response([
                'status' => 1,
                'msg' => 'جلسه مشاوره با موفقیت حذف شد',
            ]);
        } elseif ($delete == 2) {
            return response([
                'status' => 0,
                'msg' => 'جلسه مشاوره توسط کاربر رزرو شده و نمی تواند حذف شود',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'جلسه مشاوره یافت نشد',
            ]);
        }
    }

    // save session
    public function saveSession(Request $request)
    {
        $rules = [
            'dayTel' => 'required|date',
            'fromTime' => 'required|string|regex:/:/|min:5|max:5',
            'toTime' => 'required|string|regex:/:/|min:5|max:5',
            'type' => ['required', Rule::in([1, 2])],
        ];
        $customMessages = [
            'datTel.required' => 'ورود تاریخ الزامی است',
            'day_tel.date' => 'فرمت تاریخ اشتباه است',
            'fromTime.required' => 'ورود ساعت شروع الزامی است',
            'toTime.required' => 'ورود ساعت پایان الزامی است',
            '*.regex' => "ساعت به فرمت XX:YY وارد شود",
            '*.min' => "ساعت به فرمت XX:YY وارد شود",
            '*.max' => "ساعت به فرمت XX:YY وارد شود",
            'type.required' => 'ورود نوع کاربر الزامی است',
            'type.in' => 'فرمت توع کاربر اشتباه است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => "خطا در مقادیر ورودی",
                'errors' => $validator->errors(),
            ]);
        $max_price = Pricing::first()->tel_maximum_price;
        if ($request->price > $max_price && $max_price != 0 && $request->price != "رایگان") {
            return response([
                'status' => 0,
                'msg' => 'بیشترین قیمت ممکن برای مشاوره ' . $max_price . ' می باشد',
            ]);
        }
        $save = $this->telService->saveSession($request);
        if ($save == 1) {
            return response([
                'status' => 1,
                'msg' => 'جلسه با موفقیت تعریف شد',
            ]);
        } elseif ($save == 2) {
            return response([
                'status' => 0,
                'msg' => 'جلسه تکراری است',
            ]);

        } elseif ($save == 0) {
            return response([
                'status' => 0,
                'msg' => 'مشکلی پیش آمده لطفا بعدا تلاش کنید',
            ]);
        }
    }

    // save auto session
    public function saveAutoSession(Request $request)
    {
        $rules = [
            'dayTel' => 'required|date',
            'fromTime' => 'required|string|regex:/:/|min:5|max:5',
            'time' => 'required|string',
            'count' => 'required|string',
            'type' => ['required', Rule::in([1, 2])],
        ];
        $customMessages = [
            'datTel.required' => 'ورود تاریخ الزامی است',
            'day_tel.date' => 'فرمت تاریخ اشتباه است',
            'fromTime.required' => 'ورود ساعت شروع الزامی است',
            'time.required' => 'ورود زمان جلسه الزامی است',
            'count.required' => 'ورود تعداد جلسه الزامی است',
            '*.regex' => "ساعت به فرمت XX:YY وارد شود",
            '*.min' => "ساعت به فرمت XX:YY وارد شود",
            '*.max' => "ساعت به فرمت XX:YY وارد شود",
            'type.required' => 'ورود نوع کاربر الزامی است',
            'type.in' => 'فرمت توع کاربر اشتباه است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => "خطا در مقادیر ورودی",
                'errors' => $validator->errors(),
            ]);
        $max_price = Pricing::first()->tel_maximum_price;
        if ($request->price > $max_price && $max_price != 0 && $request->price != "رایگان") {
            return response([
                'status' => 0,
                'msg' => 'قیمت مشاوره ' . $max_price . ' می باشد',
            ]);
        }
        $save = $this->telService->saveAutoSession($request);
        if ($save == 1 || $save == 2) {
            return response([
                'status' => 1,
                'msg' => 'جلسات با موفقیت تعریف شد',
            ]);
        } elseif ($save == 0) {
            return response([
                'status' => 0,
                'msg' => 'تعداد جلسه انتخابی بیشتر از حد مجاز روزانه است',
            ]);

        }
    }

    // get comments
    public function getComments()
    {
        $comments = $this->telService->getComment();
        if ($comments) {
            return response([
                'status' => 1,
                'msg' => 'نمایش کامنت ها',
                'comments' => new CommentCollection($comments),
                'commentsCount' => $comments->count(),
                'scoreAvg' => $comments->avg('score'),
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    public function deleteTimeSupport(Request $request)
    {
        $support = UserTelSupport::where('tel_support_id', $request->id)->first();
        if ($support->delete()) {
            return response([
                'status' => 1,
                'msg' => 'مشاوره با موفقیت حذف گردید',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'حذف مشاوره با شکست مواجه گردید',
            ]);
        }
    }
}
