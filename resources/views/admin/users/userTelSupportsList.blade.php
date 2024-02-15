<div class="table-responsive">
    <table class="table align-middle table-row-dashed gy-5 dataTable no-footer">
        <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
        <tr class="text-center text-gray-400 gs-0" role="row">
            <th>#</th>
            <th>کارشناس</th>
            <th>تاریخ مشاوره</th>
            <th>خلاصه مشاوره</th>
            <th>تاریخ ثبت</th>
            <th>حذف</th>
        </tr>
        </thead>
        <tbody class="fs-6 fw-bold text-gray-600">
        @foreach($userTelSupports as $userTelSupport)
            <tr class="text-center">
                <td>{{ $userTelSupport->id }}</td>
                <td>{{ $userTelSupport->supervisor->firstname }} {{ $userTelSupport->supervisor->lastname }}</td>
                <td>
                    <?php
                    $date = explode(' ',$userTelSupport->tel_date);
                    $date = explode('-',$date[0]);
                    $date = \App\Providers\JDF::gregorian_to_jalali($date[0],$date[1],$date[2],'/');
                    echo $date;
                    ?>
                </td>
                <td>{{ $userTelSupport->advise }}</td>
                <td>
                    <?php
                    $date = explode(' ',$userTelSupport->created_at);
                    $date = explode('-',$date[0]);
                    $date = \App\Providers\JDF::gregorian_to_jalali($date[0],$date[1],$date[2],'/');
                    echo $date;
                    ?>
                </td>
                <td>
                    <a href="{{ route('admin.deleteUserTelSupport',['id'=>$userTelSupport->id]) }}" class="btn btn-sm btn-danger delete">حذف</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="row">
    {!! $userTelSupports->links() !!}
</div>