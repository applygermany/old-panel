<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>شماره قرارداد</th>
            <th>نام</th>
            <th>نام خانوادگی</th>
            <th>موبایل / ایمیل</th>
            <th>کد ملی</th>
            <th>مبلغ قرارداد</th>
            <th>نمایش</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr class="text-center">
                <td>{{ $user->id }}</td>
                <td>{{ $user->contract_code }}</td>
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
                <td>
                    {{$user->codemelli}}
                </td>
                <td>
                    @if ($user->type == 1)
                        <span class="text-dark">-----</span>
                    @elseif ($user->type == 2)
                        <span class="text-dark">1000</span>
                    @elseif ($user->type == 3)
                        <span class="text-primary">800</span>
                    @endif
                </td>
                <td><a href="{{ url('admin/users_information', ['id' => $user->id]) }}"
                       class="btn btn-info btn-sm">نمایش</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $users->links() !!}
