<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\Option;
use App\Models\User;
use App\Models\UserComment;
use App\Models\UserTelSupport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\CommentResource;
use App\Http\Resources\Expert\ExpertResource;
use App\Http\Services\V1\User\TelSupportService;
use App\Http\Resources\User\TelSupportsResource;
use App\Http\Resources\User\NextTelSupportResource;
use App\Http\Resources\User\FirstDayTelSupportsResource;

class TelSupportController extends Controller
{
    protected $telSupport;

    public function __construct(TelSupportService $telSupport)
    {
        $this->telSupport = $telSupport;
    }

    function array_remove_null($item)
    {
        if (!is_array($item)) {
            return $item;
        }
        return collect($item)
            ->reject(function ($item) {
                return is_null($item);
            })
            ->flatMap(function ($item, $key) {

                return is_numeric($key)
                    ? [$this->array_remove_null($item)]
                    : [$key => $this->array_remove_null($item)];
            })
            ->toArray();
    }

    public function telSupports()
    {
        $telSupports = $this->telSupport->telSupports();
        $collection = TelSupportsResource::collection($telSupports);
        $output = [];

        $hasSup = false;
        $user = User::find(auth()->guard('api')->id());
        foreach ($user->supervisors as $sup) {
            if ($sup->supervisor->level === 5) {
                $hasSup = true;
                break;
            }
        }

        foreach ($collection as $item) {
            $request = new Request();
            $request->id = $item->user_id;
            if (auth()->guard('api')->user()->type === 1) {
                if (json_decode($item->admin_permissions)->sup_tel_normal && json_decode($item->admin_permissions)->sup_tel_normal == 1) {
                    $length = 0;
                    $items = $this->telSupport->expertTelSupports($request);
                    foreach ($items['times'] as $item) {
                        $length += count($item['times']);
                    }
                    $items["expert"] = new ExpertResource($items['expert']);
                    if ($length > 0) {
                        $output[] = $items;
                    }
                }
            } else {
                if ($item->user_id === 26 || $hasSup) {
                    $length = 0;
                    $items = $this->telSupport->expertTelSupports($request);
                    foreach ($items['times'] as $item) {
                        $length += count($item['times']);
                    }
                    $items["expert"] = new ExpertResource($items['expert']);
                    if ($length > 0) {
                        $output[] = $items;
                    }
                } else {
                    if (json_decode($item->admin_permissions)->sup_tel && json_decode($item->admin_permissions)->sup_tel == 1) {
                        $length = 0;
                        $items = $this->telSupport->expertTelSupports($request);
                        foreach ($items['times'] as $item) {
                            $length += count($item['times']);
                        }
                        $items["expert"] = new ExpertResource($items['expert']);
                        if ($length > 0) {
                            $output[] = $items;
                        }
                    }
                }
            }
        }


        return response()->json([
            'status' => 1,
            'msg' => 'انتخاب مشاور',
            'telSupports' => $output,
            "hasSup" => $hasSup
        ]);
    }

