<?php

namespace App\Http\Controllers\Admin;

use App\Models\Acceptance;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\TelSupportTag;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Services\V1\Statics\Reports;
use App\Models\Comment;
use App\Models\Upload;
use App\Models\UserTelSupport;
use Carbon\Carbon;

class DashboardController extends Controller
{

    function chatSection(){
        return view('admin.chat');
    }

    function dashboard($month = null)
    {
        $stat = new Reports;

        $userChart = $stat->userChart($month);
        $contractChart = $stat->contractChart($month);
        $transactionChart = $stat->transactionChart($month);
        $acquaintedChart = $stat->acquaintedChart($month);

        $allUsers = $stat->allUsers();
        $normalUsers = $stat->normalUser();
        $specialUsers = $stat->specialUser();
        $baseUsers = $stat->baseUser();
        $factors = $stat->transaction(1, "sum");
        $successTransactions = $stat->transaction(1, "count");;
        $allTransactions = $stat->transaction(-1, "count");;
        $universities = $stat->university();
        $acceptances = (object)$stat->acceptance();
        $acquainteds = $stat->acquainteds();
        $transactionTypes = $stat->transactionTypes();
        $all_special_contracts = $stat->allSpecialContracts();
        $all_base_contracts = $stat->allBaseContracts();

        return view('admin.dashboard', compact(
            'acceptances',
            'transactionTypes',
            'allUsers',
            'normalUsers',
            'specialUsers',
            'baseUsers',
            'factors',
            'successTransactions',
            'allTransactions',
            'universities',
            'acquainteds',
            'userChart',
            'transactionChart',
            'acquaintedChart',
            'all_base_contracts',
            'all_special_contracts',
            'contractChart',
            'month'
        ));
    }

    function settings()
    {
        $settings = Setting::find(1);
        return view('admin.settings', compact('settings'));
    }

