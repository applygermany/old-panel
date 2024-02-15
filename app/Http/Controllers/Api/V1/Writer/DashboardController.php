<?php

namespace App\Http\Controllers\Api\V1\Writer;

use App\Http\Controllers\Controller;
use App\Http\Services\V1\Writer\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    function index(Request $request)
    {
        $rules = [
            'name'     => 'nullable|bad_chars',
            'category' => 'nullable|should_be_nums',
        ];
        $customMessages = [
            'name.bad_chars'          => 'نام وارد شده حاوی کاراکتر های غیر مجاز است',
            'category.should_be_nums' => 'دسته بندی معتبر نیست',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg'    => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $orders = $this->service->getAllRM($request);
        if ($orders) {
            return response([
                'status'      => 1,
                'msg'         => 'لیست سفارشات',
                'orders'      => $orders[0],
                'categories'  => $orders[1],
                'ordersCount' => $orders[2],
            ]);
        } else {
            return response([
                'status' => 0,
                'msg'    => 'کاربر یافت نشد',
            ]);
        }
    }

    public function changeDarkMode()
    {
        $this->service->changeDarkMode();
        return response(['status' => 1, 'msg' => 'اعمال شد']);
    }

}
