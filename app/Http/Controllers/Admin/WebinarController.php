<?php

namespace App\Http\Controllers\Admin;

use App\Providers\JDF;
use App\Models\Webinar;
use App\Models\UserWebinar;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use App\Providers\MyHelpers;
use Illuminate\Routing\Controller;
use App\ExcelExports\UserWebinars;
use Maatwebsite\Excel\Facades\Excel;

class WebinarController extends Controller
{
    function webinars()
    {
        $webinars = Webinar::orderBy('id', 'DESC')->paginate(10);
//		$webinars = $webinars->map(function ($item) {
//			$item->link = MyHelpers::get_link($item->id);
//			return $item;
//		});
        return view('admin.webinars.webinars', compact('webinars'));
    }

    function getWebinars(Request $request)
    {
        $webinar = Webinar::Query();
        if ($request->searchTitle)
            $webinar->where('title', 'LIKE', '%' . $request->searchTitle . '%');
        if ($request->searchFirstMeeting)
            $webinar->where('first_meeting', 'LIKE', '%' . $request->searchFirstMeeting . '%');
        if ($request->searchSecondMeeting)
            $webinar->where('second_meeting', 'LIKE', '%' . $request->searchSecondMeeting . '%');
        $webinars = $webinar->orderBy('id', 'DESC')->paginate(10);
        return view('admin.webinars.list', compact('webinars'))->render();
    }

