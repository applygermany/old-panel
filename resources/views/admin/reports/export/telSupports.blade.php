<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>کاربر</th>
            <th>تاریخ/ ساعت</th>
            <th>موضوع</th>
            <th>ترم</th>
            <th>مشاور</th>
            <th>نوع کاربر</th>
            <th>مبلغ</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td class="text-gray-600">{{ $telSupport->id }}</td>
            <td class="text-gray-600">
                <a href="{{ route('admin.userProfile',['id'=>$telSupport->user_id]) }}">{{ $telSupport->user->firstname }} {{ $telSupport->user->lastname }}</a>
            </td>
            <td class="text-gray-600">{{ $telSupport->telSupport->day_tel_fa }} - {{ $telSupport->telSupport->from_time }} تا {{ $telSupport->telSupport->to_time }}</td>
            <td class="text-gray-600">{{ $telSupport->title }}</td>
            <td class="text-gray-600">{{$categories->where('id', $telSupport->user->category_id)->first() ?
                      $categories->where('id', $telSupport->user->category_id)->first()->title  : '----'}}</td>
            <td class="text-gray-600">
                <a href="{{ route('admin.adminProfile',['id'=>$telSupport->supervisor->id]) }}">{{ $telSupport->supervisor->firstname }} {{ $telSupport->supervisor->lastname }}</a>
            </td>
            <td class="text-gray-600">
                @if($telSupport->user->type === 1)
                    <span>عادی</span>
                @else
                    <span>ویژه</span>
                @endif
            </td>
            <td class="text-gray-600">
                @if($telSupport->price === 0)
                    <span>رایگان</span>
                @else
                    <span>{{number_format($telSupport->telSupport->price)}}</span>
                @endif
            </td>
        </tr>

        </tbody>
    </table>
