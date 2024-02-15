<div class="table-responsive">
    <table class="table  table-hover">
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>نام</th>
                <th>موبایل</th>
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

            <tr class="text-center">
                <td>{{ $acceptance->id }}</td>
                <td>{{ $acceptance->firstname }} {{ $acceptance->lastname }}</td>
                <td>{{ strval($acceptance->phone) }}</td>
                <td>{{ $acceptance->birth_date }}</td>
                <td>{{ $acceptance->embassy_appointment ?? '------' }}</td>
                <td>{{ $acceptance->embassy_date ?? '------' }}</td>
                <td>{{ $acceptance->admittance ?? '------' }}</td>
                <td>{{ $acceptance->diploma_grade_average ?? '------' }}</td>
                <td>{{ $acceptance->pre_university_grade_average ?? '------' }}</td>
                <td>{{ $acceptance->field_grade ?? '------' }}</td>
                <td>{{ $acceptance->is_license_semesters ?? '------' }}</td>
                <td>{{ $acceptance->field_license ?? '------' }}</td>
                <td>{{ $acceptance->university_license ?? '------' }}</td>
                <td>{{ $acceptance->license_graduated ?? '------' }}</td>
                <td>{{ $acceptance->average_license ?? '------' }}</td>
                <td>{{ $acceptance->year_license ?? '------' }}</td>
                <td>{{ $acceptance->total_number_passes ?? '------' }}</td>
                <td>{{ $acceptance->Pass_30_units ?? '------' }}</td>
                <td>{{ $acceptance->senior_educate ?? '------' }}</td>
                <td>{{ $acceptance->field_senior ?? '------' }}</td>
                <td>{{ $acceptance->university_senior ?? '------' }}</td>
                <td>{{ $acceptance->average_senior ?? '------' }}</td>
                <td>{{ $acceptance->year_senior ?? '------' }}</td>
                <td>{{ $acceptance->another_educate ?? '------' }}</td>
                <td>{{ $acceptance->military_service ?? '------' }}</td>
                <td>{{ $acceptance->language_favor ?? '------' }}</td>
                <td>{{ $acceptance->license_language ?? '------' }}</td>
                <td>{{ $acceptance->what_grade_language ?? '------' }}</td>
                <td>{{ $acceptance->what_intent_grade_language ?? '------' }}</td>
                <td>{{ $acceptance->date_intent_grade_language ?? '------' }}</td>
                <td>{{ $acceptance->doc_translate ?? '------' }}</td>
                <td>{{ $acceptance->doc_embassy ?? '------' }}</td>
                <td>{{ $acceptance->description ?? '------' }}</td>
                <td>
                    <?php
                    $date = explode(' ', $acceptance->created_at);
                    $date = explode('-', $date[0]);
                    $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                    echo $date;
                    ?>
                </td>

            </tr>

        </tbody>
    </table>
</div>
