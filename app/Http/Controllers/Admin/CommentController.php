<?php
namespace App\Http\Controllers\Admin;

use App\Models\AdminComment;
use App\Models\Comment;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    function comment() {
        $comment = AdminComment::orderBy('id','DESC')->get();
        return view('admin.comments.comment',compact('comment'));
    }

    function getComment() {
        $comment = AdminComment::orderBy('id','DESC')->get();
        return view('admin.comments.comment',compact('comment'))->render();
    }

    function saveComment(Request $request) {
        $rules = [
            //'name'=>'required|max:200|bad_chars',
            //'field'=>'required|max:200|bad_chars',
            'text'=>'required|max:400|bad_chars',
            //'university'=>'required|max:200|bad_chars',
            'rating'=>'required|int|min:1|max:5',
        ];

        $customMessages = [
            'name.required' => 'ورود نام الزامی است',
            'name.max' => 'نام حداکثر باید 200 کاراکتر باشد',
            'name.bad_chars' => 'نام حاوی کاراکتر های غیر مجاز است',

            'field.required' => 'ورود فیلد الزامی است',
            'field.max' => 'فیلد حداکثر باید 200 کاراکتر باشد',
            'field.bad_chars' => 'فیلد حاوی کاراکتر های غیر مجاز است',

            'text.required' => 'ورود متن الزامی است',
            'text.max' => 'متن حداکثر باید 400 کاراکتر باشد',
            'text.bad_chars' => 'متن حاوی کاراکتر های غیر مجاز است',

            'university.required' => 'ورود دانشگاه الزامی است',
            'university.max' => 'دانشگاه حداکثر باید 200 کاراکتر باشد',
            'university.bad_chars' => 'دانشگاه حاوی کاراکتر های غیر مجاز است',


            'rating.required' => 'ورود امتیاز الزامی است',
            'rating.int' => 'عدد بین 1 و 5',


        ];

        $validator = validator()->make($request->all(),$rules,$customMessages);

        if ($validator->fails())
        {
            session()->flash('error','خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

     

        $comment = new AdminComment();
        $comment->name = $request->name;
        $comment->field = $request->field;
        $comment->rating = strval($request->rating);
        $comment->text = $request->text;
        $comment->university = $request->university;
        if ($request->file('photo')) {
            $time  = time();
            $folder = '/uploads/adminComment/';
            $file = $request->file('photo');
            $file->move(public_path() . $folder, $time . '.png');
           
            $comment->photo = route("adminCommentPhoto", ["id" =>  $time]);
         
        }else{
            $comment->photo = "https://api.applygermany.net/avatar_light.svg";
        }
        if($comment->save()) {
            session()->flash('success','کامنت با موفقیت ثبت شد');
        } else
            session()->flash('error','خطا در ثبت کامنت ');
        return redirect()->back();
    }
    function editComment($id) {
        $comment = AdminComment::find($id);
        return view('admin.comments.edit', compact('comment'));
    }
    function updateComment(Request $request) {
        $rules = [
            'name'=>'required|max:200|bad_chars',
            'field'=>'required|max:200|bad_chars',
            'text'=>'required|max:400|bad_chars',
            'university'=>'required|max:200|bad_chars',
            'rating'=>'required|int|min:1|max:5',
        ];

        $customMessages = [
            'name.required' => 'ورود نام الزامی است',
            'name.max' => 'نام حداکثر باید 200 کاراکتر باشد',
            'name.bad_chars' => 'نام حاوی کاراکتر های غیر مجاز است',

            'field.required' => 'ورود فیلد الزامی است',
            'field.max' => 'فیلد حداکثر باید 200 کاراکتر باشد',
            'field.bad_chars' => 'فیلد حاوی کاراکتر های غیر مجاز است',

            'text.required' => 'ورود متن الزامی است',
            'text.max' => 'متن حداکثر باید 400 کاراکتر باشد',
            'text.bad_chars' => 'متن حاوی کاراکتر های غیر مجاز است',

            'university.required' => 'ورود دانشگاه الزامی است',
            'university.max' => 'دانشگاه حداکثر باید 200 کاراکتر باشد',
            'university.bad_chars' => 'دانشگاه حاوی کاراکتر های غیر مجاز است',


            'rating.required' => 'ورود امتیاز الزامی است',
            'rating.int' => 'عدد بین 1 و 5',


        ];

        $validator = validator()->make($request->all(),$rules,$customMessages);

        if ($validator->fails())
        {
            session()->flash('error','خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $comment = AdminComment::find($request->id);
        $comment->name = $request->name;
        $comment->field = $request->field;
        $comment->rating = strval($request->rating);
        $comment->text = $request->text;
        $comment->university = $request->university;
        if ($request->file('photo')) {
            $time  = time();
            $folder = '/uploads/adminComment/';
            $file = $request->file('photo');
            $file->move(public_path() . $folder, $time . '.png');
           
            $comment->photo = route("adminCommentPhoto", ["id" =>  $time]);
         
        }
        if($comment->save()) {
            session()->flash('success','کامنت با موفقیت ثبت شد');
        } else
            session()->flash('error','خطا در ثبت کامنت ');
        return redirect()->back();
    }

    function deleteComment($id) {
        $comment = AdminComment::find($id);
        if($comment->delete()) {
            session()->flash('success','کامنت حذف شد');
        }
        else
            session()->flash('error','خطا در حذف کامنت ');
        return redirect()->back();
    }
}
