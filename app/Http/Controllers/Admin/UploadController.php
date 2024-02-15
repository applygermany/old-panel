<?php
namespace App\Http\Controllers\Admin;

use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UploadController extends Controller
{
    function uploads() {
        $uploads = Upload::orderBy('id','DESC')->paginate(10);
        $users = User::select('id','firstname','lastname')->get();
        return view('admin.uploads.uploads',compact('uploads','users'));
    }

    function getUploads(Request $request) {
        $upload = Upload::Query();
        if($request->searchUser)
            $upload->where('user_id',$request->searchUser);
        if($request->searchTitle)
            $upload->where('title','LIKE','%'.$request->searchTitle.'%');
        if($request->searchText)
            $upload->where('text','LIKE','%'.$request->searchText.'%');
        if($request->searchStartDate)
            $upload->where('date','>=',$request->searchStartDate);
        if($request->searchEndDate)
            $upload->where('date','<=',$request->searchEndDate);
        $uploads = $upload->orderBy('id','DESC')->paginate(10);
        return view('admin.uploads.list',compact('uploads'))->render();
    }
    
    function deleteUpload($id) {
        $upload = Upload::find($id);
        if($upload) {
            if($upload->delete()) {
                if (is_file(public_path('uploads/madarek/' . $upload->id . '.pdf')))
                    unlink(public_path('uploads/madarek/' . $upload->id . '.pdf'));
                session()->flash('success','مدرک حذف شد');
            }
        }
        else
            session()->flash('error','خطا در حذف مدرک');
        return redirect()->back();
    }
}
