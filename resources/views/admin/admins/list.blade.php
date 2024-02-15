<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
            <tr class="text-center">
                <th>#</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>موبایل</th>
                <th>ایمیل</th>
                <th>تاریخ عضویت</th>
                <th>سطح دسترسی</th>
                <th>وضعیت</th>
                <th>مشاهده</th>
                <th>ویرایش</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admins as $admin)
                @if (($admin->level != 4 && auth()->user()->isSuperAdmin == 0) || auth()->user()->isSuperAdmin == 1)

                    <tr class="text-center">
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->firstname }}</td>
                        <td>{{ $admin->lastname }}</td>
                        <td>{{ $admin->mobile }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            <?php
                            $date = explode(' ', $admin->created_at);
                            $date = explode('-', $date[0]);
                            $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                            echo $date;
                            ?>
                        </td>
                        <td>
                            @if ($admin->level == 4)
                                <span class="text-dark">مدیر</span>
                            @elseif($admin->level == 3)
                                <span class="text-primary">کارشناس ارشد </span>
                            @elseif($admin->level == 5)
                                <span class="text-info">کارشناس </span>
                            @elseif($admin->level == 6)
                                <span class="text-info">نگارنده </span>
                            @elseif($admin->level == 7)
                                <span class="text-info">مشاور </span>
                            @else
                                <span class="text-primary">پشتیبان</span>
                            @endif
                        </td>
                        <td>
                            @if ($admin->status == 1)
                                <button class="btn btn-success btn-sm activate"
                                    data-url="{{ route('admin.activateAdmin', ['id' => $admin->id]) }}">فعال</button>
                            @else
                                <button class="btn btn-danger btn-sm activate"
                                    data-url="{{ route('admin.activateAdmin', ['id' => $admin->id]) }}">غیر فعال</button>
                            @endif
                        </td>
                        <td><a href="{{ route('admin.adminProfile', ['id' => $admin->id]) }}"
                                class="@if ($admin->level == 4 || $admin->level == 6) d-none @endif btn btn-info btn-sm">نمایش</a>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm edit"
                                data-url="{{ route('admin.editAdmin', ['id' => $admin->id]) }}">ویرایش</button>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
{!! $admins->links() !!}
