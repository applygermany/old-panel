<div class="row mb-5 ">
    <div class="col-12 col-lg-6">
        تعداد شرکت کنندگان:
        {{count($webinars->users)}}
    </div>
    <div class="col-12 col-lg-6">
        مجموع مبلغ  :
        {{number_format($webinars->users->sum('price'))}}
    </div>
    
</div>
<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>نام</th>
            <th>موبایل/ایمیل</th>
            <th>رشته</th>
            <th>مقطع</th>
            <th>اینستاگرام</th>
            <th>تلگرام</th>
            <th>مبلغ پرداخت شده</th>
            <th>تاریخ ثبت</th>

        </tr>
        </thead>
        <tbody>
        @foreach($webinars->users as $user)
            <tr class="text-center">
                <td>{{ $user->id }}</td>
                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                <td>{{ $user->mobile  ?  $user->mobile: $user->email}}</td>
                <td>{{ $user->field }}</td>
                <td>{{ $user->grade }}</td>
                <td>{{ $user->instagram ?? "__"}}</td>
                <td>{{ $user->telegram ?? "__" }}</td>
                <td>{{ number_format($user->price) }}</td>
                <td>
                 {{ \App\Providers\MyHelpers::dateToJalali($user->created_at) }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
