<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Services\V1\User\UploadService;
use App\Models\Option;
use App\Models\UploadTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    protected $upload;

    public function __construct(UploadService $upload)
    {
        $this->upload = $upload;
    }

    public function files()
    {

        return response()->json([
            'status' => 1,
            'msg' => "آپلود فایل",
            'files' => UploadTitle::get()
        ]);
    }

    public function uploads()
    {
        $uploads = $this->upload->uploads();

        if ($uploads != 0)
            return response([
                'status' => 1,
                'msg' => 'لیست مدارک',
                'mandatoryUploads' => $uploads[0],
                'mandatoryUploaded' => $uploads[1],
                'uploads' => $uploads[2],
                'admittance' => $uploads[3] == 7 ? 0 : ($uploads[3] == 8 ? 1 : 2),
                'contract' => $uploads[4],
                'uploadTitles' => $uploads[5],
                'admittanceFor'=>$uploads[6]
            ]);

        return response([
            'status' => 0,
            'msg' => 'درخواست پذیرش تکمیل نشده است',
            'mandatoryUploads' => [],
            'mandatoryUploaded' => [],
            'uploads' => [],
            'admittance' => '',
            'contract' => 0,
            'uploadTitles' => [],
            'admittanceFor'=>''

        ]);
    }

    public function uploadMandatoryFile(Request $request)
    {
        $rules = [
            'type' => 'required|should_be_nums',
            'title' => 'nullable|max:250|bad_chars',
            'file' => 'required|mimes:jpeg,png,jpg,pdf|max:50000'
        ];

        $customMessages = [
            'type.required' => 'نوع فایل را وارد کنید',
            'type.should_be_nums' => 'نوع فایل معتبر نیست',

            'title.max' => 'عنوان حداکثر 250 کاراکتر است',
            'title.bad_chars' => 'عنوان معتبر نیست',

            'file.required' => 'فایل را انتخاب کنید',
            'file.mimes' => 'پسوند فایل معتبر نیست',
            'file.max' => 'حجم فایل باید کمتر از 50 مگابایت باشد'
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails())
            return response()->json([
                'status' => 0,
                'msg' => 'لطفا عنوان را انتخاب کنید',
                'errors' => $validator->errors(),
            ]);

        $upload = $this->upload->uploadMandatoryFile($request);

        if ($upload)
            return response([
                'status' => 1,
                'msg' => 'فایل با موفقیت ارسال شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ذخیره فایل'
        ]);
    }

    public function uploadFile(Request $request)
    {
        $rules = [
            'title' => 'required|max:250|bad_chars',
            'file' => 'required|mimes:jpeg,png,jpg,pdf|max:50000'
        ];

        $customMessages = [
            'title.required' => 'عنوان را وارد کنید',
            'title.max' => 'عنوان حداکثر 250 کاراکتر است',
            'title.bad_chars' => 'عنوان معتبر نیست',

            'file.required' => 'فایل را انتخاب کنید',
            'file.mimes' => 'پسوند فایل معتبر نیست',
            'file.max' => 'حجم فایل باید کمتر از 50 مگابایت باشد'
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);

        $upload = $this->upload->uploadFile($request);

        if ($upload)
            return response([
                'status' => 1,
                'msg' => 'فایل با موفقیت ارسال شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ذخیره فایل'
        ]);
    }

    public function deleteUpload(Request $request)
    {
        $rules = [
            'id' => 'required|max:250|should_be_nums'
        ];

        $validator = validator()->make($request->all(), $rules);

        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);

        $upload = $this->upload->deleteUpload($request);

        if ($upload)
            return response([
                'status' => 1,
                'msg' => 'فایل با موفقیت حذف شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در حذف فایل'
        ]);
    }
}