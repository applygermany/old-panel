<?php

namespace App\Http\Services\V1\Expert;

use App\Models\Motivation;
use App\Models\Resume;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentService
{
    public function getDocument(User $user)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $user->id)->first()) {
            $uploads = Upload::orderBy('id', 'desc')->where('user_id', $user->id)->get();
            if (sizeof($uploads) > 0) {
                $newUpload = [];
                foreach ($uploads as $key => $value) {
                    $checkMotivation = Motivation::where('id', $uploads[$key]['text'])->first();
                    $checkResume = Resume::where('id', $uploads[$key]['text'])->first();
                    $check = 0;
                    if (isset($checkMotivation)) {
                        foreach ($newUpload as $new) {
                            if ($new['id'] == $uploads[$key]['id'] || $new['realTitle'] == 'انگیزه نامه') {
                                $check = 1;
                            }
                        }
                        if ($check == 0) {
                            $uploads[$key]['realTitle'] = 'انگیزه نامه';
                            array_push($newUpload, $uploads[$key]);
                        }
                    } elseif (isset($checkResume)) {
                        foreach ($newUpload as $new) {
                            if ($new['id'] == $uploads[$key]['id'] || $new['realTitle'] == 'رزومه') {
                                $check = 1;
                            }
                        }
                        if ($check == 0) {
                            $uploads[$key]['realTitle'] = 'رزومه';
                            array_push($newUpload, $uploads[$key]);
                        }
                    } else {
                        array_push($newUpload, $uploads[$key]);
                    }
                }
                $acceptance = User::where('id',$user->id)->with('acceptances')->first();

                if($acceptance->admittance == 'کالج'){
                    $importantTypes=[['type'=>1,'title'=>'تمامی مدارک دبیرستان'],['type'=>2,'title'=>'گواهی قبولی کنکور'],['type'=>3,'title'=>'مدرک زبان'],['type'=>4,'title'=>'پاسپورت'],['type'=>6,'title'=>'عکس پرسنلی'],['type'=>7,'title'=>'قرارداد']];
                }else{
                    $importantTypes=[['type'=>1,'title'=>'تمامی مدارک دبیرستان'],['type'=>2,'title'=>'گواهی قبولی کنکور'],['type'=>3,'title'=>'مدرک زبان'],['type'=>4,'title'=>'پاسپورت'],['type'=>6,'title'=>'عکس پرسنلی'],['type'=>7,'title'=>'قرارداد'],['type'=>8,'title'=>'تمامی مدارک دوره کارشناسی'],['type'=>11,'title'=>'گواهی شرح دروس']];
                }
                $importantThatNotExst=[];
                foreach ($importantTypes as $type){
                    $check=0;
                    foreach ($newUpload as $new){
                        if($type['type']==$new['type']){
                            $check=1;
                        }
                    }
                    if($check==0){
                        array_push($importantThatNotExst,$type);
                    }
                }

                return [$newUpload,$importantThatNotExst];
            } else {
                return[$uploads,[]];
            }
        } else {
            return 0;
        }
    }
    public function getDocumentOld(User $user)
    {
        $logged_user = auth()->guard('api')->user();
        if ($logged_user->users()->where('user_id', $user->id)->first()) {
            $uploads = Upload::orderBy('id', 'desc')->where('user_id', $user->id)->get();
            if (sizeof($uploads) > 0) {
                $newUpload = [];
                foreach ($uploads as $key => $value) {
                    $checkMotivation = Motivation::where('id', $uploads[$key]['text'])->first();
                    $checkResume = Resume::where('id', $uploads[$key]['text'])->first();
                    $check = 0;
                    if (isset($checkMotivation)) {
                        foreach ($newUpload as $new) {
                            if ($new['id'] == $uploads[$key]['id'] || $new['title'] == 'انگیزه نامه') {
                                $check = 1;
                            }
                        }
                        if ($check == 0) {
                            $uploads[$key]['text'] = 'انگیزه نامه';
                            array_push($newUpload, $uploads[$key]);
                        }
                    } elseif (isset($checkResume)) {
                        foreach ($newUpload as $new) {
                            if ($new['id'] == $uploads[$key]['id'] || $new['type'] == 'رزومه') {
                                $check = 1;
                            }
                        }
                        if ($check == 0) {
                            $uploads[$key]['text'] = 'رزومه';
                            array_push($newUpload, $uploads[$key]);
                        }
                    } else {
                        array_push($newUpload, $uploads[$key]);
                    }
                }
                
                $acceptance = auth()->guard('api')->user()->acceptances()->first();

                if($acceptance->admittance == 'کالج'){
                    $importantTypes=[['type'=>1,'title'=>'تمامی مدارک دبیرستان'],['type'=>2,'title'=>'گواهی قبولی کنکور'],['type'=>3,'title'=>'مدرک زبان'],['type'=>4,'title'=>'پاسپورت'],['type'=>6,'title'=>'عکس پرسنلی'],['type'=>7,'title'=>'قرارداد']];
                }else{
                    $importantTypes=[['type'=>1,'title'=>'تمامی مدارک دبیرستان'],['type'=>2,'title'=>'گواهی قبولی کنکور'],['type'=>3,'title'=>'مدرک زبان'],['type'=>4,'title'=>'پاسپورت'],['type'=>6,'title'=>'عکس پرسنلی'],['type'=>7,'title'=>'قرارداد'],['type'=>8,'title'=>'تمامی مدارک دوره کارشناسی'],['type'=>11,'title'=>'گواحی شرح دروس']];
                }
                $importantThatNotExst=[];
                foreach ($importantTypes as $type){
                    $check=0;
                    foreach ($newUpload as $new){
                        if($type['type']==$new['type']){
                            $check=1;
                        }
                    }
                    if($check==0){
                        array_push($importantThatNotExst,$type);
                    }
                }

                return [$newUpload,$importantThatNotExst];
            } else {
                return $uploads;
            }
        } else {
            return 0;
        }
    }
    public function getDocumentOld0(User $user)
    {
        $logged_user = auth()->guard('api')->user();
        if($logged_user->users()->where('user_id' , $user->id)->first()){
            $uploads=Upload::orderBy('id','desc')->where('user_id',$user->id)->get();
            if(sizeof($uploads) > 0){//not showing same type and by the way it should be the last one
                //            $uploads=$user->uploads;
                $newUpload=[];
                foreach ($uploads as $upload){
                    $check=0;
                    if($upload['type']==12 and ($upload['text']!='null' and isset($upload['text']))){
                        foreach ($newUpload as $new){
                            if($new['id']==$upload['id'] || $new['type'] ==$upload['type']){
                                $check=1;
                            }
                        }
                    }
                    if($check==0)
                        array_push($newUpload,$upload);
                }
            }else{
                return $uploads;
            }

            foreach ($newUpload as $key=>$value){
                if(isset($newUpload[$key]['text']) and $newUpload[$key]['text']!='null'){
                    $checkMotivation=Motivation::where('id',$newUpload[$key]['text'])->first();
                    if(isset($checkMotivation)){
                        $newUpload[$key]['text']='انگیزه نامه';
                    }else{
                        $checkResume=Resume::where('id',$newUpload[$key]['text'])->first();
                        if(isset($checkResume)){
                            $newUpload[$key]['text']='رزومه';
                        }
                    }
                }
            }
            return $newUpload;
        }else{
            return 0;
        }
    }

    public function deleteDocument(Upload $upload)
    {
        $logged_user = auth()->guard('api')->user();

        $user = $upload->user;

        if($logged_user->users()->where('user_id' , $user->id)->first()){
            if (is_file(public_path('uploads/madarek/' . $upload->id . '.pdf')))
                unlink(public_path('uploads/madarek/' . $upload->id . '.pdf'));

            $upload->delete();

            return 1;
        }else{
            return 0;
        }
    }

    public function changeDocument(Upload $upload, Request $request)
    {
//        $logged_user = auth()->guard('api')->user();
//        $user = $upload->user;
//
//        if($logged_user->users()->where('user_id' , $user->id)->first()){
//            if (is_file(public_path('uploads/madarek/' . $upload->id . '.pdf')))
//                unlink(public_path('uploads/madarek/' . $upload->id . '.pdf'));
//        }

        $upload->type = $request->type;
        $upload->title = $request->title;

        if($upload->save())
            return 1;
        return 0;
    }

}
