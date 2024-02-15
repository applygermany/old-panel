<?php
namespace App\Http\Controllers\Admin;

use App\Models\Collaboration;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CollaborationController extends Controller
{
    function collaborations() {
        $collaborations = Collaboration::orderBy('id','DESC')->paginate(10);
        return view('admin.collaborations.collaborations',compact('collaborations'));
    }

    function getCollaborations(Request $request) {
        $collaboration = Collaboration::Query();
        if($request->searchName)
            $collaboration->where('name','LIKE','%'.$request->searchName.'%');
        if($request->searchFamily)
            $collaboration->where('family','LIKE','%'.$request->searchFamily.'%');
        if($request->searchEmail)
            $collaboration->where('email','LIKE','%'.$request->searchEmail.'%');
        if($request->searchField)
            $collaboration->where('field','LIKE','%'.$request->searchField.'%');
        $collaborations = $collaboration->orderBy('id','DESC')->paginate(10);
        return view('admin.collaborations.list',compact('collaborations'))->render();
    }
    
    function deleteCollaboration($id) {
        $collaboration = Collaboration::find($id);
        if($collaboration->delete()) {
            $files = glob(public_path('uploads/resumeCollaboration/'.$id.'.*'));
            if(count($files) > 0) {
                if (is_file($files[0]))
                    unlink($files[0]);
            }
            session()->flash('success','درخواست همکاری حذف شد');
        }
        else
            session()->flash('error','خطا در حذف درخواست همکاری');
        return redirect()->back();
    }
}
