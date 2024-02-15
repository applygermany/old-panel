<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>نام</th>
            <th>تصویر</th>
            <th>متن</th>
            <th>دانشگاه</th>
            <th>رشته</th>
            <th>امتیاز</th>
            <th>اقدامات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($comment as $item)
            <tr class="text-center">
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td><img src="{{ $item->photo }}" height="40" width="40"> </td>
                <td>{{ $item->text }}</td>
                <td>{{ $item->university }}</td>
                <td>{{ $item->field }}</td>
                <td>{{ $item->rating }}</td>
                <td>
                    <a href="{{ route('admin.editComment',['id'=>$item->id]) }}" class="btn btn-warning btn-sm">ویرایش</a>
                    <a href="{{ route('admin.deleteComment',['id'=>$item->id]) }}" class="btn btn-danger btn-sm delete">حذف</a>

                </td>
       
            </tr>
        @endforeach
        </tbody>
    </table>
</div>