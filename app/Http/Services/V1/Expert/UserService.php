<?php

namespace App\Http\Services\V1\Expert;

use App\Http\Services\V1\User\DidarService;
use App\Models\Upload;
use App\Providers\SMS;
use App\Models\Category;
use App\Models\User;
use App\Models\UserComment;
use App\Models\UserSupervisor;
use App\Models\UserTelSupport;
use App\Providers\MyHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getAllUserOld(Request $request)
    {
        $usersCount = UserSupervisor::Query();
        $usersCount->join('users', 'user_supervisors.user_id', '=', 'users.id');
        $usersCount->where('users.type', 2)->where('user_supervisors.supervisor_id', auth()->guard('api')->id());
        $usersCount = $usersCount->count();
        $user = UserSupervisor::Query();
        $user->join('users', 'user_supervisors.user_id', '=', 'users.id');
        $user->where('user_supervisors.supervisor_id', auth()->guard('api')->id());
        if ($request->name) {
            $user->leftJoin('acceptances', function ($join) {
                $join->on('user_supervisors.user_id', '=', 'acceptances.user_id');
            });

            if (str_contains($request->name, '@')) {
                $user->where(function ($query) use ($request) {
                    $query->orWhereRaw("LOWER(`nag_users`.`email`) LIKE '%" . strtolower(MyHelpers::numberToEnglish($request->name)) . "%'");
                });
            } elseif (is_numeric(MyHelpers::numberToEnglish($request->name))) {
                $user->where(function ($query) use ($request) {
                    $query->orWhereRaw("`nag_acceptances`.`phone` LIKE '%" . MyHelpers::numberToEnglish($request->name) . "%'");
                });
            } else {
                $user->where(function ($query) use ($request) {
                    $query->orWhereRaw("LOWER(`nag_users`.`firstname`) LIKE '%" . strtolower($request->name) . "%'");
                    $query->orWhereRaw("LOWER(`nag_users`.`lastname`) LIKE '%" . strtolower($request->name) . "%'");
                });
            }
        }

        if (isset($request->category)) {
            foreach ($request->category as $cat) {
                if ($cat['value'] === 'contract') {
                    $contracts = Upload::where('type', 7)->whereIn('user_id', $user->pluck('users.id'))->pluck('user_id');
                    $user->whereIn('users.id', $contracts);
                }
                if ($cat['value'] === 'special') {
                    $user->where('users.type', 2);
                }
                if ($cat['value'] === 'base') {
                    $user->where('users.type', 3);
                }
                if ($cat['value'] === 'normal') {
                    $user->where('users.type', 1);
                }
                if (is_numeric($cat['value'])) {
                    $user->where('users.category_id', $cat['value']);
                }
            }
        }

        $users = $user->select(
            'users.id',
            'users.email',
            'users.firstname',
            'users.lastname',
            'users.mobile',
            'users.type',
            'users.level',
            'users.max_university_count',
            'users.category_id',
            'users.upload_access',
            'users.updated_at'
        )
            ->orderBy('user_supervisors.id', 'DESC')->get();
        $categories = Category::all();

        $permission = auth()->guard('api')->user()->admin_permissions;

        return [$users->take($request->take), $categories, count($users), ($permission->sup_tel === 1 ? true : false)];
    }
    public function getAllUser(Request $request)
    {
        $usersCount = UserSupervisor::Query();
        $BaseSearchValues = preg_split('/\s+/', trim($request->name), -1, PREG_SPLIT_NO_EMPTY);
        foreach ($BaseSearchValues as $key=>$value){
            if(strlen($BaseSearchValues[$key])<4){
                unset($BaseSearchValues[$key]);
            }
        }
        $usersCount->join('users', 'user_supervisors.user_id', '=', 'users.id');
        $usersCount->where('users.type', 2)->where('user_supervisors.supervisor_id', auth()->guard('api')->id());
        $usersCount = $usersCount->count();
        $user = UserSupervisor::Query();
        $user->join('users', 'user_supervisors.user_id', '=', 'users.id');
        $user->where('user_supervisors.supervisor_id', auth()->guard('api')->id());
        if ($request->name) {
            $user->leftJoin('acceptances', function ($join) {
                $join->on('user_supervisors.user_id', '=', 'acceptances.user_id');
            });

            if (str_contains($request->name, '@')) {
                $user->where(function ($query) use ($request) {
                    $query->orWhereRaw("LOWER(`nag_users`.`email`) LIKE '%" . strtolower(MyHelpers::numberToEnglish($request->name)) . "%'");
                });
            } elseif (is_numeric(MyHelpers::numberToEnglish($request->name))) {
                $user->where(function ($query) use ($request) {
                    $query->orWhereRaw("`nag_acceptances`.`phone` LIKE '%" . MyHelpers::numberToEnglish($request->name) . "%'");
                });
            } else {
                if(sizeof($BaseSearchValues) > 1){
                    $user->where(function ($query) use ($BaseSearchValues) {
                        $query->whereRaw("LOWER(`nag_users`.`firstname`) LIKE '%" . strtolower($BaseSearchValues[0]) . "%'");
                        $query->whereRaw("LOWER(`nag_users`.`lastname`) LIKE '%" . strtolower($BaseSearchValues[1]) . "%'");
                    });
                    $user->where(function ($query) use ($BaseSearchValues) {

                        $query->whereRaw("LOWER(`nag_users`.`lastname`) LIKE '%" . strtolower($BaseSearchValues[1]) . "%'");
                        $query->orWhereRaw("LOWER(`nag_users`.`firstname`) LIKE '%" . strtolower($BaseSearchValues[0]) . "%'");
                    });
                }else{
                    $user->where(function ($query) use ($request) {
                        $query->orWhereRaw("LOWER(`nag_users`.`firstname`) LIKE '%" . strtolower($request->name) . "%'");
                        $query->orWhereRaw("LOWER(`nag_users`.`lastname`) LIKE '%" . strtolower($request->name) . "%'");
                    });
                }
            }
        }

        if (isset($request->category)) {
            foreach ($request->category as $cat) {
                if ($cat['value'] === 'contract') {
                    $contracts = Upload::where('type', 7)->whereIn('user_id', $user->pluck('users.id'))->pluck('user_id');
                    $user->whereIn('users.id', $contracts);
                }
                if ($cat['value'] === 'special') {
                    $user->where('users.type', 2);
                }
                if ($cat['value'] === 'base') {
                    $user->where('users.type', 3);
                }
                if ($cat['value'] === 'normal') {
                    $user->where('users.type', 1);
                }
                if (is_numeric($cat['value'])) {
                    $user->where('users.category_id', $cat['value']);
                }
            }
        }

        $users = $user->select(
            'users.id',
            'users.email',
            'users.firstname',
            'users.lastname',
            'users.mobile',
            'users.type',
            'users.level',
            'users.max_university_count',
            'users.category_id',
            'users.upload_access',
            'users.updated_at'
        )
            ->orderBy('user_supervisors.id', 'DESC')->get();
        $categories = Category::all();

        $permission = auth()->guard('api')->user()->admin_permissions;

        return [$users->take($request->take), $categories, count($users), ($permission->sup_tel === 1 ? true : false)];
    }
    public function getUser(User $user)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $user->id)->first()) {
            return $user;
        } else {
            return 0;
        }
    }

    public function changeUser($id)
    {
        $logged_user = auth()->guard('api')->user();
        $user = $logged_user->users()->where('user_id', $id)->first();
        if ($user) {
            $user = User::find($id);
            $user->acceptances()->delete();
            $user->telSupports()->delete();
            $user->uploads()->delete();
            $user->userTelSupports()->delete();
            //$user->universities()->delete();
            UserSupervisor::where("user_id", $user->id)->delete();
            UserTelSupport::where(DB::raw("UNIX_TIMESTAMP(tel_date) "), ">", time())->where('user_id', $user->id)->delete();
            $user->contract_code = null;
            $user->type = 1;
            return $user->save();
        } else {
            return 0;
        }
    }

    public function changeUserType($type, $id)
    {
        $logged_user = auth()->guard('api')->user();
        $user = $logged_user->users()->where('user_id', $id)->first();
        if ($user) {
            $user = User::find($id);
            $user->type = $type;
            $user->package_at = Carbon::now();
            return $user->save();
        } else {
            return 0;
        }
    }

    public function changeUploadAccess($id)
    {
        $logged_user = auth()->guard('api')->user();
        $user = $logged_user->users()->where('user_id', $id)->first();
        if ($user) {
            $user = User::find($id);
            $user->contract_open_id = auth()->guard('api')->id();
            $user->upload_access = $user->upload_access == 1 ? 0 : 1;
            if ($user->upload_access == 1) {
                (new SMS())->sendVerification($user->mobile, 'contract_ready', "name==$user->firstname $user->lastname");
            }
            return $user->save();
        } else {
            return 0;
        }
    }

    public function sendComment(Request $request, User $user)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $user->id)->first()) {
            $userComment = new UserComment();
            $userComment->text = $request->text;
            $userComment->user_id = $user->id;
            $userComment->owner_id = Auth::guard('api')->id();
            $userComment->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function sendCommentFree(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $userComment = new UserComment();
            $userComment->text = $request->text;
            $userComment->user_id = $user->id;
            $userComment->owner_id = Auth::guard('api')->id();
            $userComment->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function updateComment(Request $request, UserComment $userComment)
    {
        $logged_user = auth()->guard('api')->user();

        $user = $userComment->user;
        if ($logged_user->users()->where('user_id', $user->id)->first()) {
            $userComment = UserComment::where('user_id', $user->id)->where('owner_id', Auth::guard('api')->id())->first();
            $userComment->text = $request->text;
            $userComment->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function updateCommentFree(Request $request, $id)
    {
        $userComment = UserComment::find($id);
        $user = $userComment->user;
        if ($user) {
            $userComment = UserComment::where('user_id', $user->id)->where('owner_id', Auth::guard('api')->id())->first();
            if ($userComment) {
                $userComment->text = $request->text;
                $userComment->save();
            } else {
                $userComment = new UserComment();
                $userComment->text = $request->text;
                $userComment->user_id = $user->id;
                $userComment->owner_id = Auth::guard('api')->id();
                $userComment->save();
            }

            return 1;
        } else {
            return 0;
        }
    }

    public function saveCategory(Request $request)
    {
        $category = new Category();
        $category->title = $request->title;
        if ($category->save())
            return 1;
        return 0;
    }

    public function deleteCategory(Request $request)
    {
        $category = Category::find($request->id);
        if ($category) {
            foreach ($category->users()->get() as $user) {
                $user->category_id = 0;
                $user->save();
            }
            $category->delete();
            return 1;
        }
        return 0;
    }

    public function updateUserCategory(Request $request)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $request->id)->first()) {
            $user = User::find($request->id);
            if ($user) {
                $user->category_id = $request->category;
                $user->save();
                return 1;
            }
        }
        return 0;
    }
}
