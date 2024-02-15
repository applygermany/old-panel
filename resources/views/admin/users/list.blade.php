<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>نام</th>
            <th>نام خانوادگی</th>
            <th>موبایل / ایمیل</th>
            <th>ترم</th>
            <th>کارشناس</th>
            <th>پشتیبانی</th>
            <th>تاریخ عضویت</th>
            <th>نوع</th>
            <th>وضعیت</th>
            <th>ویرایش</th>
            <th>نمایش</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr class="text-center">
                <td>{{ $user->id }}</td>
                <td>{{ $user->firstname }}</td>
                <td>{{ $user->lastname }}</td>
                <td>
                    @if ($user->mobile != '')
                        {{ $user->mobile }}
                    @else
                        ------
                    @endif
                    <br/>
                    @if ($user->email != '')
                        {{ $user->email }}
                    @else
                        ------
                    @endif
                </td>
                <td>{{$categories->where('id', $user->category_id)->first() ?
                      $categories->where('id', $user->category_id)->first()->title  : '----'}}</td>
                <td>
                    @if ($user->supervisor_item)
                        {{ $user->supervisor_item->firstname }} {{ $user->supervisor_item->lastname }}
                        <br/>
                            <?php
                            if ($user->expert_assign_date) {
                                $date = explode(' ', $user->expert_assign_date);
                                $date = explode('-', $date[0]);
                                $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                                echo $date;
                            }
                            ?>
                    @else
                        ------
                    @endif
                </td>
                <td>
                    @if ($user->support_item)
                        {{ $user->support_item->firstname }} {{ $user->support_item->lastname }}
                        <br/>
                            <?php
                            if ($user->support_assign_date) {
                                $date = explode(' ', $user->support_assign_date);
                                $date = explode('-', $date[0]);
                                $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                                echo $date;
                            }
                            ?>
                    @else
                        ------
                    @endif
                </td>
                <td>
                        <?php
                        $date = explode(' ', $user->created_at);
                        $date = explode('-', $date[0]);
                        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                        echo $date;
                        ?>
                </td>
                <td>
                    @if ($user->type == 1)
                        <span class="text-dark">معمولی</span>
                    @else
                        <span class="text-primary">ویژه</span>
                    @endif
                </td>
                <td>
                    @if ($user->status == 1)
                        <button class="btn btn-success btn-sm activate"
                                data-url="{{ route('admin.activateUser', ['id' => $user->id]) }}">فعال
                        </button>
                    @else
                        <button class="btn btn-danger btn-sm activate"
                                data-url="{{ route('admin.activateUser', ['id' => $user->id]) }}">غیر فعال
                        </button>
                    @endif
                </td>
                <td>
                    <button class="btn btn-warning btn-sm edit"
                            data-url="{{ route('admin.editUser', ['id' => $user->id]) }}">ویرایش
                    </button>
                </td>
                <td><a href="{{ route('admin.userProfile', ['id' => $user->id]) }}"
                       class="btn btn-info btn-sm">نمایش</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $users->links() !!}
