@extends('admin.resumes.preview.master-layout-pdf')
@section('content')
    <div class="pdfbody">
        <table class="table">
            <tr>
                <td>
                    <img src="{{ route('imageResume',['id'=>$resume->id,'ua'=>strtotime($resume->user->updated_at)]) }}"
                         class="thumbnail-img" width="250"/>
                </td>
                <td>
                    <table class="table">
                        <tr>
                            <td colspan="2">
                                <h3>{{$resume->name}} {{$resume->family}}</h3>
                            </td>
                        </tr>
                        <tr>
                            <td>شماره سفارش</td>
                            <td>{{$resume->id}}</td>
                        </tr>
                        <tr>
                            <td>نام قالب</td>
                            <td>{{$resume->theme}}</td>
                        </tr>
                        <tr>
                            <td>کد رنگ</td>
                            <td>{{$resume->color}}</td>
                        </tr>
                        <tr>
                            <td>شماره تماس</td>
                            <td>{{$resume->phone}}</td>
                        </tr>
                        <tr>
                            <td>ایمیل</td>
                            <td>{{$resume->email}}</td>
                        </tr>
                        <tr>
                            <td>زبان</td>
                            <td>{{$resume->language}}</td>
                        </tr>
                        <tr>
                            <td>تاریخ تولد</td>
                            <td>{{$resume->birth_date}}</td>
                        </tr>
                        <tr>
                            <td>محل تولد</td>
                            <td>{{$resume->birth_place}}</td>
                        </tr>
                        <tr>
                            <td>آدرس</td>
                            <td>{{$resume->address}}</td>
                        </tr>
                        <tr>
                            <td>شبکه های اجتماعی</td>
                            <td>{{$resume->socialmedia_links}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div>
            <h3 style="background: #eee; padding: 10px">توضیحات بیشتر</h3>
            {!! nl2br($resume->text) !!}
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px"> سوابق تحصیلی</h3>
            <table class="table">
                <thead class="table-light">
                <tr class="text-center">
                    <th>مقطع</th>
                    <th>رشته</th>
                    <th>معدل</th>
                    <th>شهر</th>
                    <th>از تاریخ</th>
                    <th>تا تاریخ</th>
                    <th>مدرسه یا دانشگاه</th>
                    <th>توضیحات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resume->educationRecords()->get() as $educationRecord)
                    <tr class="text-center">
                        <td>{{ $educationRecord->grade }}</td>
                        <td>{{ $educationRecord->field }}</td>
                        <td>{{ $educationRecord->grade_score }}</td>
                        <td>{{ $educationRecord->city }}</td>
                        <td>{{ $educationRecord->from_date_year }}
                            - {{$educationRecord->from_date_month}}</td>
                        <td>{{ $educationRecord->to_date_year }}
                            - {{$educationRecord->to_date_month}}</td>
                        <td>{{ $educationRecord->school_name }}</td>
                        <td>{{ $educationRecord->text }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px">زبان ها</h3>
            <table class="table">
                <thead class="table-light">
                <tr class="text-center">
                    <th>نام زبان</th>
                    <th>میزان تسلط</th>
                    <th>مدرک</th>
                    <th>نمره</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resume->languages()->get() as $language)
                    <tr class="text-center">
                        <td>{{ $language->title }}</td>
                        <td>{{ $language->fluency_level }}</td>
                        <td>{{ $language->degree }}</td>
                        <td>{{ $language->score }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px">سوابق کاری</h3>
            <table class="table">
                <thead class="table-light">
                <tr class="text-center">
                    <th>نام شرکت</th>
                    <th>سمت</th>
                    <th>شهر</th>
                    <th>از تاریخ</th>
                    <th>تا تاریخ</th>
                    <th>توضیحات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resume->works()->get() as $work)
                    <tr class="text-center">
                        <td>{{ $work->company_name }}</td>
                        <td>{{ $work->position }}</td>
                        <td>{{ $work->city }}</td>
                        <td>{{ $work->from_date_year }} - {{$work->from_date_month}}</td>
                        <td>{{ $work->to_date_year }} - {{$work->to_date_month}}</td>
                        <td>{{ $work->text }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px">دانش نرم افزاری</h3>
            <table class="table">
                <thead class="table-light">
                <tr class="text-center">
                    <th>عنوان</th>
                    <th>میزان تسلط</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resume->softwareKnowledges()->get() as $softwareKnowledge)
                    <tr class="text-center">
                        <td>{{ $softwareKnowledge->title }}</td>
                        <td>{{ $softwareKnowledge->fluency_level }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px">دوره ها و مدارک</h3>
            <table class="table">
                <thead class="table-light">
                <tr class="text-center">
                    <th>عنوان</th>
                    <th>برگزار کننده</th>
                    <th>تاریخ (سال)</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resume->courses()->get() as $course)
                    <tr class="text-center">
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->organizer }}</td>
                        <td>{{ $course->year }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px">سوابق کاری و افتخارات</h3>
            <table class="table">
                <thead class="table-light">
                <tr class="text-center">
                    <th>عنوان</th>
                    <th>نوع</th>
                    <th>تاریخ (سال)</th>
                    <th>توضیحات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resume->researchs()->get() as $research)
                    <tr class="text-center">
                        <td>{{ $research->title }}</td>
                        <td>{{ $research->type }}</td>
                        <td>{{ $research->year }}</td>
                        <td>{{ $research->text }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px">تفریحات</h3>
            <table class="table">
                <thead class="table-light">
                <tr class="text-center">
                    <th>عنوان</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resume->hobbies()->get() as $hobby)
                    <tr class="text-center">
                        <td>{{ $hobby->title }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection