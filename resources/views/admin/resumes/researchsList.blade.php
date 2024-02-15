<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>عنوان</th>
            <th>نوع</th>
            <th>تاریخ (سال)</th>
            <th>توضیحات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resume->researchs()->get() as $research)
            <tr class="text-center">
                <td>{{ $research->title }}</td>
                <td>{{ $research->type }}</td>
                <td>{{ $research->year }}</td>
                <td>{{ $research->text }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>