    public function setNewTime(Request $request)
    {
        $rules = [
            'expertId' => 'required|should_be_nums',
            'userId' => 'required|should_be_nums',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
            ]);
        $expert = User::find($request->expertId);
        $notifs = json_decode($expert->notification_users, true);
        if (!$notifs) {
            $notifs = [];
        }
        $notifs[] = $request->userId;
        $expert->notification_users = json_encode($notifs);
        if ($expert->save()) {
            return response()->json([
                'status' => 1,
                'msg' => 'عملیات موفق',
            ]);
        }
        return response()->json([
            'status' => -1,
            'msg' => 'خطایی رخ داد',
        ]);
    }

    public function expertTelSupports(Request $request)
    {
        $rules = [
            'id' => 'required|should_be_nums',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
            ]);

        $expertTelSupports = $this->telSupport->expertTelSupports($request);

        return response([
            'status' => 1,
            'msg' => 'لیست مشاوره ها',
            'expert' => new ExpertResource($expertTelSupports['expert']),
            'times' => $expertTelSupports['times'],
            'nextTelSupport' => $expertTelSupports['nextTelSupport'],
            'comments' => $expertTelSupports['comments'],
        ]);
    }

    public function expertTelSupportsOld(Request $request)
    {
        $rules = [
            'id' => 'required|should_be_nums',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
            ]);
        $nextTelSupport = NULL;
        $expertTelSupports = $this->telSupport->expertTelSupports($request);
        if ($expertTelSupports['nextTelSupport'] != NULL) {
            $nextTelSupport = new NextTelSupportResource($expertTelSupports['nextTelSupport']);
        }
        return response([
            'status' => 1,
            'msg' => 'لیست مشاوره ها',
            'expert' => new ExpertResource($expertTelSupports['expert']),
            'weeks' => $expertTelSupports['weeks'],
            'days' => $expertTelSupports['days'],
            'months' => $expertTelSupports['months'],
            'firstDayTelSupports' => FirstDayTelSupportsResource::collection($expertTelSupports['firstDayTelSupports']),
            'secondDayTelSupports' => FirstDayTelSupportsResource::collection($expertTelSupports['secondDayTelSupports']),
            'thirdDayTelSupports' => FirstDayTelSupportsResource::collection($expertTelSupports['thirdDayTelSupports']),
            'fourthDayTelSupports' => FirstDayTelSupportsResource::collection($expertTelSupports['fourthDayTelSupports']),
            'fifthDayTelSupports' => FirstDayTelSupportsResource::collection($expertTelSupports['fifthDayTelSupports']),
            'sixthDayTelSupports' => FirstDayTelSupportsResource::collection($expertTelSupports['sixthDayTelSupports']),
            'seventhDayTelSupports' => FirstDayTelSupportsResource::collection($expertTelSupports['seventhDayTelSupports']),
            'firstDayTelSupports1' => FirstDayTelSupportsResource::collection($expertTelSupports['firstDayTelSupports1']),
            'secondDayTelSupports1' => FirstDayTelSupportsResource::collection($expertTelSupports['secondDayTelSupports1']),
            'thirdDayTelSupports1' => FirstDayTelSupportsResource::collection($expertTelSupports['thirdDayTelSupports1']),
            'fourthDayTelSupports1' => FirstDayTelSupportsResource::collection($expertTelSupports['fourthDayTelSupports1']),
            'fifthDayTelSupports1' => FirstDayTelSupportsResource::collection($expertTelSupports['fifthDayTelSupports1']),
            'sixthDayTelSupports1' => FirstDayTelSupportsResource::collection($expertTelSupports['sixthDayTelSupports1']),
            'seventhDayTelSupports1' => FirstDayTelSupportsResource::collection($expertTelSupports['seventhDayTelSupports1']),
            'firstDayTelSupports2' => FirstDayTelSupportsResource::collection($expertTelSupports['firstDayTelSupports2']),
            'secondDayTelSupports2' => FirstDayTelSupportsResource::collection($expertTelSupports['secondDayTelSupports2']),
            'thirdDayTelSupports2' => FirstDayTelSupportsResource::collection($expertTelSupports['thirdDayTelSupports2']),
            'fourthDayTelSupports2' => FirstDayTelSupportsResource::collection($expertTelSupports['fourthDayTelSupports2']),
            'fifthDayTelSupports2' => FirstDayTelSupportsResource::collection($expertTelSupports['fifthDayTelSupports2']),
            'sixthDayTelSupports2' => FirstDayTelSupportsResource::collection($expertTelSupports['sixthDayTelSupports2']),
            'comments' => CommentResource::collection($expertTelSupports['comments']),
            'nextTelSupport' => $nextTelSupport,
        ]);
    }

    public function chooseTelSupport(Request $request)
    {
        $rules = [
            'id' => 'required|should_be_nums',
            'title' => 'required|max:250|bad_chars',
        ];
        $customMessages = [
            'id.required' => 'ورود تاریخ الزامی است',
            'id.should_be_nums' => 'فرمت تاریخ اشتباه است',
            'title.required' => 'ورود موضوع الزامی است',
            'title.max' => 'موضوع حداکثر باید 250 کاراکتر باشد',
            'title.bad_chars' => 'موضوع حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $chooseTelSupport = $this->telSupport->chooseTelSupport($request);
        if ($chooseTelSupport["success"]) {
            $hash = array_reverse(explode("/", $chooseTelSupport["hash"]))[0];
            return response([
                'status' => 1,
                "hash" => $hash,
                'msg' => $hash == 0 ? "تایم مشاوره با موفقیت رزرو شد" : 'در حال انتقال به صفحه پرداخت...',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => $chooseTelSupport["msg"] ?? 'وقت مشاوره یافت نشد',
                't' => $chooseTelSupport["t"] ?? 'وقت مشاوره یافت نشد',
            ]);
        }
    }

    public function updateTelSupport(Request $request)
    {
        $rules = [
            'id' => 'required|should_be_nums',
            'title' => 'required|max:250|bad_chars',
        ];
        $customMessages = [
            'id.required' => 'ورود تاریخ الزامی است',
            'id.should_be_nums' => 'فرمت تاریخ اشتباه است',
            'title.required' => 'ورود موضوع الزامی است',
            'title.max' => 'موضوع حداکثر باید 250 کاراکتر باشد',
            'title.bad_chars' => 'موضوع حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $updateTelSupport = $this->telSupport->updateTelSupport($request);
        if ($updateTelSupport == 1)
            return response([
                'status' => 1,
                'msg' => 'وقت مشاوره با موفقیت بروز شد',
            ]);
        elseif ($updateTelSupport == 2)
            return response([
                'status' => 0,
                'msg' => 'خطا در بروزرسانی وقت مشاوره',
            ]);
        return response([
            'status' => 0,
            'msg' => 'وقت مشاوره یافت نشد',
        ]);
    }

    public function sendComment(Request $request)
    {
        $rules = [
            'text' => 'required|bad_chars',
            'expert_id' => "required",
            'score' => "required",
        ];
        $customMessages = [
            'text.required' => 'ورود متن الزامی ست',
            'text.bad_chars' => 'متن وارد شده حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);

        $save = $this->telSupport->sendComment($request);
        if ($save) {
            return response([
                'status' => 1,
                'msg' => 'کامنت با موفقیت ثبت شد',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    public function cancelTelSupport(Request $request)
    {
        $rules = [
            'id' => 'required|should_be_nums',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $updateTelSupport = $this->telSupport->cancelTelSupport($request);
        if ($updateTelSupport == 1)
            return response([
                'status' => 1,
                'msg' => 'وقت مشاوره با موفقیت کنسل شد',
            ]);
        elseif ($updateTelSupport == 2)
            return response([
                'status' => 0,
                'msg' => 'تا شروع جلسه کمتر از 24 ساعت باقیست . جلسه نمی تواند حذف شود',
            ]);
        return response([
            'status' => 0,
            'msg' => 'وقت مشاوره یافت نشد',
        ]);
    }

    function expertPollInfo($id)
    {
        $userTelSupport = UserTelSupport::find($id);
        $expert = User::find($userTelSupport->supervisor_id);
        $data = [
            "id" => $userTelSupport->id,
            "user_id" => $userTelSupport->user_id,
            "tel_support_id" => $userTelSupport->id,
            "expert_id" => $userTelSupport->supervisor_id,
            "expertId" => $userTelSupport->supervisor_id,
            "expert_name" => $expert->firstname . " " . $expert->lastname,
            "expert_level" => $expert->level,
            "expert_photo" => str_replace("api/", "", route('imageUser', [
                'id' => $expert->id,
                'ua' => strtotime($expert->updated_at),
            ])),
        ];

        return response([
            'status' => 1,
            'msg' => 'اطلاعات مشاور',
            'info' => $data
        ]);
    }
}
