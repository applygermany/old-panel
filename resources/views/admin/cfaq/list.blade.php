<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>سوال</th>
            <th>ویرایش</th>
            <th>حذف</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cfaqs as $faq)
            <tr class="text-center">
                <td>{{ $faq->id }}</td>
                <td>{{ $faq->question }}</td>
                <td>
                    <a href="{{ route('admin.editCFaq',['id'=>$faq->id]) }}" class="btn btn-warning btn-sm">ویرایش</a>
                </td>
                <td>
                    <a href="{{ route('admin.deleteCFaq',['id'=>$faq->id]) }}" class="btn btn-danger btn-sm delete">حذف</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>