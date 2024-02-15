<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>مقطع</th>
            <th>رشته</th>
            <th>معدل</th>
            <th>شهر</th>
            <th>از تاریخ</th>
            <th>تا تاریخ</th>
            <th>مدرسه یا دانشگاه</th>
            <th>توضیحات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resume->educationRecords()->get() as $educationRecord)
            <tr class="text-center">
                <td>{{ $educationRecord->grade }}</td>
                <td>{{ $educationRecord->field }}</td>
                <td>{{ $educationRecord->grade_score }}</td>
                <td>{{ $educationRecord->city }}</td>
                <td>{{ $educationRecord->from_date_year }} - {{$educationRecord->from_date_month}}</td>
                <td>{{ $educationRecord->to_date_year }} - {{$educationRecord->to_date_month}}</td>
                <td>{{ $educationRecord->school_name }}</td>
                <td>{{ $educationRecord->text }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>