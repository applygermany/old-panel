<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>کاربر</th>
            <th>عنوان</th>
            <th>تاریخ</th>
            <th>ترم</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($telSupports as $telSupport)
            <tr class="text-center">
                <td>{{ $telSupport->id }}</td>
                <td>{{ $telSupport->user->firstname}} {{ $telSupport->user->lastname}}</td>
                <td>{{ $telSupport->title }}</td>
                <td>{{ $telSupport->telSupport->day_tel_fa}} -
                    {{ $telSupport->telSupport->from_time}} تا {{ $telSupport->telSupport->to_time}}</td>
                <td>
                    {{$telSupport->user->category_id ? $categories->where('id', $telSupport->user->category_id)->first()->title : '---'}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
