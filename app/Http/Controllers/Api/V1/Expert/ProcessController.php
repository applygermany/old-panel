<?php

namespace App\Http\Controllers\Api\V1\Expert;

use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\UserProcessCollection;
use App\Http\Services\V1\Expert\ProcessService;
use App\Models\UserUniversity;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    protected $processService;

    public function __construct(ProcessService $processService)
    {
        $this->processService = $processService;
    }

    // get processes
    public function getProcesses(Request $request)
    {
        $users = $this->processService->getProcesses($request);

        $countArray = array();
        $countArray[0] = 0;
        foreach ($users['users'] as $user) {
            $universities = UserUniversity::where('user_id',$user->user_id)->count();
            array_push($countArray,$universities);
        }
        rsort($countArray);

        return response([
            'status' => 1,
            'msg' => 'پیشرفت پرونده',
            'processes' => new UserProcessCollection($users['users']),
            'experts' => $users['experts'],
            'categories' => $users['categories'],
            'maxUniversityCount' => $countArray[0]
        ]);
    }

    public function updateProcess(Request $request)
    {
        $process = $this->processService->updateProcess($request);

        if ($process)
            return response([
                'status' => 1,
                'msg' => 'عملیات با موفقیت انجام شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'کاربر یافت نشد'
        ]);
    }

    public function updateProcessUniversity(Request $request)
    {
        $process = $this->processService->updateProcessUniversity($request);

        if ($process)
            return response([
                'status' => 1,
                'msg' => 'عملیات با موفقیت انجام شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'کاربر یافت نشد'
        ]);
    }
}
