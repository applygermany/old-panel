<?php
namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CFaqController extends Controller
{
    function cfaq() {
        $cfaqs = Faq::orderBy('id','DESC')->where('type',2)->get();
        return view('admin.cfaq.cfaq',compact('cfaqs'));
    }

    function saveCFaq(Request $request) {
        $rules = [
            'question'=>'required',
            'answer'=>'required'
        ];

        $customMessages = [
            'question.required' => 'سوال را وارد کنید',
            'answer.required' => 'جواب را وارد کنید'
        ];

        $validator = validator()->make($request->all(),$rules,$customMessages);

        if ($validator->fails())
        {
            session()->flash('error','خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cfaq = new Faq();
        $cfaq->question = $request->question;
        $cfaq->answer = $request->answer;
        $cfaq->type = 2;
        if($cfaq->save())
            session()->flash('success','سوال با موفقیت ثبت شد');
        else
            session()->flash('error','خطا در ثبت سوال');
        return redirect()->back();
    }

    function editCFaq($id) {
        $cfaq = Faq::find($id);
        return view('admin.cfaq.edit',compact('cfaq'));
    }

    function updateCFaq(Request $request) {
        $rules = [
            'question'=>'required',
            'answer'=>'required'
        ];

        $customMessages = [
            'question.required' => 'سوال را وارد کنید',
            'answer.required' => 'جواب را وارد کنید'
        ];

        $validator = validator()->make($request->all(),$rules,$customMessages);

        if ($validator->fails())
        {
            session()->flash('error','خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cfaq = Faq::find($request->id);
        $cfaq->question = $request->question;
        $cfaq->answer = $request->answer;
        if($cfaq->save())
            session()->flash('success','سوال با موفقیت ویرایش شد');
        else
            session()->flash('error','خطا در ویرایش سوال');
        return redirect()->back();
    }

    function deleteCFaq($id) {
        $cfaq = Faq::where("id",$id)->where("type", 2)->first();
        if($cfaq->delete())
            session()->flash('success','سوال با موفقیت حذف شد');
        else
            session()->flash('error','خطا در حذف سوال');
        return redirect()->back();
    }
}
