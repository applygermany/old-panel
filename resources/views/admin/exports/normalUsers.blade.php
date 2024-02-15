<table class="table table-hover">
    <thead>
    <tr class="text-center">
        <th>شناسه</th>
        <th>تاریخ عضویت</th>
        <th>نام</th>
        <th>موبایل</th>
        <th>مقطع قبلی</th>
        <th>معدل قبلی</th>
        <th>مقطع درخواستی</th>
        <th>زبان مورد علاقه</th>
        {{--        <th>ایمیل</th>--}}
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        @php($acceptances=$user->acceptances->last())
        <tr class="text-center">

            <td>{{ $user->id }}</td>
            <td>
                    <?php
                    $date = explode(' ', $user->created_at);
                    $date = explode('-', $date[0]);
                    $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                    echo $date;
                    ?>
            </td>
            <td>{{ $user->firstname }} {{ $user->lastname }}</td>
            <td>{{ strval($user->mobile) }}</td>
            <td>
                @if($acceptances->field_license and $acceptances->field_license !='')
                    {{$acceptances->field_license}}
                @else
                    {{$acceptances->field_grade}}
                @endif
            </td>
            <td>
                @if($acceptances->average_license)
                    {{$acceptances->average_license}}
                @elseif($acceptances->pre_university_grade_average)
                    {{$acceptances->pre_university_grade_average}}
                @else
                    {{$acceptances->diploma_grade_average}}
                @endif
            </td>
            <td>
                {{$acceptances->admittance}}
            </td>
            <td>
                {{$acceptances->language_favor}}
            </td>
            {{--            <td>{{ $user->email }}</td>--}}

        </tr>
    @endforeach
    </tbody>
</table>
