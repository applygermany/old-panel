<?php

namespace App\Http\Controllers\Api\V1\Expert;

use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\AllUserResource;
use App\Http\Resources\Expert\UserResource;
use App\Http\Resources\Expert\UserResumeResource;
use App\Http\Services\V1\Expert\UserService;
use App\Models\Acceptance;
use App\Models\Option;
use App\Models\User;
use App\Models\UserComment;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    //expert search
    public function searchUser(Request $request){
        $BaseSearchValues = preg_split('/\s+/', trim($request->name), -1, PREG_SPLIT_NO_EMPTY);
        $users=User::where([['level',1],['firstname',$BaseSearchValues[0]],['lastname',$BaseSearchValues[1]]])->get();
        if(sizeof($users) <=0){
            $users=User::where([['level',1],['firstname',$BaseSearchValues[0]],['lastname',$BaseSearchValues[1].' '.$BaseSearchValues[2]]])->get();
        }

        foreach ($BaseSearchValues as $key=>$value){
            if(strlen($BaseSearchValues[$key])<5){
                unset($BaseSearchValues[$key]);
            }
        }
        if(sizeof($users) <=0){
            $request['baseSearchValue']=$BaseSearchValues;
            $users=User::orderBy('id','desc')->where('level',1)->when($request->name, function ($query) use ($request) {
                $searchValues = $request->baseSearchValue;

                return $query->where('firstname', $request->name)->orWhere(function ($q) use ($searchValues) {
                    $q->orWhere('firstname', 'like', "%{$searchValues[0]}%");
                    foreach ($searchValues as $value) {
                        if($searchValues[0]!=$value and sizeof($searchValues) > 1){
                            $q->orWhere('lastname', 'like', "%{$value}%");
                        }else{
                            $q->orWhere('lastname', 'like', "%{$value}%");
                        }
                    }
                    $q->orWhere('mobile', 'like', "%{$value}%");
                });
            })->get();
        }
        foreach ($users as $key=>$value){
            $users[$key]['acceptance']=Acceptance::where('user_id', $value->id)->first();
        }
        if ($users) {
            return response([
                'status' => 1,
                'msg' => 'لیست کاربران',
                'users' => AllUserResource::collection($users),
                'usersCount' => count($users),
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربری یافت نشد',
            ]);
        }
    }

    // get all users of expert
    public function getAllUser(Request $request)
    {
        $rules = [
            'name' => 'nullable|bad_chars',
            'category' => 'nullable',
        ];
        $customMessages = [
            'name.bad_chars' => 'نام وارد شده حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $users = $this->userService->getAllUser($request);
        if ($users) {
            return response([
                'status' => 1,
                'msg' => 'لیست کاربران',
                'users' => AllUserResource::collection($users[0]),
                'support_version' => Option::where('name', 'support_version')->first()->value,
                'categories' => $users[1],
                'usersCount' => $users[2],
                'telSupportAccess' => $users[3],
                'checkUsers'=>$users[0]
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربری یافت نشد',
            ]);
        }
    }
    public function getAllUserNew(Request $request)
    {
        $rules = [
            'name' => 'nullable|bad_chars',
            'category' => 'nullable',
        ];
        $customMessages = [
            'name.bad_chars' => 'نام وارد شده حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $users = $this->userService->getAllUser($request);
        $filterRepetitious=[];
//        foreach ($users[0] as $user){
//            $check=0;
//            foreach ($filterRepetitious as $filter){
//             if($filter['id']==$user['id']){
//                 $check=1;
//             }
//            }
//            if($check==0)
//                array_push($filterRepetitious,$user);
//        }
        if ($users) {
            return response([
                'status' => 1,
                'msg' => 'لیست کاربران',
                'users' => AllUserResource::collection($users),
                'support_version' => Option::where('name', 'support_version')->first()->value,
                'categories' => $users[1],
                'usersCount' => $users[2],
                'telSupportAccess' => $users[3]
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربری یافت نشد',
            ]);
        }
    }

    // get specific user
    public function getUser(User $user)
    {
        $user = $this->userService->getUser($user);
        if ($user) {
            return response([
                'status' => 1,
                'msg' => 'اطلاعات کاربر',
                'user' => new UserResource($user),
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    public function changeUser($user)
    {
        $user = $this->userService->changeUser($user);
        if ($user) {
            return response([
                'status' => 1,
                'msg' => 'با موفقیت انجام شد',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    public function changeUserType($type, $user)
    {
        $user = $this->userService->changeUserType($type, $user);
        if ($user) {
            return response([
                'status' => 1,
                'msg' => 'با موفقیت انجام شد',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    public function changeUploadAccess(Request $request)
    {
        $user = $this->userService->changeUploadAccess($request->userId);
        if ($user) {
            $user = User::find($request->userId);
            return response([
                'status' => 1,
                'uploadAccess' => $user->upload_access === 1,
                'msg' => 'با موفقیت انجام شد',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    // get specific user resume
    public function getResume(User $user)
    {
        return response([
            'status' => 1,
            'msg' => 'رزومه کاربر',
            'resume' => new UserResumeResource($user),
        ]);
    }

    // send comment for user
    public function sendComment(User $user, Request $request)
    {
        $rules = [
            'text' => 'required',
        ];
        $customMessages = [
            'text.required' => 'ورود متن الزامی ست',
//            'text.bad_chars' => 'متن وارد شده حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $save = $this->userService->sendComment($request, $user);
        if ($save) {
            return response([
                'status' => 1,
                'msg' => 'کامنت با موفقیت ثبت شد',
                'comments' => UserComment::where('user_id', $user->id)->get()
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    // send comment for free user
    public function sendCommentFree($id, Request $request)
    {
        $rules = [
            'text' => 'required',
        ];
        $customMessages = [
            'text.required' => 'ورود متن الزامی ست',
//            'text.bad_chars' => 'متن وارد شده حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $save = $this->userService->sendCommentFree($request, $id);
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

    // update comment for user
    public function updateComment(Request $request, UserComment $userComment)
    {
        $rules = [
            'text' => 'required',
        ];
        $customMessages = [
            'text.required' => 'ورود متن الزامی ست',
            //			'text.bad_chars' => 'متن وارد شده حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $save = $this->userService->updateComment($request, $userComment);
        if ($save) {
            return response([
                'status' => 1,
                'msg' => 'کامنت با موفقیت ویرایش شد',
                'comments' => UserComment::where('user_id', $userComment->user_id)->get()
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    // update comment for free user
    public function updateCommentFree(Request $request, $id)
    {
        $rules = [
            'text' => 'required',
        ];
        $customMessages = [
            'text.required' => 'ورود متن الزامی ست',
            //			'text.bad_chars' => 'متن وارد شده حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $save = $this->userService->updateCommentFree($request, $id);
        if ($save) {
            return response([
                'status' => 1,
                'msg' => 'کامنت با موفقیت ویرایش شد',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    // save category
    public function saveCategory(Request $request)
    {
        $rules = [
            'title' => 'required|bad_chars',
        ];
        $customMessages = [
            'title.required' => 'ورود عنوان الزامی ست',
            'title.bad_chars' => 'عنوان حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $save = $this->userService->saveCategory($request);
        if ($save) {
            return response([
                'status' => 1,
                'msg' => 'دسته بندی جدید با موفقیت ثبت شد',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'خطا در ثبت دسته بندی',
            ]);
        }
    }

    // delete category
    public function deleteCategory(Request $request)
    {
        $rules = [
            'id' => 'required|should_be_nums|should_be_pos',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $deleteCategory = $this->userService->deleteCategory($request);
        if ($deleteCategory) {
            return response([
                'status' => 1,
                'msg' => 'دسته بندی با موفقیت حذف شد',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'خطا در حذف دسته بندی',
            ]);
        }
    }

    // update user gategory
    public function updateUserCategory(Request $request)
    {
        $rules = [
            'id' => 'required|should_be_nums',
            'category' => 'required|should_be_nums',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $updateUserCategory = $this->userService->updateUserCategory($request);
        if ($updateUserCategory) {
            return response([
                'status' => 1,
                'msg' => 'دسته بندی کاربر با موفقیت تغییر کرد',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد',
            ]);
        }
    }

    public function saveUserContractInfo($id, Request $request)
    {
        $user = User::find($id);

        if (isset($request->category))
            $user->category_id = $request->category;

        if (isset($request->contractType))
            $user->contract_type = $request->contractType;

        $user->unable_to_work = $request->unableToWork ?? false;
        if ($request->unableToWork === true) {
            $user->type = 1;
        }
        if (isset($request->uploadAccess)) {
            $user->upload_access = $request->uploadAccess;
            $user->contract_open_id = auth()->guard('api')->id();
        }

        if ($user->save()) {
            return response([
                'status' => 1,
                'msg' => 'ذخیره با موفقیت انجام گردید',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'ذخیره با شکست مواجه گردید',
            ]);
        }
    }
}
