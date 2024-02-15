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
        @foreach ($supports as $support)
            <tr class="text-center">
                <td>{{ $support->id }}</td>
                <td>{{ $support->firstname }}</td>
                <td>{{ $support->lastname }}</td>
                <td>{{ $support->mobile }}</td>
                <td>{{ $support->email }}</td>
                <td>
                    <a href="{{ route('admin.adminsTeamAddSupport', ['expertId'=>$id,'supportId' => $support->id]) }}"
                       class="btn btn-success btn-sm">افزودن</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
