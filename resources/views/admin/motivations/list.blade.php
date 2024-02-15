<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>کاربر</th>
            <th>نام و نام خانوادگی</th>
            <th>تاریخ</th>
            <th>ترم</th>
            <th>نگارنده</th>
            <th>وضعیت</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($motivations as $motivation)
            <tr class="text-center">
                <td>{{ $motivation->id }}</td>
                <td>
                    @if($motivation->user->id > 0)
                        <a href="{{ route('admin.userProfile',['id'=>$motivation->user->id]) }}">{{ $motivation->user->firstname }} {{ $motivation->user->lastname }}</a>
                    @else
                        {{ $motivation->user->firstname }} {{ $motivation->user->lastname }}
                    @endif
                </td>
                <td>{{ $motivation->name }} {{ $motivation->family }}</td>
                <td>{{ $motivation->created_at }}</td>
                <td>{{$categories->where('id', $motivation->user->category_id)->first() ?
                      $categories->where('id', $motivation->user->category_id)->first()->title  : '----'}}</td>
                <td>{{$motivation->writer_id != 0 ? $motivation->writer->firstname .' ' . $motivation->writer->lastname : '----'}}</td>
                <td>
                    @switch($motivation->status)
                        @case(1)
                            <span class="badge badge-warning">
                                                در انتظار بررسی
                    </span>
                            @break
                        @case(2)
                            <span class="badge badge-success">
                        آماده شده
                    </span>
                            @break
                        @case(3)
                            <span class="badge badge-primary">
                        ادیت از سمت ادمین
                    </span>
                            @break
                        @case(4)
                            <span class="badge badge-danger">
                        ادیت از سمت کاربر
                    </span>
                            @break
                        @case(5)
                            <span class="badge badge-success">
                            تایید پشتیبان/کارشناس
                       </span>
                            @break
                        @case(6)
                            <span class="badge badge-danger">
                            رد پشتیبان/کارشناس
                       </span>
                            @break
                        @case(7)
                            <span class="badge badge-info">
                            اپلود نگارنده
                       </span>
                            @break
                        @default

                    @endswitch
                </td>
                <td>
                    <a href="{{ route('admin.showMotivation',['id'=>$motivation->id]) }}"
                       class="btn btn-primary btn-sm">نمایش</a>
                    <!--<a href="javascript:{}" data-url="{{ route('admin.editMotivation',['id'=>$motivation->id]) }}" class="edit btn btn-info btn-sm">ویرایش</a>-->

                    <button class="btn btn-danger btn-sm delete"
                            data-url="{{ route('admin.deleteMotivation',['id'=>$motivation->id]) }}">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $motivations->links() !!}
