<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>نام</th>
            <th>فامیل</th>
            <th>ایمیل/موبایل</th>
            <th>رشته</th>
            <th>تاریخ تولد</th>
            <th>توضیحات</th>
            <th>رزومه</th>
            <th>حذف</th>
        </tr>
        </thead>
        <tbody>
        @foreach($collaborations as $collaboration)
            <tr class="text-center">
                <td>{{ $collaboration->id }}</td>
                <td>{{ $collaboration->name }}</td>
                <td>{{ $collaboration->family }}</td>
                <td>{{ $collaboration->email }}</td>
                <td>{{ $collaboration->field }}</td>
                <td>{{ $collaboration->birth_date }}</td>
                <td>
                    <button class="btn btn-primary btn-sm showCollaboration"
                            data-text="{{ htmlspecialchars($collaboration->text) }}">نمایش</button>
                </td>
                <td>
                    <a href="{{ route('resumeCollaboration',['id'=>$collaboration->id]) }}"
                       class="col-12 btn btn-info btn-sm" id="resumeCollab">دانلود رزومه</a>
                </td>
                <td>
                    <a href="{{ route('admin.deleteCollaboration',['id'=>$collaboration->id]) }}" class="btn btn-danger btn-sm delete">حذف</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $collaborations->links() !!}