<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>عنوان</th>
            <th>کد رنگ</th>
             <th>نمایش</th>

        </tr>
        </thead>
        <tbody>
        @foreach($resumeTemplateColors as $resumeTemplateColor)
            <tr class="text-center">
                <td>{{ $resumeTemplateColor->id }}</td>
                <td>{{ $resumeTemplateColor->title }}</td>
                <td>{{ $resumeTemplateColor->code }}</td>
                 <td align="center"><div style="width:20px;height:20px;background:#{{ $resumeTemplateColor->code }}"></div></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $resumeTemplateColors->links() !!}