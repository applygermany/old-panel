<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>نام</th>
            <th>فامیل</th>
            <th>نام وبینار</th>
            <th>ایمیل</th>
            <th>موبایل</th>
            <th>هزینه پرداختی</th>
            <th>تاریخ ارسال</th>
            <th>نمایش رسید</th>
        </tr>
        </thead>
        <tbody>
        @foreach($userWebinars as $userWebinar)
            <tr class="text-center">
                <td>{{ $userWebinar->id }}</td>
                <td>{{ $userWebinar->name }}</td>
                <td>{{ $userWebinar->family }}</td>
                <td>{{ $userWebinar->webinar->title ?? "__" }}</td>
                <td>{{ $userWebinar->email }}</td>
                <td>{{ $userWebinar->mobile }}</td>
                <td>{{ number_format($userWebinar->price) }}</td>
                <td>
                    {{ \App\Providers\MyHelpers::dateToJalali($userWebinar->created_at) }}
                    <br>
                    {{ \App\Providers\MyHelpers::dateGetHour($userWebinar->created_at) }}
                </td>
                <td>
                    <button class="btn btn-primary btn-sm showWebinar" data-instagram="{{ $userWebinar->instagram }}"
                            data-telegram="{{ $userWebinar->telegram }}"
                            data-grade="{{ $userWebinar->grade }}" data-field="{{ $userWebinar->field }}"
                            data-webinar_receipt="{{ route('webinarReceipt',['id'=>$userWebinar->id]) }}">نمایش رسید</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $userWebinars->links() !!}