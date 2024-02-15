<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
            <tr class="text-center">
                <th>#</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>موبایل</th>
                <th>ایمیل</th>
                <th>سطح دسترسی</th>
                <th>لیست</th>
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
                            @if ($admin->level == 3)
                                <span class="text-primary">کارشناس ارشد </span>
                            @elseif($admin->level == 5)
                                <span class="text-info">کارشناس </span>
                            @elseif($admin->level == 7)
                                <span class="text-dark">مشاور </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.workExperienceList', ['id' => $admin->id]) }}"
                                class="btn btn-info btn-sm">نمایش</a>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
{!! $admins->links() !!}
