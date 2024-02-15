@extends('admin.motivations.preview.master-layout-pdf')
@section('content')
    <div class="pdfbody">
        <table class="table">
            <tr>
                <td>
                    <img src="{{url('motivation/images/profile.png')}}" width="150"/>
                </td>
                <td>
                    <table class="table">
                        <tr>
                            <td>شماره تماس</td>
                            <td>{{$motivation->phone}}</td>
                        </tr>
                        <tr>
                            <td>ایمیل</td>
                            <td>{{$motivation->email}}</td>
                        </tr>
                        <tr>
                            <td>تاریخ تولد</td>
                            <td>{{$motivation->birth_date}}</td>
                        </tr>
                        <tr>
                            <td>محل تولد</td>
                            <td>{{$motivation->birth_place}}</td>
                        </tr>
                        <tr>
                            <td>محل تولدآدرس</td>
                            <td>{{$motivation->address}}</td>
                        </tr>
                        <tr>
                            <td>ارائه به</td>
                            <td>{{$motivation->to === 1 ? 'سفارت' : 'دانشگاه'}}</td>
                        </tr>
                        @if($motivation->to === 1)
                            <tr>
                                <td>ارائه به</td>
                                <td>{{$motivation->country === 1 ? 'ایران' : 'سایر گشور ها'}}</td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>

        <div>
            <h3 style="background: #eee; padding: 10px">بیوگرافی</h3>
            {!! nl2br($motivation->about) !!}
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px"> درباره سوابق تحصیلی و کاری</h3>
            {!! nl2br($motivation->resume) !!}
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px"> به چه علت آلمان را برای تحصیل انتخاب کرده اید؟</h3>
            {!! nl2br($motivation->why_germany) !!}
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px"> برنامه پس از فارغ التحصیلی</h3>
            {!! nl2br($motivation->after_graduation) !!}
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px"> توضیحات اضافی</h3>
            {!! nl2br($motivation->extra_text) !!}
        </div>
        <div>
            <h3 style="background: #eee; padding: 10px">دانشگاه ها</h3>
            @foreach($motivation->universities()->get() as $university)
                <table class="table">
                    <thead class="table-light">
                    <tr class="text-center">
                        <th>نام دانشگاه</th>
                        <th>مقطع</th>
                        <th>رشته</th>
                        <th>زبان تحصیل</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr class="text-center">
                        <td>{{ $university->name }}</td>
                        <td>{{ $university->grade }}</td>
                        <td>{{ $university->field }}</td>
                        <td>{{ $university->language }}</td>
                    </tr>
                    <tr>
                        <td colspan="1">
                            علت انتخاب این دانشگاه
                        </td>
                        <td colspan="3">
                            {{$university->text1}}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px black solid;margin-bottom: 1em">
                        <td colspan="1">
                            علت انتخاب این رشته
                        </td>
                        <td colspan="3">
                            {{$university->text2}}
                        </td>
                    </tr>

                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
@endsection