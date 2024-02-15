<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>عنوان</th>
            <th>مرحله</th>
            <th>فاز</th>
            <th>درصد فاز</th>
            <th>درصد پیشرفت</th>
            <th>متن دکمه</th>
            <th>تاریخ ثبت</th>
            <th>ویرایش</th>
            <th>حذف</th>
        </tr>
        </thead>
        <tbody>
        @foreach($applyLevels as $applyLevel)
            <tr class="text-center">
                <td>{{ $applyLevel->pos }}</td>
                <td>{{ $applyLevel->title }}</td>
                <td>{{ $applyLevel->pos }}</td>
                <td>{{ $applyLevel->phase }}</td>
                <td>{{ $applyLevel->phase_perent }}</td>
                <td>{{ $applyLevel->progress_perent }}</td>
                <td>{{ $applyLevel->next_level_button }}</td>
                <td>
                    <?php
                    $date = explode(' ',$applyLevel->created_at);
                    $date = explode('-',$date[0]);
                    $date = \App\Providers\JDF::gregorian_to_jalali($date[0],$date[1],$date[2],'/');
                    echo $date;
                    ?>
                </td>
                <td>
                    <a href="{{ route('admin.editApplyLevel',['id'=>$applyLevel->id]) }}" class="btn btn-warning btn-sm">ویرایش</a>
                </td>
                <td>
                    <a href="{{ route('admin.deleteApplyLevel',['id'=>$applyLevel->id]) }}" class="btn btn-danger btn-sm delete">حذف</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $applyLevels->links() !!}