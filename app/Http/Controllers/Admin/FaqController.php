<?php
namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FaqController extends Controller
{
    public $ordering = 1;

    function faq() {
        $faqs = Faq::orderBy('ordering','ASC')->where("type", 1)->get();
        return view('admin.faq.faq',compact('faqs'));
    }

    function saveFaq(Request $request) {
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

        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        if($faq->save())
            session()->flash('success','سوال با موفقیت ثبت شد');
        else
            session()->flash('error','خطا در ثبت سوال');
        return redirect()->back();
    }

    function editFaq($id) {
        $faq = Faq::find($id);
        return view('admin.faq.edit',compact('faq'));
    }

    function updateFaq(Request $request) {
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

        $faq = Faq::find($request->id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        if($faq->save())
            session()->flash('success','سوال با موفقیت ویرایش شد');
        else
            session()->flash('error','خطا در ویرایش سوال');
        return redirect()->back();
    }

    function deleteFaq($id) {
        $cfaq = Faq::where("id",$id)->where("type", 1)->first();
        if($cfaq->delete())
            session()->flash('success','سوال با موفقیت حذف شد');
        else
            session()->flash('error','خطا در حذف سوال');
        return redirect()->back();
    }

    function sortFaq(Request $request){
        $faqs = $request->faqs;
        foreach ($faqs as $faq) {
            Faq::find($faq['id'])->update(['ordering' => $this->ordering++]);
        }
        return 'success';
    }
}
