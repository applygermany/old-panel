<?php

namespace App\Http\Controllers\Api\V1\Writer;

use App\Http\Controllers\Controller;
use App\Mail\MailVerificationCode;
use App\Models\Motivation;
use App\Models\Resume;
use App\Models\User;
use App\Providers\Notification;
use Illuminate\Http\Request;
use PDF;

class ResumeController extends Controller
{
    function getResume($id)
    {
        $resume = Resume::find($id);

        $output = new \stdClass();
        $output = $resume;
        $output->image = route('imageResume', ['id' => $resume->id, 'ua' => strtotime($resume->user->updated_at)]);
        $output->educations = $resume->educationRecords;
        $output->languages = $resume->languages;
        $output->works = $resume->works;
        $output->softwareKnowledges = $resume->softwareKnowledges;
        $output->courses = $resume->courses;
        $output->researchs = $resume->researchs;
        $output->hobbies = $resume->hobbies;

        $files = array();
        if ($resume->url_uploaded_from_user) {
            $file = new \stdClass();
            $file->title = "دانلود فایل 1 ";
            $file->file = $resume->url_uploaded_from_user;
            $files[0] = $file;
        }
        $output->files = $files;
        $output->pdf = url('api/v1/writer/exports/pdf', ['type' => 'resume', 'id' => $resume->id]);

        return response([
            'status' => 1,
            'msg' => 'اطلاعات رزومه',
            'resume' => $output,
            'acceptance' => $resume->user->acceptances[0],
        ]);
    }

    function getFiles($id, $type)
    {
        $data = null;
        if ($type === 'motivation') {
            $data = Motivation::find($id);
        } else {
            $data = Resume::find($id);
        }

        $files = array();
        if ($data->url_uploaded_from_writer) {
            $index = 0;
            foreach (json_decode($data->url_uploaded_from_writer) as $key => $item) {
                $file = new \stdClass();
                $file->title = ($type === 'motivation' ? " انگیزه نامه " : " رزومه ") . ($key + 1);
                $file->file = $item;
                $files[$index] = $file;
                $index++;
            }
        }

        return response([
            'status' => 1,
            'msg' => 'اطلاعات ',
            'user' => $data->user,
            'files' => $files,
        ]);
    }

    function uploadFile(Request $request)
    {
        $rules = [
            'type' => 'required|should_be_nums',
            'title' => 'nullable|max:250|bad_chars',
            'file' => 'required|max:50000'
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

        $data = null;
        if ($request->dataType === 'motivation') {
            $data = Motivation::find($request->dataId);
        } else {
            $data = Resume::find($request->dataId);
        }

        $index = 0;
        if ($data->url_uploaded_from_writer) {
            foreach (json_decode($data->url_uploaded_from_writer) as $key => $item) {
                $index++;
            }
        }

        $folder = '/uploads/WriterFile/';
        $file = $request->file('file');
        $fileName = $this->getFileName(public_path() . $folder, $request->dataType . '_' . $request->dataId, $index, $request->file('file')->getClientOriginalExtension());
        $file->move(public_path() . $folder, $fileName);
        $admins = json_decode($data->url_uploaded_from_writer, true);
        if (!is_array($admins)) {
            $admins = [];
        }
        $admins[] = route('writerFile', ["id" => $fileName]);
        $data->url_uploaded_from_writer = json_encode($admins);

        $files = array();
        if ($data->url_uploaded_from_writer) {
            $index = 0;
            foreach (json_decode($data->url_uploaded_from_writer) as $key => $item) {
                $file = new \stdClass();
                $file->title = ($request->dataType === 'motivation' ? " انگیزه نامه " : " رزومه ") . ($key + 1);
                $file->file = $item;
                $files[$index] = $file;
                $index++;
            }
        }

        $data->status = 7;

        if ($data->save()) {
            $name = $data->user->firstname . ' ' . $data->user->lastname;
            $writerName = $data->writer->firstname . ' ' . $data->writer->lastname;
            $order_admin = User::where('level', 4)->where("admin_permissions", "like", '%"orders":1%')->get();
            foreach ($order_admin as $admin) {
                $send = User::sendMail(new MailVerificationCode("writer_uploaded_document", [
                    $request->dataType === 'motivation' ? 'انگیزه نامه' : 'رزومه',
                    $name,
                    $writerName,
                    $data->id,
                ], "writer_uploaded_document"), $admin->email);
                $notif = (new Notification("writer_uploaded_document", [
                    $request->dataType === 'motivation' ? 'انگیزه نامه' : 'رزومه',
                    $name,
                    $writerName,
                    $data->id,
                ]))->send($admin->id);
            }

            return response([
                'status' => 1,
                'msg' => 'فایل با موفقیت ارسال شد',
                'files' => $files,
                'p' => $fileName
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'ارسال فایل با شکست مواجه گردید'
            ]);
        }
    }

    function deleteFile(Request $request)
    {
        $data = null;
        if ($request->type === 'motivation') {
            $data = Motivation::find($request->id);
        } else {
            $data = Resume::find($request->id);
        }

        $files = array();
        if ($data->url_uploaded_from_writer) {
            $index = 0;
            foreach (json_decode($data->url_uploaded_from_writer) as $key => $item) {
                if ($request->file !== $item) {
                    $files[$index] = $item;
                    $index++;
                }
            }
        }

        $data->url_uploaded_from_writer = $files;
        if ($data->save()) {
            return response([
                'status' => 1,
                'msg' => 'فایل با موفقیت حذف گردید',
                'files' => $files
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'حذف فایل با شکست مواجه گردید'
            ]);
        }
    }

    public function generateExport($type, $id)
    {
        $data = null;
        if ($type === 'motivation') {
            $data = Motivation::find($id);

            $pdf = PDF::loadView('admin.motivations.preview.pdf', [
                'motivation' => $data
            ], [], [
                'subject' => $data->id . 'انگیزه نامه ',
            ]);
        } else {
            $data = Resume::find($id);
            $pdf = PDF::loadView('admin.resumes.preview.pdf', [
                'resume' => $data
            ], [], [
                'subject' => $data->id . 'رزومه ',
            ]);
        }
        return $pdf->stream($data->id . ' ' . $data->created_at . '.pdf');
    }

    private function getFileName($string, $id, $index = 0, $type)
    {
        if (is_file($string . $id . "_" . $index . '.' . $type)) {
            return $this->getFileName($string, $id, $index + 1, $type);
        }
        return $id . "_" . $index . '.' . $type;
    }
}
