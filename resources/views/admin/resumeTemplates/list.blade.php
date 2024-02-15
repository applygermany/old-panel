<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>کد</th>
            <th>نام</th>
            <th>رنگ های موجود</th>
            <th>ویرایش</th>
            <th>حذف</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resumeTemplates as $resumeTemplate)
            <tr class="text-center">
                <td>{{ $resumeTemplate->id }}</td>
                <td>{{ $resumeTemplate->id }}</td>
                <td>{{ $resumeTemplate->name }}</td>
                <td align="center" >
                    <div class="row justify-content-center">
                    @foreach($resumeTemplate->colors as $color)
                    <div style="width:15px;height:15px;background:{{ $color }}"></div>
                    
                    @endforeach
                    </div>
               </td>

                <td>
                    <button class="btn btn-warning btn-sm edit" data-url="{{ route('admin.editResumeTemplate',['id'=>$resumeTemplate->id]) }}">ویرایش</button>
                </td>
                <td>
                    <a href="{{ route('admin.deleteResumeTemplate',['id'=>$resumeTemplate->id]) }}" class="btn btn-danger btn-sm delete">حذف</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $resumeTemplates->links() !!}