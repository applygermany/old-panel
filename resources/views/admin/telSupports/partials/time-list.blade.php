<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>تاریخ</th>
            <th>از ساعت</th>
            <th>تا ساعت</th>
            <th>انتخاب</th>
        </tr>
        </thead>
        <tbody>
        @foreach($times as $time)
            <tr class="text-center">
                <td>{{$time->day_tel_fa}}</td>
                <td>{{$time->from_time}}</td>
                <td>{{$time->to_time}}</td>
                <td>
                    <button class="btn btn-success btn-sm reserve" data-url="{{ route('admin.telSupportsExpertChooseTime',['id'=>$time->id]) }}">انتخاب رزرو</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