    function updateSettings(Request $request)
    {
        $rules = [
            // 'email'=>'required|max:250|email',
            // 'mobile'=>'required|max:250|should_be_nums',
            // 'phone'=>'required|max:250|should_be_nums',
            // 'responseTime'=>'required',
            // 'address'=>'required'
        ];
        $customMessages = [
            'email.required' => 'ایمیل را وارد کنید',
            'email.max' => 'ایمیل حداکثر باید 250 کاراکتر باشد',
            'email.email' => 'ایمیل معتبر نیست',
            'mobile.required' => 'موبایل را وارد کنید',
            'mobile.max' => 'موبایل حداکثر باید 250 کاراکتر باشد',
            'mobile.should_be_nums' => 'موبایل معتبر نیست',
            'phone.required' => 'شماره ثابت را وارد کنید',
            'phone.max' => 'شماره ثابت حداکثر باید 250 کاراکتر باشد',
            'phone.should_be_nums' => 'شماره ثابت معتبر نیست',
            'responseTime.required' => 'زمان پاسخگویی را وارد کنید',
            'address.required' => 'آدرس را وارد کنید',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $settings = Setting::find(1);
        $settings->email = $request->email;
        $settings->mobile = $request->mobile;
        $settings->phone = $request->phone;
        $settings->response_time = $request->responseTime;
        $settings->address = $request->address;
        if ($settings->save())
            session()->flash('success', 'تنظیمات ذخیره شد');
        else
            session()->flash('error', 'خطا در ذخیره تنظیمات');
        return redirect()->back();
    }

    function telSupportTags()
    {
        $telSupportTags = TelSupportTag::all();
        return view('admin.telSupportTags.telSupportTags', compact('telSupportTags'));
    }

    function saveTelSupportTag(Request $request)
    {
        $rules = [
            'title' => 'required|max:200|bad_chars',
            'value' => 'required|should_be_nums',
            'type' => 'required|should_be_nums',
        ];
        $customMessages = [
            'title.required' => 'ورود عنوان الزامی است',
            'title.max' => 'عنوان حداکثر باید 200 کاراکتر باشد',
            'title.bad_chars' => 'عنوان حاوی کاراکتر های غیر مجاز است',
            'value.required' => 'ورود مقدار الزامی است',
            'value.should_be_nums' => 'مقدار باید عدد باشد',
            'type.required' => 'ورود نوع تگ الزامی است',
            'type.should_be_nums' => 'نوع تگ معتبر نیست',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $university = new TelSupportTag();
        $university->title = $request->title;
        $university->value = $request->value;
        $university->type = $request->type;
        if ($university->save())
            session()->flash('success', 'تگ با موفقیت ثبت شد');
        else
            session()->flash('error', 'خطا در ثبت تگ');
        return redirect()->back();
    }

    function deleteTelSupportTag($id)
    {
        $telSupportTag = TelSupportTag::find($id);
        if ($telSupportTag->delete())
            session()->flash('success', 'تگ با موفقیت حذف شد');
        else
            session()->flash('error', 'خطا در حذف تگ');
        return redirect()->back();
    }

    function notification()
    {
        $users = User::select('id', 'firstname', 'lastname')->get();
        return view('admin.notification', compact('users'));
    }

    function sendNotification(Request $request)
    {
        $rules = [
            'title' => 'required|max:250',
            'text' => 'required',
            'sendType' => 'required',
        ];
        $customMessages = [
            'title.required' => 'عنوان را وارد کنید',
            'title.max' => 'عنوان حداکثر باید 250 کاراکتر باشد',
            'text.required' => 'متن را وارد کنید',
            'sendType.required' => 'نوع ارسال را انتخاب کنید',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $client = new \GuzzleHttp\Client();

        if ($request->sendType === 'users' || $request->sendType === 'user') {
            if (empty($request->searchUser)) {
                $request->searchUser = "all";
            }
            $client->request("POST", "https://chat.applygermany.net/notification", [
                'json' => [
                    'to' => $request->searchUser,
                    'body' => $request->text,
                    'title' => $request->title,
                ],
                "headers" => [
                    "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
                ],
            ]);
        } else if ($request->sendType === 'hasContract') {
            $contracts = Upload::where('type', 7)->get();
            foreach ($contracts as $contract) {
                $client->request("POST", "https://chat.applygermany.net/notification", [
                    'json' => [
                        'to' => $contract->user_id,
                        'body' => $request->text,
                        'title' => $request->title,
                    ],
                    "headers" => [
                        "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
                    ],
                ]);
            }
        } else if ($request->sendType === 'normalUsers') {
            $users = User::where('level', 1)->where('type', 1)->where('status', 1)->get();
            foreach ($users as $user) {
                $client->request("POST", "https://chat.applygermany.net/notification", [
                    'json' => [
                        'to' => $user->id,
                        'body' => $request->text,
                        'title' => $request->title,
                    ],
                    "headers" => [
                        "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
                    ],
                ]);
            }
        } else if ($request->sendType === 'baseUsers') {
            $users = User::where('level', 1)->where('type', 3)->where('status', 1)->get();
            foreach ($users as $user) {
                $client->request("POST", "https://chat.applygermany.net/notification", [
                    'json' => [
                        'to' => $user->id,
                        'body' => $request->text,
                        'title' => $request->title,
                    ],
                    "headers" => [
                        "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
                    ],
                ]);
            }
        } else if ($request->sendType === 'specialUsers') {
            $users = User::where('level', 1)->where('type', 2)->where('status', 1)->get();
            foreach ($users as $user) {
                $client->request("POST", "https://chat.applygermany.net/notification", [
                    'json' => [
                        'to' => $user->id,
                        'body' => $request->text,
                        'title' => $request->title,
                    ],
                    "headers" => [
                        "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
                    ],
                ]);
            }
        } else if ($request->sendType === 'hasTelSupport') {
            $telSupports = UserTelSupport::whereDate('created_at', '>=', Carbon::now())->where('user_id', '<>', null)->get();

            foreach ($telSupports as $telSupport) {
                $client->request("POST", "https://chat.applygermany.net/notification", [
                    'json' => [
                        'to' => $telSupport->user_id,
                        'body' => $request->text,
                        'title' => $request->title,
                    ],
                    "headers" => [
                        "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
                    ],
                ]);
            }
        } else if ($request->sendType === 'userComments') {
            $comments = Comment::where('type', 2)->where('status', 1)->get();

            foreach ($comments as $comment) {
                $client->request("POST", "https://chat.applygermany.net/notification", [
                    'json' => [
                        'to' => $comment->author,
                        'body' => $request->text,
                        'title' => $request->title,
                    ],
                    "headers" => [
                        "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
                    ],
                ]);
            }
        }
        session()->flash('success', 'نوتیفیکیشن ارسال شد');
        return redirect()->back();
    }

    function logout()
    {
        auth()->logout();
        return redirect()->route('admin.loginForm')->with('success', 'شما با موفقیت از اکانت خود خارج شدید');
    }

    function importData()
    {
        return view('admin.imports');
    }

    function doImports(Request $request)
    {
        $request->file('json')->move(public_path() . '/uploads/json/', $request->file('json')->getClientOriginalName());
        $json = file_get_contents(public_path() . '/uploads/json/' . $request->file('json')->getClientOriginalName());
        $data = json_decode($json);
        $array1 = (array)$data;

        foreach ($array1 as $data) {
            if ($data->type === 'table') {
                foreach ($data->data as $data2) {
                    if (DB::table(substr($data->name, 4))->where('id', $data2->id)->count() === 0)
                        DB::table(substr($data->name, 4))->insert((array)$data2);
                }
                break;
            }
        }

        session()->flash('success', 'بارگذاری با موفقیت انجام گردید');
        return redirect()->back();
    }
}
