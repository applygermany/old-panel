<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>عنوان</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resume->hobbies()->get() as $hobby)
            <tr class="text-center">
                <td>{{ $hobby->title }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>