<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>شماره</th>
            <th>تخفیف</th>
            <th>کد</th>
            <th>تعداد مجاز استفاده</th>
            <th>تعداد استفاده شده</th>
            <th>نوع</th>
            <th>کاربر</th>
            <th>تاریخ استفاده</th>
            <th>نوع استفاده</th>
            <th>وضعیت</th>
            <th>خروجی</th>
            <th>حذف</th>
        </tr>
        </thead>
        <tbody>
        @foreach($offs as $off)
            <tr class="text-center">
                <td>{{ $off->id }}</td>
                <td>{{ number_format($off->discount) }}</td>
                <td>{{ $off->code }} <br/> {{$off->discount_code}}</td>
                <td>{{ $off->maximum_usage }}</td>
                <td>{{ $off->current_usage }}</td>
                <td>
                    @if($off->discount_type == 'percent')
                         درصدی
                    @elseif($off->discount_type == 'fixed')
                         مقداری 
                    @endif
                </td>
                <td>
                    @if($off->user_id)
                        <a href="{{ route('admin.userProfile',['id'=>$off->user_id]) }}">{{ $off->user->firstname }} {{ $off->user->lastname }}</a>
                    @else
                        برای همه
                    @endif
                </td>
                <td>{{ $off->end_date }}</td>
                <td>{{ $off->off_title }}</td>
                <td>
                    @if($off->status == 'active')
                        <button class="btn btn-success btn-sm activate" data-url="{{ route('admin.activateInviterOff',['id'=>$off->id]) }}">فعال</button>
                    @else
                        <button class="btn btn-danger btn-sm activate" data-url="{{ route('admin.activateInviterOff',['id'=>$off->id]) }}">غیر فعال</button>
                    @endif
                </td>
                <td><a class="btn btn-primary btn-sm" href="{{ route('admin.off.exportOffInviter',['id'=>$off->id]) }}">خروجی اکسل</a></td>
                <td><a class="btn btn-danger btn-sm delete" href="{{ route('admin.deleteOffInviter',['id'=>$off->id]) }}">حذف</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $offs->links() !!}