<?php

namespace App\Http\Controllers\Api\V1\Expert;

use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\UserDocumentCollection;
use App\Http\Resources\Expert\UserResource;
use App\Http\Services\V1\Expert\DocumentService;
use App\Mail\MailVerificationCode;
use App\Models\GenerateCode;
use App\Models\Motivation;
use App\Models\Resume;
use App\Models\Upload;
use App\Models\User;
use App\Providers\Notification;
use Illuminate\Http\Request;
use ZipArchive;
class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }
    public function downloadAllDocs($id){
        $user=User::where('id',$id)->first();
        $uploads=Upload::orderBy('id','desc')->where('user_id',$user->id)->get();
        $files=[];
        $checker=['1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'9'=>0,'10'=>0,'11'=>0,'12'=>0,'13'=>0,'14'=>0];
        foreach ($uploads as $key=>$value){

            if (is_file(public_path('uploads/madarek/' . $uploads[$key]->id . '.pdf'))){
                $pathOfFile=public_path('uploads/madarek/'.$uploads[$key]->id.'.pdf');
                $uploads[$key]['path_of_file']=$pathOfFile;
                if($uploads[$key]['type']==1){
                    $uploads[$key]['file_name']='School Leaving Certificate';
                }elseif ($uploads[$key]['type']==2){
                    $uploads[$key]['file_name']='University Entrance Qualification';
                }elseif ($uploads[$key]['type']==3){
                    $uploads[$key]['file_name']='Language Certificate';
                }elseif ($uploads[$key]['type']==4){
                    $uploads[$key]['file_name']='Passport';
                }elseif ($uploads[$key]['type']==5){
                    $uploads[$key]['file_name']='Job Experience';
                }elseif ($uploads[$key]['type']==7){
                    $uploads[$key]['file_name']='Contract';
                }elseif ($uploads[$key]['type']==8){
                    $uploads[$key]['file_name']='Bachelor Degree';
                }elseif ($uploads[$key]['type']==9){
                    $uploads[$key]['file_name']='Recommendation Letter';
                }elseif ($uploads[$key]['type']==11){
                    $uploads[$key]['file_name']='Course description';
                }elseif ($uploads[$key]['type']==12){
                    $uploads[$key]['file_name']='Grading System certificate(statement)';
                }
                $number=$uploads[$key]['type'];
                    if($checker[$number]!=0){
                        $uploads[$key]['file_name']=$uploads[$key]['file_name'].'-'.$checker[$uploads[$key]['type']];
                    }
                $checker[$number]+=1;

                array_push($files,$uploads[$key]);
            }
        }
        $zip = new ZipArchive;
//        $checkMotive=Motivation::where('user_id',$user->id)->where('url_uploaded_from_user','<>',null)->latest()->first();
//        if(isset($checkMotive)){
//            $checkMotive->url_uploaded_from_user=json_decode($checkMotive->url_uploaded_from_user, true);
//            foreach($checkMotive['url_uploaded_from_user'] as $userFile){
//                $fakeArray= [
//                    'id' => $checkMotive->id,
//                    'title' => 'انگیزه نامه',
//                    'date' => '',
//                    'url' => $userFile,
//                    'size' => '',
//                    'text' => '',
//                    'status'=>10,
//                    'mandatory' => 1,
//                    'type' => 2,
//                ];
//                array_push($mandatoryUploadArray,$fakeArray);
//            }
//        }
        $zipFileName = 'test.zip'; // Replace with your custom name
        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {//hameye upload ha daroone Documents
                if (file_exists($file['path_of_file'])) {//agar file vojood dasht
                    if($file->type!=7){
                        $folderName = 'Documents';
                    }else{
                        $folderName = 'Contract';
                    }
                    $newFileName = $folderName . '/' . $file['file_name'] . '.' . pathinfo($file['path_of_file'], PATHINFO_EXTENSION);
                    $zip->addFile($file['path_of_file'], $newFileName);
                }
            }
            $zip->close();
            // header add mikonim k majboor be download she
            return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
        } else {
            // ag nashod zip kone error bede
            return response()->json(['message' => 'Failed to create zip file'], 500);
        }

    }
    public function changeStatus(Request $request){
        $upload=Upload::find($request->id);
        if($request->status==1){//accept or untouched
            if($upload->status==2){
                $upload->status=1;
            }else{
                if($upload->status==0){
                    $upload->status=1;
                }else{
                    $upload->status=0;
                }
            }
            if($upload->save()){
                return ['msg'=>'تغییر وضعیت با موفقیت انجام شد','status'=>$upload['status'],'upload'=>$upload];
            }else{
                return ['msg'=>'خطایی رخ داد لطفاً مجدداً تلاش نمایید','status'=>$upload['status'],'upload'=>$upload];
            }
        }else{
            //reject file
            $upload->status=2;
            $upload->reasonOfReject=$request->reasonOfReject;
            $user=User::where('id',$upload['user_id'])->first();
            $name=$user->firstname.' '.$user->lastname;
            if($request->reasonOfReject){
                $reasonOfReject=$request->reasonOfReject;
            }else{
                $reasonOfReject=$upload->reasonOfReject;
            }
            $send = User::sendMail(new MailVerificationCode("reject_file_by_expert", [
                'name'=>$name,
                'fileName'=>$upload->title,
                'reasonOfReject'=>$reasonOfReject,
            ], "reject_file_by_expert"), $user->email);
            $notif = (new Notification("reject_file_by_expert",[
                'name'=>$name,
                'fileName'=>$upload->title,
                'reasonOfReject'=>$reasonOfReject,
            ]))->send($user->id);
            if (is_file(public_path('uploads/madarek/' . $upload->id . '.pdf')))
                unlink(public_path('uploads/madarek/' . $upload->id . '.pdf'));
            if($upload->delete()){
                return ['msg'=>'تغییر وضعیت و حذف فایل با موفقیت انجام شد','status'=>null,'upload'=>null];
            }else{
                return ['msg'=>'خطایی رخ داد لطفاً مجدداً تلاش نمایید','status'=>$upload['status'],'upload'=>$upload];
            }
        }

    }
    public function changeStatusOld(Request $request){
        $upload=Upload::find($request->id);
        if($request->status==1){//accept or untouched
            if($upload->status==2){
                $upload->status=1;
            }else{
                if($upload->status==0){
                    $upload->status=1;
                }else{
                    $upload->status=0;
                }
            }
        }else{//reject file
            $upload->status=2;
            $upload->reasonOfReject=$request->reasonOfReject;
            $user=User::where('id',$upload['user_id'])->first();
            $name=$user->firstname.' '.$user->lastname;
            if($request->reasonOfReject){
                $reasonOfReject=$request->reasonOfReject;
            }else{
                $reasonOfReject=$upload->reasonOfReject;
            }
            $send = User::sendMail(new MailVerificationCode("reject_file_by_expert", [
                'name'=>$name,
                'fileName'=>$upload->title,
                'reasonOfReject'=>$reasonOfReject,
            ], "reject_file_by_expert"), $user->email);
            $notif = (new Notification("reject_file_by_expert",[
                'name'=>$name,
                'fileName'=>$upload->title,
                'reasonOfReject'=>$reasonOfReject,
            ]))->send($user->id);
        }
        if($upload->save()){
            return ['msg'=>'تغییر وضعیت با موفقیت انجام شد','status'=>$upload['status'],'upload'=>$upload];
        }else{
            return ['msg'=>'خطایی رخ داد لطفاً مجدداً تلاش نمایید','status'=>$upload['status'],'upload'=>$upload];
        }
    }

    // get specific user document
    public function getDocument(User $user)
    {
        $documents = $this->documentService->getDocument($user);
        $generateCode=GenerateCode::where('user_id',0)->first();
        $timeCheck=$generateCode->expire_time+86400;
        if(!isset($generateCode->expire_time) or $timeCheck < time()){
            $generateCode->expire_time=time();
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $code = substr(str_shuffle($characters), 0, 5);
            $generateCode->generated_code=$code;
            $generateCode->save();
            $emails=explode(',',$generateCode->emails);
            foreach ($emails as $email){
                User::sendMail(new MailVerificationCode("generated_code", [
                    $generateCode->generated_code
                ], "generated_code"), $email);
            }
        }
        if ($documents){
            return response([
                'status' => 1,
                'msg' => 'مدارک کاربر',
                'documents' => new UserDocumentCollection($documents[0]),
                'user' => new UserResource($user),
                'importantFilesThatNotExist'=>$documents[1],
                'tokenDownloadAll'=>$generateCode->generated_code,
            ]);
        }else{
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد'
            ]);
        }
    }

    // delete specific document
    public function deleteDocument(Upload $upload)
    {
        $document = $this->documentService->deleteDocument($upload);

        if ($document){
            return response([
                'status' => 1,
                'msg' => 'مدرک با موفقیت حذف شد',
            ]);
        }else{
            return response([
                'status' => 0,
                'msg' => 'مدرک یافت نشد'
            ]);
        }
    }

    public function changeDocument(Upload $upload, Request $request)
    {
        $document = $this->documentService->changeDocument($upload, $request);

        if ($document){
            return response([
                'status' => 1,
                'msg' => 'تغییر نوع مدرک با موفقیت انجام گردید',
            ]);
        }else{
            return response([
                'status' => 0,
                'msg' => 'مدرک یافت نشد'
            ]);
        }
    }

}
