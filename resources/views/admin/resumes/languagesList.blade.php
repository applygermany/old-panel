<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>نام زبان</th>
            <th>میزان تسلط</th>
            <th>مدرک</th>
            <th>نمره</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resume->languages()->get() as $language)
            <tr class="text-center">
                <td>{{ $language->title }}</td>
                <td>{{ $language->fluency_level }}</td>
                <td>{{ $language->degree }}</td>
                <td>{{ $language->score }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>