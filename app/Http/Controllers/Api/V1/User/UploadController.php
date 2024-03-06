<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Services\V1\User\UploadService;
use App\Models\Motivation;
use App\Models\Option;
use App\Models\Resume;
use App\Models\Upload;
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

    public function getWriterFile($id,$fileName,$type){
        if($type=='motivation'){
            $check=Motivation::where([['id',$id],['user_id',auth()->guard('api')->id()]])->first();
        }elseif ($type=='resume'){
            $check=Resume::where([['id',$id],['user_id',auth()->guard('api')->id()]])->first();
        }
        if(isset($check)){
            if (is_file(public_path('uploads/WriterFile/' . $fileName)))
                $file = public_path('uploads/WriterFile/' . $fileName);
            else
                return 'فایل یافت نشد';
            header('Content-Type:' . mime_content_type($file));
            header('Cache-Control: no-cache, must-revalidate');
            header('Content-Length: ' . filesize($file));

            return response()->download($file);
        }else{
            return 'شما اجازه دسترسی به این فایل را ندارید';
        }

    }
    public function getFileNameAndFormat($type,$id){
        if(Upload::where('id',$id)->first()->user_id != auth()->guard('api')->id()){
            return ['status'=>0,'msg'=>'شما اجازه دسترسی به این فایل را ندارید','fileName'=>null];
        }else{
            if (is_file(public_path('uploads/'.$type.'/' . $id . '.pdf'))) {
                $file = public_path('uploads/'.$type.'/' . $id . '.pdf');
                $fileName=$id . '.pdf';
            } elseif (is_file(public_path('uploads/'.$type.'/' . $id . '.jpg'))) {
                $file = public_path('uploads/'.$type.'/' . $id . '.jpg');
                $fileName=$id . '.jpg';
            } elseif (is_file(public_path('uploads/'.$type.'/' . $id . '.rar'))) {
                $file = public_path('uploads/'.$type.'/' . $id . '.rar');
                $fileName=$id . '.rar';
            } elseif (is_file(public_path('uploads/'.$type.'/' . $id . '.zip'))) {
                $file = public_path('uploads/'.$type.'/' . $id . '.zip');
                $fileName=$id.'.zip';
            }else{
                return ['status'=>0,'msg'=>'فایل مورد نظر وجود ندارد','fileName'=>null];
            }
            return ['status'=>1,'msg'=>'فرایند دانلود شروع شد','fileName'=>$fileName];
        }

    }

    public function downloadFiles($type,$id){

        if(Upload::where('id',$id)->first()->user_id != auth()->guard('api')->id()){
            return ['status'=>0,'msg'=>'شما اجازه دسترسی به این فایل را ندارید','fileName'=>null];
        }else {
            if (is_file(public_path('uploads/' . $type . '/' . $id . '.pdf'))) {
                $file = public_path('uploads/' . $type . '/' . $id . '.pdf');
                $fileName = $id . '.pdf';
            } elseif (is_file(public_path('uploads/' . $type . '/' . $id . '.jpg'))) {
                $file = public_path('uploads/' . $type . '/' . $id . '.jpg');
                $fileName = $id . '.jpg';
            } elseif (is_file(public_path('uploads/' . $type . '/' . $id . '.rar'))) {
                $file = public_path('uploads/' . $type . '/' . $id . '.rar');
                $fileName = $id . '.rar';
            } elseif (is_file(public_path('uploads/' . $type . '/' . $id . '.zip'))) {
                $file = public_path('uploads/' . $type . '/' . $id . '.zip');
                $fileName = $id . '.zip';
            }
            return response()->download($file, $fileName);

        }

    }


    public function downloadPureContract($id){

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
