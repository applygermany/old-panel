<?php
namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TeamController extends Controller
{
    public $ordering = 1;
    function teams() {
        $teams = Team::orderBy('ordering','ASC')->get();
        return view('admin.teams.teams',compact('teams'));
    }

    function getTeams() {
        $teams = Team::orderBy('ordering','ASC')->get();
        return view('admin.teams.list',compact('teams'))->render();
    }

    function saveTeam(Request $request) {
        $rules = [
            'name'=>'required|max:200|bad_chars',

            'field'=>'required|max:200|bad_chars',
        ];

        $customMessages = [
            'name.required' => 'ورود عنوان الزامی است',
            'name.max' => 'عنوان حداکثر باید 200 کاراکتر باشد',
            'name.bad_chars' => 'عنوان حاوی کاراکتر های غیر مجاز است',

            'field.required' => 'ورود شهر الزامی است',
            'field.max' => 'شهر حداکثر باید 200 کاراکتر باشد',
            'field.bad_chars' => 'شهر حاوی کاراکتر های غیر مجاز است',
        ];

        $validator = validator()->make($request->all(),$rules,$customMessages);

        if ($validator->fails())
        {
            session()->flash('error','خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $team = new Team();
        $team->name = $request->name;
        $team->field = $request->field;
//        if( $request->position){
//        $team->position = $request->position;
//
//        }
        if($team->save()) {
            if ($request->file('image')) {
                $folder = '/uploads/team/';
                $file = $request->file('image');
                $file->move(public_path().$folder,$team->id.'.jpg');
            }
            session()->flash('success','عضو تیم با موفقیت ثبت شد');
        } else
            session()->flash('error','خطا در ثبت عضو تیم');
        return redirect()->back();
    }

    function editTeam($id) {
        $team = Team::find($id);
        $data = csrf_field().'
<input name="editId" type="hidden" id="editId" value="'.$team->id.'">
<div class="row">
    <div class="form-group float-label col-6">
        <label for="editName" class="header-label">نام</label>
        <input name="editName" type="text" class="form-control" id="editName" value="'.$team->name.'">
    </div>
    <div class="form-group float-label col-6">
        <label for="editField" class="header-label">تخصص</label>
        <input name="editField" type="text" class="form-control" id="editField" value="'.$team->field.'">
    </div>

    <div class="form-group float-label col-12">
        <label for="editImage" class="header-label">تصویر</label>
        <input name="editImage" type="file" class="form-control" id="editImage">
    </div>
    <div class="form-group text-center col-12">
        <br>
        <img src="'.route('imageTeam',['id'=>$team->id,'ua'=>strtotime($team->updated_at)]).'" width="80%">
    </div>
</div>';
        return $data;
    }

    function updateTeam(Request $request) {
        $rules = [
            'editName'=>'required|max:200|bad_chars',
            'editField'=>'required|max:200|bad_chars'
        ];

        $customMessages = [
            'editName.required' => 'ورود نام الزامی است',
            'editName.max' => 'نام حداکثر باید 200 کاراکتر باشد',
            'editName.bad_chars' => 'نام حاوی کاراکتر های غیر مجاز است',

            'editField.required' => 'ورود تخصص الزامی است',
            'editField.max' => 'تخصص حداکثر باید 200 کاراکتر باشد',
            'editField.bad_chars' => 'تخصص حاوی کاراکتر های غیر مجاز است'
        ];
        $validator = validator()->make($request->all(),$rules,$customMessages);

        if ($validator->fails())
            return response()->json(['errors'=>$validator->errors()]);

        $team = Team::find($request->editId);
        $team->name = $request->editName;
        $team->field = $request->editField;
//        $team->position = $request->editPosition;
        if($team->save()) {
            if ($request->file('editImage')) {
                $folder = '/uploads/team/';
                $file = $request->file('editImage');
                $file->move(public_path().$folder,$team->id.'.jpg');
            }
            return 1;
        }
        return 2;
    }

    function updateTeamHeader(Request $request) {
        if ($request->file('image')) {
            $folder = '/uploads/';
            $file = $request->file('image');
            $file->move(public_path().$folder,'teamHeader.jpg');
        }
        session()->flash('error','تصویر با موفقیت بروز شد');
        return redirect()->back();
    }

    function deleteTeam($id) {
        $team = Team::find($id);
        if($team->delete()) {
            if (is_file(public_path('uploads/team/' . $team->id . '.jpg')))
                unlink(public_path('uploads/team/' . $team->id . '.jpg'));
            session()->flash('success','عضو تیم حذف شد');
        }
        else
            session()->flash('error','خطا در حذف عضو تیم');
        return redirect()->back();
    }

    function sortTeam(Request $request){
        $teams = $request->teams;
        foreach ($teams as $team) {
            Team::find($team['id'])->update(['ordering' => $this->ordering++]);
        }
        return 'success';
    }
}
