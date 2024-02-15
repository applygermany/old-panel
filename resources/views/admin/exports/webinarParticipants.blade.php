<table class="table table-hover">
    <thead>
    <tr class="text-center">
        <th>#</th>
        <th>نام</th>
        <th>نام خانوادگی</th>
        <th>موبایل</th>
        <th>ایمیل</th>
        <th>رشته تحصیلی</th>
        <th>مقطع</th>
        <th>اینستاگرام</th>
        <th>تلگرام</th>
        <th>تاریخ ثبت</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr class="text-center">
            <td>{{ $user->id }}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->family}}</td>
            <td>{{$user->mobile}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->field}}</td>
            <td>{{$user->grade}}</td>
            <td>{{$user->instagram}}</td>
            <td>{{$user->telegram}}</td>
            <td>
				<?php
				$date = explode(' ', $user->created_at);
				$date = explode('-', $date[0]);
				$date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
				echo $date;
				?>
            </td>

        </tr>
    @endforeach
    </tbody>
</table>
