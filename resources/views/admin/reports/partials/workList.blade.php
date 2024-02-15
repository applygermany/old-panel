

<div class="table-responsive">
    <table class="table" id="car">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>کاربر</th>
            <th>تعداد مشاوره</th>
            <th>تاریخ ویژه</th>
            <th>ترم</th>
            <th>قرارداد</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($contracts as $telSupport)
            <tr class="text-center">
                <td>{{ $telSupport['id'] }}</td>
                <td>{{ $telSupport['name'] }}</td>
                <td>{{ $telSupport['count'] }}</td>
                <td>
                    @php
                        $date = explode(' ',$telSupport['date']);
                        $date = explode('-',$date[0]);
                        $date = \App\Providers\JDF::gregorian_to_jalali($date[0],$date[1],$date[2],'/');
                        echo $date;
                    @endphp
                </td>
                <td>{{ $telSupport['term']}}</td>
                <td>{{ $telSupport['contract']}}</td>
                <td>
                    <a href="{{route('admin.workExperienceListUser', ['id'=>$telSupport['id'],
                     'supervisorId'=>$telSupport['supervisorId']])}}" class="btn btn-info btn-sm">نمایش مشاوره ها</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div id="paginationContainer"></div>

<script>
    var table = $('#car').DataTable({
        "paging": true,
        "pageLength": 10,
        "lengthChange": false
    });
</script>
