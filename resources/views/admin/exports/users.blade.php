<table class="table table-hover">
    <thead>
    <tr class="text-center">
        <th>#</th>
        <th>نام</th>
        <th>موبایل</th>
        <th>ایمیل</th>
        <th>نحوه آشنایی</th>
        <th>تاریخ تولد</th>
        <th>درخواست وقت سفارت</th>
        <th>تاریخ وقت سفارت</th>
        <th>تقاضای اخذ پذیریش مدرک</th>
        <th>معدل دیپلم</th>
        <th>معدل پیش دانشگاهی</th>
        <th>رشته دیپلم</th>
        <th>در مقطع لیسانس تحصیل کرده اید</th>
        <th>رشته لیسانس</th>
        <th>نام دانشگاه</th>
        <th>لیسانس فارغ تحصیل شده اید</th>
        <th>معدل لیسانس</th>
        <th>سال فارغ تحصیلی</th>
        <th>تعداد واحد پاس شده</th>
        <th>30 واحد پاس شده</th>
        <th>ارشد</th>
        <th>رشته ارشد</th>
        <th>نام دانشگاه ارشد</th>
        <th>معدل مقطع ارشد</th>
        <th>سال فارغ تحصیلی ارشد</th>
        <th>تحصیلات دیگه</th>
        <th>وضعیت خدمت</th>
        <th>علاقمند به زبان</th>
        <th>مدرک زبان</th>
        <th>نوع مدرک زبان</th>
        <th>قصد گرفتن چه مدرک زبان</th>
        <th>قصد گرفتن مدرک زبان در تاریخ</th>
        <th>مدارک ترجمه رسمی ؟</th>
        <th>مدارک تایید سفارت</th>
        <th>توضیح اضافی</th>
        <th>تاریخ ثبت</th>


    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr class="text-center">
            <td>{{ $user->id }}</td>
            <td>{{ $user->firstname }} {{ $user->lastname }}</td>
            <td>{{ strval($user->mobile) }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->acquainted_way }}</td>
            <td>{{ $user->birth_date }}</td>
            <td>{{ $user->acceptances[0]->embassy_appointment ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->embassy_date ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->admittance ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->diploma_grade_average ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->pre_university_grade_average ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->field_grade ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->is_license_semesters ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->field_license ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->university_license ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->license_graduated ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->average_license ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->year_license ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->total_number_passes ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->Pass_30_units ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->senior_educate ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->field_senior ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->university_senior ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->average_senior ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->year_senior ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->another_educate ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->military_service ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->language_favor ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->license_language ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->what_grade_language ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->what_intent_grade_language ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->date_intent_grade_language ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->doc_translate ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->doc_embassy ?? "------" }}</td>
            <td>{{ $user->acceptances[0]->description ?? "------" }}</td>

            <td>
				<?php
				$date = explode(' ',$user->created_at);
				$date = explode('-',$date[0]);
				$date = \App\Providers\JDF::gregorian_to_jalali($date[0],$date[1],$date[2],'/');
				echo $date;
				?>
            </td>

        </tr>
    @endforeach
    </tbody>
</table>
