<?php

namespace App\Http\Services\V1\Expert;

use App\Http\Services\V1\User\DidarService;
use App\Models\Acceptance;
use App\Models\Category;
use App\Models\Upload;
use App\Models\User;
use App\Models\UserSupervisor;
use App\Models\UserTelSupport;
use App\Providers\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkExperienceService
{
    public function getHistory(Request $request)
    {
        $users = User::query();
        if ($request->name) {
            $users->where(function ($query) use ($request) {
                $query->orWhereRaw("LOWER(`nag_users`.`firstname`) LIKE '%" . strtolower($request->name) . "%'");
                $query->orWhereRaw("LOWER(`nag_users`.`lastname`) LIKE '%" . strtolower($request->name) . "%'");
                $query->orWhereRaw("LOWER(`nag_users`.`mobile`) LIKE '%" . MyHelpers::numberToEnglish($request->name) . "%'");
                $query->orWhereRaw("LOWER(`nag_users`.`email`) LIKE '%" . strtolower(MyHelpers::numberToEnglish($request->name)) . "%'");
            });
        }

        if ($request->category) {
            if ($request->category === -1 ) {
                $uploads = Upload::whereIn('user_id', $users->pluck('id'))->where('type', 7)->pluck('user_id');
                $users = $users->whereIn('id', $uploads);
            }
            elseif ($request->category != 0) {
                $users = $users->where('category_id', $request->category);
            }

        }

        $date = Carbon::now();
        $acceptances = Acceptance::whereDate('created_at', '<=', $date)->whereIn('user_id', $users->pluck('id'))->pluck('user_id');

        $_telSupports = UserTelSupport::select(DB::raw('DISTINCT user_id, COUNT(*) AS count'))
            ->whereIn('user_id', $acceptances)
            ->whereDate('created_at', '<=', $date)
            ->where('supervisor_id',  auth()->guard('api')->id())
            ->groupBy('user_id')
            ->get();
        $results = [];
        $index = 0;
        $counter = 0;
        foreach ($_telSupports as $item) {
            $user = User::find($item->user_id);
            $uploadContract = Upload::where('user_id', $user->id)->where('type', 7)->first();
            if ($uploadContract) {
                $contractDate = explode('/', $uploadContract->date);
                $contractDate = \App\Providers\JDF::jalali_to_gregorian($contractDate[0], $contractDate[1], $contractDate[2], '-');
                $firstSup = UserTelSupport::orderBy('id','asc')->where('user_id', $user->id)->where('tel_date', '<=', $contractDate)->first();
                if ($firstSup->supervisor_id === intval(auth()->guard('api')->id())) {
                    if($index <= $request->take) {
                        $results[$index] = [
                            "id" => $item->user->id,
                            "firstname" => $item->user->firstname,
                            "lastname" => $item->user->lastname,
                            "email" => $item->user->email,
                            "mobile" => $item->user->mobile,
                            "type" => $item->user->type,
                            "upload_access" => (bool)$item->user->uploads()->where('type', 7)->first(),
                            "title" => $firstSup->title,
                            "date" => $firstSup->telSupport->day_tel_fa,
                            "from_time" => $firstSup->telSupport->from_time,
                            "to_time" => $firstSup->telSupport->to_time
                        ];
                        $index++;
                    }

                    $counter++;
                }
            }
        }
        return [$results, $counter];
    }

    public function changeUploadAccess($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->upload_access = $user->upload_access == 1 ? 0 : 1;
            if ($user->upload_access == 1) {
                (new SMS())->sendVerification($user->mobile, 'contract_ready', "name==$user->firstname $user->lastname");
            }

//            $didarService = new DidarService();
//            $didarService->updateTable('Field_996_3_19', $user->upload_access == 1 ? true : false, $user->id);
//            $didarService->updateDidarApi($user->id);

            return $user->save();
        } else {
            return 0;
        }
    }
}
