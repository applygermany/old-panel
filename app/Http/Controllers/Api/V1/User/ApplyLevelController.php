<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\ApplyPhase;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\ApplyLevelsResource;
use App\Http\Services\V1\User\ApplyLevelService;
use App\Models\Option;
use Illuminate\Http\Request;

class ApplyLevelController extends Controller
{
    protected $applyLevel;

    public function __construct(ApplyLevelService $applyLevel)
    {
        $this->applyLevel = $applyLevel;
    }

    public function applyLevels()
    {
        $phases = $this->applyLevel->applyLevels();
        $phase1 = ApplyPhase::find(1);
        $phase2 = ApplyPhase::find(2);
        $phase3 = ApplyPhase::find(3);
        $phase4 = ApplyPhase::find(4);
        $phase5 = ApplyPhase::find(5);

        return response([
            'status' => 1,
            'msg' => 'لیست فازهای اپلای',
            'phase1' => ["title" => $phase1->title, "description" => $phase1->description, "data" => ApplyLevelsResource::collection(collect($phases['phase1'])->sortBy("pos", SORT_REGULAR, false))],
            'phase2' => ["title" => $phase2->title, "description" => $phase2->description, "data" => ApplyLevelsResource::collection(collect($phases['phase2'])->sortBy("pos", SORT_REGULAR, false))],
            'phase3' => ["title" => $phase3->title, "description" => $phase3->description, "data" => ApplyLevelsResource::collection(collect($phases['phase3'])->sortBy("pos", SORT_REGULAR, false))],
            'phase4' => ["title" => $phase4->title, "description" => $phase4->description, "data" => ApplyLevelsResource::collection(collect($phases['phase4'])->sortBy("pos", SORT_REGULAR, false))],
            'phase5' => ["title" => $phase5->title, "description" => $phase5->description, "data" => ApplyLevelsResource::collection(collect($phases['phase5'])->sortBy("pos", SORT_REGULAR, false))]
        ]);
    }

    public function checkApplyLevel(Request $request)
    {
        $rules = [
            'id' => 'required|should_be_nums'
        ];

        $customMessages = [
            'id.required' => 'آیدی را وارد کنید',
            'id.should_be_nums' => 'شناسه معتبر نیست'
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);

        $applyLevel = $this->applyLevel->checkApplyLevel($request);
        if ($applyLevel)
            return response([
                'status' => 1,
                'msg' => 'مرحله اپلای با موفقیت خوانده شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'مرحله اپلای یافت نشد'
        ]);
    }
}