    function saveWebinar(Request $request)
    {
        $rules = [
            'title' => 'required',
            'time' => 'required',
            'headlines' => 'required',
            'paymentText' => 'required',
            //'paymentLink'=>'required',
            'organizerName' => 'required',
            'organizerField' => 'required',
            'firstMeeting' => 'required',
            'firstMeetingStartTime' => 'required',
            'firstMeetingEndTime' => 'required',
            'secondMeeting' => 'required',
            'secondMeetingStartTime' => 'required',
            'secondMeetingEndTime' => 'required',
            'slug' => 'required',
        ];
        $customMessages = [
            'title.required' => 'ورود عنوان الزامی است',
            'time.required' => 'ورود زمان برگزاری الزامی است',
            'headlines.required' => 'ورود سرفصل ها الزامی است',
            'paymentText.required' => 'ورود متن پرداخت الزامی است',
            'paymentLink.required' => 'ورود لینک پرداخت الزامی است',
            //'paymentLink.required' => 'ورود لینک پرداخت الزامی است',
            'organizerName.required' => 'ورود نام برگزار کننده الزامی است',
            'organizerField.required' => 'ورود تخصص برگزار کننده الزامی است',
            'firstMeeting.required' => 'ورود تاریخ برگزاری جلسه اول الزامی است',
            'firstMeetingStartTime.required' => 'ورود ساعت شروع جلسه اول الزامی است',
            'firstMeetingEndTime.required' => 'ورود ساعت خاتمه جلسه اول الزامی است',
            'secondMeeting.required' => 'ورود تاریخ برگزاری جلسه دوم الزامی است',
            'secondMeetingStartTime.required' => 'ورود ساعت شروع جلسه دوم الزامی است',
            'secondMeetingEndTime.required' => 'ورود ساعت خاتمه جلسه دوم الزامی است',
            'slug.required' => 'ورود slug الزامی است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $webinar = new Webinar();
        $webinar->title = $request->title;
        $webinar->time = $request->time;
        $webinar->headlines = $request->headlines;
        $webinar->payment_text = $request->paymentText;
        $webinar->payment_link = $request->paymentLink;
        $webinar->price = $request->paymentPrice;
        $webinar->organizer_name = $request->organizerName;
        $webinar->organizer_field = $request->organizerField;
        $webinar->first_meeting = $request->firstMeeting;
        $webinar->first_meeting_start_time = $request->firstMeetingStartTime;
        $webinar->first_meeting_end_time = $request->firstMeetingEndTime;
        $webinar->second_meeting = $request->secondMeeting;
        $webinar->second_meeting_start_time = $request->secondMeetingStartTime;
        $webinar->second_meeting_end_time = $request->secondMeetingEndTime;
        $webinar->slug = SlugService::createSlug(Webinar::class, 'slug', $request->slug);

        if ($webinar->save()) {
            session()->flash('success', 'وبینار با موفقیت ثبت شد');
            if ($request->file('image')) {
                $folder = '/uploads/webinar/';
                $file = $request->file('image');
                $file->move(public_path() . $folder, $webinar->id . '.jpg');
            }
            if ($request->file('organizerImage')) {
                $folder = '/uploads/webinar/';
                $file = $request->file('organizerImage');
                $file->move(public_path() . $folder, $webinar->id . '_organizer.jpg');
            }
            if ($request->file('webinarBanner')) {
                $folder = '/uploads/webinar/';
                $file = $request->file('webinarBanner');
                $file->move(public_path() . $folder, $webinar->id . '_banner.jpg');
            }
        } else
            session()->flash('error', 'خطا در ثبت وبینار');
        return redirect()->back();
    }

    function editWebinar($id)
    {
        $webinar = Webinar::find($id);
        return view('admin.webinars.edit', compact('webinar'));
    }

    function updateWebinar(Request $request)
    {
        $rules = [
            'title' => 'required',
            'time' => 'required',
            'headlines' => 'required',
            'paymentText' => 'required',
            // 'paymentLink'=>'required',
            //'paymentPrice'=>'required',
            'organizerName' => 'required',
            'organizerField' => 'required',
            'firstMeeting' => 'required',
            'firstMeetingStartTime' => 'required',
            'firstMeetingEndTime' => 'required',
            'secondMeeting' => 'required',
            'secondMeetingStartTime' => 'required',
            'secondMeetingEndTime' => 'required',
            'slug' => 'required',
        ];
        $customMessages = [
            'title.required' => 'ورود عنوان الزامی است',
            'time.required' => 'ورود زمان برگزاری الزامی است',
            'headlines.required' => 'ورود سرفصل ها الزامی است',
            'paymentText.required' => 'ورود متن پرداخت الزامی است',
            'paymentLink.required' => 'ورود لینک پرداخت الزامی است',
            //'paymentLink.required' => 'ورود لینک پرداخت الزامی است',
            'organizerName.required' => 'ورود نام برگزار کننده الزامی است',
            'organizerField.required' => 'ورود تخصص برگزار کننده الزامی است',
            'firstMeeting.required' => 'ورود تاریخ برگزاری جلسه اول الزامی است',
            'firstMeetingStartTime.required' => 'ورود ساعت شروع جلسه اول الزامی است',
            'firstMeetingEndTime.required' => 'ورود ساعت خاتمه جلسه اول الزامی است',
            'secondMeeting.required' => 'ورود تاریخ برگزاری جلسه دوم الزامی است',
            'secondMeetingStartTime.required' => 'ورود ساعت شروع جلسه دوم الزامی است',
            'secondMeetingEndTime.required' => 'ورود ساعت خاتمه جلسه دوم الزامی است',
            'slug.required' => 'ورود slug الزامی است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $webinar = Webinar::find($request->id);
        $webinar->title = $request->title;
        $webinar->time = $request->time;
        $webinar->headlines = $request->headlines;
        $webinar->payment_text = $request->paymentText;
        $webinar->payment_link = $request->paymentLink;
        $webinar->price = $request->paymentPrice;
        $webinar->organizer_name = $request->organizerName;
        $webinar->organizer_field = $request->organizerField;
        $webinar->first_meeting = $request->firstMeeting;
        $webinar->first_meeting_start_time = $request->firstMeetingStartTime;
        $webinar->first_meeting_end_time = $request->firstMeetingEndTime;
        $webinar->second_meeting = $request->secondMeeting;
        $webinar->second_meeting_start_time = $request->secondMeetingStartTime;
        $webinar->second_meeting_end_time = $request->secondMeetingEndTime;
        if ($webinar->slug !== $request->slug) {
            $webinar->slug = SlugService::createSlug(Webinar::class, 'slug', $request->slug);
        }
        if ($webinar->save()) {
            session()->flash('success', 'وبینار با موفقیت ثبت شد');
            if ($request->file('image')) {
                $folder = '/uploads/webinar/';
                $file = $request->file('image');
                $file->move(public_path() . $folder, $webinar->id . '.jpg');
            }
            if ($request->file('organizerImage')) {
                $folder = '/uploads/webinar/';
                $file = $request->file('organizerImage');
                $file->move(public_path() . $folder, $webinar->id . '_organizer.jpg');
            }
            if ($request->file('webinarBanner')) {
                $folder = '/uploads/webinar/';
                $file = $request->file('webinarBanner');
                $file->move(public_path() . $folder, $webinar->id . '_banner.jpg');
            }
        } else
            session()->flash('error', 'خطا در ثبت وبینار');
        return redirect()->back();
    }

    function deleteWebinar($id)
    {
        $webinar = Webinar::find($id);
        if ($webinar->delete()) {
            $files = glob(public_path('uploads/webinar/' . $id . '.*'));
            if (count($files) > 0) {
                if (is_file($files[0]))
                    unlink($files[0]);
            }
            session()->flash('success', 'وبینار حذف شد');
        } else
            session()->flash('error', 'خطا در حذف وبینار');
        return redirect()->back();
    }

    function webinarsParticipation(Request $request)
    {
        $webinar = Webinar::Query();
        if ($request->searchTitle)
            $webinar->where('title', 'LIKE', '%' . $request->searchTitle . '%');
        if ($request->searchFirstMeeting)
            $webinar->where('first_meeting', 'LIKE', '%' . $request->searchFirstMeeting . '%');
        if ($request->searchSecondMeeting)
            $webinar->where('second_meeting', 'LIKE', '%' . $request->searchSecondMeeting . '%');
        $webinars = $webinar->orderBy('id', 'DESC')->paginate(10);
        return view('admin.webinars.userWebinars', compact('webinars'))->render();
//	    return view('admin.webinars.participationList',compact('webinars'))->render();
//        $userWebinars = UserWebinar::with("webinar")->orderBy('id','DESC')->paginate(10);
//
//        return view('admin.webinars.participation',compact('userWebinars'));
    }

    function getSpecificWebinarUsers($id)
    {
        $webinars = Webinar::find($id);
        return view('admin.webinars.user', compact('webinars'));
    }

    function getWebinarsParticipation(Request $request)
    {

        $userWebinar = UserWebinar::Query();
        if ($request->searchName)
            $userWebinar->where('name', 'LIKE', '%' . $request->searchName . '%');
        if ($request->searchFamily)
            $userWebinar->where('family', 'LIKE', '%' . $request->searchFamily . '%');
        if ($request->searchEmail)
            $userWebinar->where('email', 'LIKE', '%' . $request->searchEmail . '%');
        if ($request->searchMobile)
            $userWebinar->where('mobile', 'LIKE', '%' . $request->searchMobile . '%');
        if ($request->searchField)
            $userWebinar->where('field', 'LIKE', '%' . $request->searchField . '%');
        if ($request->searchGrade)
            $userWebinar->where('grade', 'LIKE', '%' . $request->searchGrade . '%');
        if ($request->searchStartDate) {
            $date = explode('/', $request->searchStartDate);
            $date = JDF::jalali_to_gregorian($date[0], $date[1], $date[2], '-');
            $userWebinar->where('created_at', '>=', $date . ' 00:00:00');
        }
        if ($request->searchEndDate) {
            $date = explode('/', $request->searchEndDate);
            $date = JDF::jalali_to_gregorian($date[0], $date[1], $date[2], '-');
            $userWebinar->where('created_at', '<=', $date . ' 23:59:59');
        }
        $webinarId = $request->webinarId;
//		var_dump($webinarId);
//		die('');
        $userWebinar = $userWebinar->where("webinar_id", $webinarId);
        $userWebinars = $userWebinar->with("webinar")->orderBy('id', 'DESC')->paginate(10);
        return view('admin.webinars.participation', compact('userWebinars', 'webinarId'));
    }

    function getWebinarsParticipationPagination(Request $request)
    {

        $userWebinar = UserWebinar::Query();
        if ($request->searchName)
            $userWebinar->where('name', 'LIKE', '%' . $request->searchName . '%');
        if ($request->searchFamily)
            $userWebinar->where('family', 'LIKE', '%' . $request->searchFamily . '%');
        if ($request->searchEmail)
            $userWebinar->where('email', 'LIKE', '%' . $request->searchEmail . '%');
        if ($request->searchMobile)
            $userWebinar->where('mobile', 'LIKE', '%' . $request->searchMobile . '%');
        if ($request->searchField)
            $userWebinar->where('field', 'LIKE', '%' . $request->searchField . '%');
        if ($request->searchGrade)
            $userWebinar->where('grade', 'LIKE', '%' . $request->searchGrade . '%');
        if ($request->searchStartDate) {
            $date = explode('/', $request->searchStartDate);
            $date = JDF::jalali_to_gregorian($date[0], $date[1], $date[2], '-');
            $userWebinar->where('created_at', '>=', $date . ' 00:00:00');
        }
        if ($request->searchEndDate) {
            $date = explode('/', $request->searchEndDate);
            $date = JDF::jalali_to_gregorian($date[0], $date[1], $date[2], '-');
            $userWebinar->where('created_at', '<=', $date . ' 23:59:59');
        }
        $webinarId = $request->webinarId;
//		var_dump($webinarId);
//		die('');
        $userWebinar = $userWebinar->where("webinar_id", $webinarId);
        $userWebinars = $userWebinar->with("webinar")->orderBy('id', 'DESC')->paginate(10);
        return view('admin.webinars.participationList', compact('userWebinars', 'webinarId'))->render();
    }

    function exportParticipants(Request $request)
    {
        $user = UserWebinar::Query();
        $users = $user->where('webinar_id', $request->webinarId)->orderBy('id', 'DESC')->get();
        return Excel::download(new UserWebinars($request), 'webinarParticipants.xlsx');
    }

    function changeWebinarStatus($id)
    {
        $webinar = Webinar::find($id);
        if ($webinar->status === 'active')
            $webinar->status = 'deactive';
        else
            $webinar->status = 'active';

        if ($webinar->save()) {
            session()->flash('success', 'وبینار با موفقیت تغییر وضعیت داده شد');
        } else {
            session()->flash('error', 'تغییر وضعیت وبینار با شکست مواجه گردید');
        }
        return redirect()->back();
    }
}
