<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>نام شرکت</th>
            <th>سمت</th>
            <th>شهر</th>
            <th>از تاریخ</th>
            <th>تا تاریخ</th>
            <th>توضیحات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resume->works()->get() as $work)
            <tr class="text-center">
                <td>{{ $work->company_name }}</td>
                <td>{{ $work->position }}</td>
                <td>{{ $work->city }}</td>
                <td>{{ $work->from_date_year }} - {{$work->from_date_month}}</td>
                <td>{{ $work->to_date_year }} - {{$work->to_date_month}}</td>
                <td>{{ $work->text }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>