<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>عنوان</th>
            <th>توضیحات</th>
            <th>تاریخ</th>
            <th>دانلود مدرک</th>
            <th>حذف</th>
        </tr>
        </thead>
        <tbody>
        @foreach($uploads as $upload)
            <tr class="text-center">
                <td>{{ $upload->id }}</td>
                <td>{{ $upload->title }}</td>
                <td>{{ $upload->text }}</td>
                <td>{{ $upload->date }}</td>
                <td>
                    <a href="{{ route('madrak',['id'=>$upload->id]) }}" class="btn btn-primary btn-sm" target="_blank">دانلود مدرک</a>
                </td>
                <td>
                    <a href="{{ route('admin.deleteUpload',['id'=>$upload->id]) }}" class="btn btn-danger btn-sm delete">حذف</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $uploads->links() !!}