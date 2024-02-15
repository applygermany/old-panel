<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>عنوان</th>
            <th>برگزار کننده</th>
            <th>تاریخ (سال)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resume->courses()->get() as $course)
            <tr class="text-center">
                <td>{{ $course->title }}</td>
                <td>{{ $course->organizer }}</td>
                <td>{{ $course->year }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>