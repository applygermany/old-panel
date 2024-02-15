<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>نام</th>
            <th>نام خانوادگی</th>
            <th>موبایل</th>
            <th>ایمیل</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($admins as $admin)
            <tr class="text-center">
                <td>{{ $admin->id }}</td>
                <td>{{ $admin->firstname }}</td>
                <td>{{ $admin->lastname }}</td>
                <td>{{ $admin->mobile }}</td>
                <td>{{ $admin->email }}</td>
                <td>
                    <button class="btn btn-warning btn-sm show"
                            data-url="{{ route('admin.adminsTeamList', ['id' => $admin->id]) }}">
                        لیست
                    </button>
                    <button class="btn btn-success btn-sm add"
                            data-url="{{ route('admin.adminsTeamAdd', ['id' => $admin->id]) }}">
                        افزودن
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
