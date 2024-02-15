<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>نام</th>
            <th>ترم</th>
            <th>ویرایش</th>
            <th>حذف</th>
        </tr>
        </thead>
        <tbody>
        @foreach($accepteds as $accepted)
            <tr class="text-center">
                <td>{{ $accepted->id }}</td>
                <td>{{ $accepted->name }}</td>
                <td>{{ $accepted->universities[0]["semester"] }}</td>

                <td>
                    <button class="btn btn-warning btn-sm edit" data-url="{{ route('admin.editAccepted',['id'=>$accepted->id]) }}">ویرایش</button>
                </td>
                <td>
                    <a href="{{ route('admin.deleteAccepted',['id'=>$accepted->id]) }}" class="btn btn-danger btn-sm delete">حذف</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $accepteds->links() !!}