<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>نام کاربر</th>
            <th>نام کارشناس</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody id="table_body">
        @foreach($votes as $accepted)
            <tr class="text-center">
                <td>{{ $accepted->id }}</td>
                <td>{{ $accepted->userName }}</td>
                <td>{{ $accepted->expertName }}</td>
                <td>{{ $accepted->created_at }}</td>

                <td>
                    <a href="{{ route('admin.showVoteData',['id'=>$accepted->id]) }}" class="btn btn-info btn-sm view">مشاهده</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{--{!! $votes->links() !!}--}}
