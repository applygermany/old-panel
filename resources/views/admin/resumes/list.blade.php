<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>کاربر</th>
            <th>تاریخ</th>
            <th>ترم</th>
            <th>نگارنده</th>
            <th>وضعیت</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resumes as $resume)
            <tr class="text-center">
                <td>{{ $resume->id }}</td>
                <td>
                    <a href="{{ route('admin.userProfile',['id'=>$resume->user_id]) }}">{{ $resume->user->firstname }} {{ $resume->user->lastname }}</a>
                </td>
                <td>{{ $resume->created_at }}</td>
                <td>{{$categories->where('id', $resume->user->category_id)->first() ?
                      $categories->where('id', $resume->user->category_id)->first()->title  : '----'}}</td>
                <td>{{$resume->writer_id != 0 ? $resume->writer->firstname .' ' . $resume->writer->lastname : '----'}}</td>
                <td>
                    @switch($resume->status)
                        @case(0)
                            <span class="badge badge-info">
                               پرداخت نشده
                            </span>
                            @break
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
                    <a href="{{ route('admin.showResume',['id'=>$resume->id]) }}"
                       class="btn btn-primary btn-sm">نمایش</a>
                    <button class="btn btn-danger btn-sm delete"
                            data-url="{{ route('admin.deleteResume',['id'=>$resume->id]) }}">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $resumes->links() !!}
