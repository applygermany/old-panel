<?php

namespace App\Http\Controllers\Admin;

use App\ExcelExports\UserWebinars;
use App\Exports\ContractExport;
use App\Exports\ResumeExport;
use App\Exports\TelSupportExport;
use App\Exports\WorkExperienceExport;
use App\Http\Controllers\Controller;
use App\Models\Acceptance;
use App\Models\Category;
use App\Models\Comment;
use App\Models\TelSupport;
use App\Models\Upload;
use App\Models\User;
use App\Models\UserComment;
use App\Models\UserTelSupport;
use App\Models\UserUniversity;
use App\Providers\MyHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    function telSupportResult()
    {
        $telSupports = UserTelSupport::orderBy('tel_date', 'desc')->where('user_id', '<>', '0')->where('tel_date', '<', Carbon::today())->paginate(20);
        $users = User::select('id', 'firstname', 'lastname', 'mobile', 'email', 'level')->get();
        $categories = Category::all();
        $ids = UserTelSupport::orderBy('tel_date', 'desc')->where('user_id', '<>', '0')->pluck('id');
        return view('admin.reports.result.telSupports', compact('telSupports', 'users', 'categories', 'ids'));
    }

    function telSupportsReport()
    {
        $telSupports = UserTelSupport::orderBy('id', 'desc')->where('user_id', '<>', '0')->paginate(20);
        $users = User::select('id', 'firstname', 'lastname', 'mobile', 'email', 'level')->get();
        $categories = Category::all();
        $ids = UserTelSupport::orderBy('id', 'desc')->where('user_id', '<>', '0')->pluck('id');
        return view('admin.reports.telSupports', compact('telSupports', 'users', 'categories', 'ids'));
    }

    function telSupportsReportComments($id)
    {
        $comments = UserComment::where('user_id', $id)->get();
        return view('admin.reports.partials.comments', compact('comments'));
    }

    function telSupportsReportSearch(Request $request)
    {
        $telSupport = UserTelSupport::orderBy('id', 'desc')->where('user_id', '<>', '0');
        if ($request->userType) {
            $tels = TelSupport::where('type', $request->userType)->pluck('id');
            $telSupport->whereIn('tel_support_id', $tels);
        }
        if ($request->searchTerm) {
            $users = User::where('category_id', $request->searchTerm)->pluck('id');
            $telSupport->whereIn('user_id', $users);
        }
        if ($request->searchUser) {
            $telSupport->where('user_id', $request->searchUser);
        }
        if ($request->searchSupport) {
            $telSupport->where('supervisor_id', $request->searchSupport);
        }
        if ($request->searchStartDate) {
            $tels = TelSupport::where('day_tel_fa', '>=', MyHelpers::numberToEnglish($request->searchStartDate))->pluck('id');
            $telSupport->whereIn('tel_support_id', $tels);
        }
        if ($request->searchEndDate) {
            $tels = TelSupport::where('day_tel_fa', '<=', MyHelpers::numberToEnglish($request->searchEndDate))->pluck('id');
            $telSupport->whereIn('tel_support_id', $tels);
        }
        $telSupports = $telSupport->orderBy('id', 'DESC')->paginate(20);
        $users = User::select('id', 'firstname', 'lastname', 'mobile', 'email', 'level')->get();
        $categories = Category::all();
        $ids = $telSupport->orderBy('id', 'DESC')->pluck('id');
        return view('admin.reports.partials.listTelSupports', compact('telSupports', 'users', 'categories', 'ids'));
    }

    function telSupportsResultSearch(Request $request)
    {
        $telSupport = UserTelSupport::orderBy('tel_date', 'desc')->where('user_id', '<>', '0');
        $telSupport = $telSupport->where('tel_date', '<', Carbon::today());
        if ($request->userType) {
            $tels = TelSupport::where('type', $request->userType)->pluck('id');
            $telSupport->whereIn('tel_support_id', $tels);
        }
        if ($request->searchTerm) {
            $users = User::where('category_id', $request->searchTerm)->pluck('id');
            $telSupport->whereIn('user_id', $users);
        }
        if ($request->searchUser) {
            $telSupport->where('user_id', $request->searchUser);
        }
        if ($request->searchSupport) {
            $telSupport->where('supervisor_id', $request->searchSupport);
        }
        if ($request->searchStartDate) {
            $tels = TelSupport::where('day_tel_fa', '>=', MyHelpers::numberToEnglish($request->searchStartDate))->pluck('id');
            $telSupport->whereIn('tel_support_id', $tels);
        }
        if ($request->searchEndDate) {
            $tels = TelSupport::where('day_tel_fa', '<=', MyHelpers::numberToEnglish($request->searchEndDate))->pluck('id');
            $telSupport->whereIn('tel_support_id', $tels);
        }
        $telSupports = $telSupport->orderBy('tel_date', 'DESC')->paginate(20);
        $users = User::select('id', 'firstname', 'lastname', 'mobile', 'email', 'level')->get();
        $categories = Category::all();
        $ids = $telSupport->orderBy('tel_date', 'DESC')->pluck('id');
        return view('admin.reports.result.listTelSupports', compact('telSupports', 'users', 'categories', 'ids'));
    }

    function telSupportsReportExport(Request $request)
    {
        $telSupport = UserTelSupport::orderBy('id', 'desc')->where('user_id', '<>', '0');
        if ($request->exportUsertype) {
            $tels = TelSupport::where('type', $request->exportUsertype)->pluck('id');
            $telSupport->whereIn('tel_support_id', $tels);
        }
        if ($request->exportTerm) {
            $users = User::where('category_id', $request->exportTerm)->pluck('id');
            $telSupport->whereIn('user_id', $users);
        }
        if ($request->exportUser) {
            $telSupport->where('user_id', $request->exportUser);
        }
        if ($request->exportSupervisor) {
            $telSupport->where('supervisor_id', $request->exportSupervisor);
        }
        if ($request->exportFromDate) {
            $tels = TelSupport::where('day_tel_fa', '>=', MyHelpers::numberToEnglish($request->exportFromDate))->pluck('id');
            $telSupport->whereIn('tel_support_id', $tels);
        }
        if ($request->exportToDate) {
            $tels = TelSupport::where('day_tel_fa', '<=', MyHelpers::numberToEnglish($request->exportToDate))->pluck('id');
            $telSupport->whereIn('tel_support_id', $tels);
        }
        $telSupports = $telSupport->orderBy('id', 'DESC')->get();
        $categories = Category::all();

        $data = new \stdClass();
        foreach ($telSupports as $telSupport) {
            $std = new \stdClass();
            $std->id = $telSupport->id;
            $std->user = $telSupport->user->firstname . ' ' . $telSupport->user->lastname;
            $std->date = $telSupport->telSupport->day_tel_fa . ' - ' . $telSupport->telSupport->from_time . ' تا ' . $telSupport->telSupport->to_time;
            $std->title = $telSupport->title;
            $std->category = $categories->where('id', $telSupport->user->category_id)->first() ?
                $categories->where('id', $telSupport->user->category_id)->first()->title : '----';
            $std->supervisor = $telSupport->supervisor->firstname . ' ' . $telSupport->supervisor->lastname;
            $std->type = $telSupport->telSupport->type === 1 ? 'عادی' : 'ویژه';
            $std->price = $telSupport->telSupport->price === 0 ? 'رایگان' : $telSupport->telSupport->price;
            $data->data[] = $std;
        }

        return Excel::download(new TelSupportExport($data->data), 'telSupports_' . time() . '.xlsx');
    }

    function workExperience()
    {
        $admins = User::whereIn('level', [3, 5, 7])->where('status', 1)->paginate(10);
        return view('admin.reports.workExperience', compact('admins'));
    }

    function getWorkExperience(Request $request)
    {
        $request->searchMobile = MyHelpers::numberToEnglish($request->searchMobile);
        $admin = User::Query();

        if ($request->searchFirstname)
            $admin->where('firstname', 'LIKE', '%' . $request->searchFirstname . '%');
        if ($request->searchLastname)
            $admin->where('lastname', 'LIKE', '%' . $request->searchLastname . '%');
        if ($request->searchMobile)
            $admin->where('mobile', 'LIKE', '%' . $request->searchMobile . '%');
        if ($request->searchEmail)
            $admin->where('email', 'LIKE', '%' . $request->searchEmail . '%');
        $admins = $admin->whereIn('level', [3, 5])->orderBy('id', 'DESC')->paginate(10);
        return view('admin.reports.partials.adminsList', compact('admins'))->render();
    }

    function workExperienceList($id)
    {
        $date = Carbon::now();
        $acceptances = Acceptance::whereDate('created_at', '<=', $date)->pluck('user_id');

        $_telSupports = UserTelSupport::select(DB::raw('DISTINCT user_id, COUNT(*) AS count'))
            ->whereIn('user_id', $acceptances)
            ->whereDate('created_at', '<=', $date)
            ->where('supervisor_id', $id)
            ->groupBy('user_id')
            ->get();

        $telSupports = array();
        $contracts = array();
        foreach ($_telSupports as $telSupport) {
            $user = User::find($telSupport->user_id);
            $uploadContract = Upload::where('user_id', $user->id)->where('type', 7)->first();
            if ($uploadContract) {
                $contractDate = explode('/', $uploadContract->date);
                $contractDate = \App\Providers\JDF::jalali_to_gregorian($contractDate[0], $contractDate[1], $contractDate[2], '-');
                $firstSup = UserTelSupport::orderBy('id','asc')->where('user_id', $user->id)->where('tel_date', '<=', $contractDate)->first();
                if ($firstSup->supervisor_id === intval($id)) {
                    $contracts[] = [
                        'name' => $user->firstname . ' ' . $user->lastname,
                        'date' => $user->acceptances()->first()->created_at,
                        'count' => $telSupport->count,
                        'id' => $user->id,
                        'supervisorId' => $id,
                        'term' => $user->category_id ? Category::find($user->category_id)->title : '-',
                        'contract' => Upload::where('user_id', $user->id)->where('type', 7)->first() ? 'قرارداد دارد' : '-'
                    ];
                }
            }
            $telSupports[] = [
                'name' => $user->firstname . ' ' . $user->lastname,
                'date' => $user->acceptances()->first()->created_at,
                'count' => $telSupport->count,
                'id' => $user->id,
                'supervisorId' => $id,
                'term' => $user->category_id ? Category::find($user->category_id)->title : '-',
                'contract' => Upload::where('user_id', $user->id)->where('type', 7)->first() ? 'قرارداد دارد' : '-'
            ];
        }

        $admin = User::find($id);
        return view('admin.reports.workExperienceList', compact('telSupports', 'admin', 'contracts'))->render();
    }

    function workExperienceListUser($id, $supervisorId)
    {
        $telSupports = UserTelSupport::orderBY('id', 'desc')
            ->where('user_id', $id)
            ->where('supervisor_id', $supervisorId)
            ->get();
        $categories = Category::all();
        $admin = User::find($supervisorId);
        return view('admin.reports.workExperienceUser', compact('admin', 'categories', 'telSupports'));
    }

    function workExperienceExport($id)
    {
        $date = Carbon::now();
        $acceptances = Acceptance::whereDate('created_at', '<=', $date)->pluck('user_id');

        $_telSupports = UserTelSupport::select(DB::raw('DISTINCT user_id, COUNT(*) AS count'))
            ->whereIn('user_id', $acceptances)
            ->whereDate('created_at', '<=', $date)
            ->where('supervisor_id', $id)
            ->groupBy('user_id')
            ->get();

        $data = new \stdClass();
        foreach ($_telSupports as $telSupport) {
            $user = User::find($telSupport->user_id);
            $uploadContract = Upload::where('user_id', $user->id)->where('type', 7)->first();
            if ($uploadContract) {
                $contractDate = explode('/', $uploadContract->date);
                $contractDate = \App\Providers\JDF::jalali_to_gregorian($contractDate[0], $contractDate[1], $contractDate[2], '-');
                $firstSup = UserTelSupport::orderBy('id','asc')->where('user_id', $user->id)->where('tel_date', '<=', $contractDate)->first();
                if ($firstSup->supervisor_id === $id) {
                    $std = new \stdClass();
                    $std->user = $user->firstname . ' ' . $user->lastname;
                    $std->count = $telSupport->count;
                    $std->date = $date;
                    $std->term = $user->category_id ? Category::find($user->category_id)->title : '-';

                    $data->data[] = $std;
                }
            }
        }

        return Excel::download(new WorkExperienceExport($data->data), 'workExperience_' . time() . '.xlsx');
    }

    function workExperienceUserExport($id, $supervisorId)
    {
        $date = Carbon::now();
        $telSupports = UserTelSupport::orderBy('id', 'desc')->where('user_id', $id)
            ->whereDate('created_at', '<=', $date)
            ->where('supervisor_id', $supervisorId)->get();
        $categories = Category::all();

        $data = new \stdClass();
        foreach ($telSupports as $_telSupports) {
            $std = new \stdClass();
            $std->id = $_telSupports->id;
            $std->user = $_telSupports->user->firstname . ' ' . $_telSupports->user->lastname;
            $std->title = $_telSupports->title;
            $std->date = $_telSupports->telSupport->day_tel_fa . ' - ' . $_telSupports->telSupport->from_time . ' تا ' . $_telSupports->telSupport->to_time;
            $std->category = $categories->where('id', $_telSupports->user->category_id)->first() ?
                $categories->where('id', $_telSupports->user->category_id)->first()->title : '----';

            $data->data[] = $std;
        }

        return Excel::download(new WorkExperienceExport($data->data), 'workExperience_' . time() . '.xlsx');
    }

    function contracts()
    {
        $users = User::orderBy('id', 'desc')->where('level', 1)->get();
        $categories = Category::all();

        $unique = Upload::where('type', 7)->get()->unique(['user_id']);
        $contracts = Upload::where('type', 7)->get()->diff($unique)->pluck('id');
        $contracts = Upload::orderBy('date', 'desc')->whereNotIn('id', $contracts)->where('type', 7)->paginate(10);
        return view('admin.reports.contracts', compact('contracts', 'users', 'categories'));
    }

    function contractsSearch(Request $request)
    {
        $users = User::orderBy('id', 'desc')->where('level', 1)->get();
        $categories = Category::all();
        $contracts = Upload::query();

        $_users = User::orderBy('id', 'desc')->where('level', 1);
        if ($request->userType) {
            $ids = $_users->where('type', $request->userType)->pluck('id');
            $contracts = $contracts->whereIn('user_id', $ids);
        }
        if ($request->searchTerm) {
            $ids = $_users->where('category_id', $request->searchTerm)->pluck('id');
            $contracts = $contracts->whereIn('user_id', $ids);
        }
        if ($request->searchUser) {
            $contracts = $contracts->where('user_id', $request->searchUser);
        }
        if ($request->searchStartDate) {
            $contracts = $contracts->where('date', '>=', MyHelpers::numberToEnglish($request->searchStartDate));
        }
        if ($request->searchEndDate) {
            $contracts = $contracts->where('date', '<=', MyHelpers::numberToEnglish($request->searchEndDate));
        }

        $contracts = $contracts->orderBy('id', 'desc')->where('type', 7)->paginate(10);
        return view('admin.reports.partials.contractList')
            ->with(['users' => $users, 'categories' => $categories, 'contracts' => $contracts])
            ->render();
    }

    function contractExport(Request $request)
    {
        $categories = Category::all();
        $contracts = Upload::query();

        $_users = User::orderBy('id', 'desc')->where('level', 1);
        if ($request->exportUserType) {
            $ids = $_users->where('type', $request->exportUserType)->pluck('id');
            $contracts = $contracts->whereIn('user_id', $ids);
        }
        if ($request->exportTerm) {
            $ids = $_users->where('category_id', $request->exportTerm)->pluck('id');
            $contracts = $contracts->whereIn('user_id', $ids);
        }
        if ($request->exportUser) {
            $contracts = $contracts->where('user_id', $request->exportUser);
        }
        if ($request->exportFromDate) {
            $contracts = $contracts->where('date', '>=', MyHelpers::numberToEnglish($request->exportFromDate));
        }
        if ($request->exportToDate) {
            $contracts = $contracts->where('date', '<=', MyHelpers::numberToEnglish($request->exportToDate));
        }

        $contracts = $contracts->orderBy('id', 'desc')->where('type', 7)->get();
        return Excel::download(new \App\ExcelExports\ContractExport($contracts, $categories), 'contracts.xlsx');
    }
}
