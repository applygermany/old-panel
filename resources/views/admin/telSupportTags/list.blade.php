<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>عنوان</th>
            <th>مقدار</th>
            <th>نوع</th>
            <th>حذف</th>
        </tr>
        </thead>
        <tbody>
        @foreach($telSupportTags as $telSupportTag)
            <tr class="text-center">
                <td>{{ $telSupportTag->id }}</td>
                <td>{{ $telSupportTag->title }}</td>
                <td>{{ $telSupportTag->value }}</td>
                <td>
                    @if($telSupportTag->type == 1)
                        تاریخ
                    @else
                        مبلغ
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.deleteTelSupportTag',['id'=>$telSupportTag->id]) }}" class="btn btn-danger btn-sm delete">حذف</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>