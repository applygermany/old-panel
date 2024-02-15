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
            <th>مبلغ</th>
            <th>کامنت ها</th>
        </tr>
        </thead>
        <tbody>
        @foreach($telSupports as $telSupport)
            <tr class="text-center">
                <td>{{ $telSupport->id }}</td>
                <td>
                    <a href="{{ route('admin.userProfile',['id'=>$telSupport->user_id]) }}">{{ $telSupport->user->firstname }} {{ $telSupport->user->lastname }}</a>
                    <br/>
                    @if($telSupport->user->type === 1)
                        <span>عادی</span>
                    @elseif($telSupport->user->type === 2)
                        <span>پایه</span>
                    @else
                        <span>ویژه</span>
                    @endif
                </td>
                <td>{{ $telSupport->telSupport->day_tel_fa }} - {{ $telSupport->telSupport->from_time }}
                    تا {{ $telSupport->telSupport->to_time }}</td>
                <td style="width: 20%">{{$telSupport->title}}</td>
                <td>{{$categories->where('id', $telSupport->user->category_id)->first() ?
                      $categories->where('id', $telSupport->user->category_id)->first()->title  : '----'}}</td>
                <td>
                    <a href="{{ route('admin.adminProfile',['id'=>$telSupport->supervisor->id]) }}">{{ $telSupport->supervisor->firstname }} {{ $telSupport->supervisor->lastname }}</a>
                </td>
                <td>
                    @if($telSupport->price === 0)
                        <span>رایگان</span>
                    @else
                        <span>{{number_format($telSupport->telSupport->price)}}</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-warning btn-sm comments"
                            data-url="{{ route('admin.telSupportComments', ['id' => $telSupport->user_id]) }}">نمایش</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $telSupports->links() !!}
