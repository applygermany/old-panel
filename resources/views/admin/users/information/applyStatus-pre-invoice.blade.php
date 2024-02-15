<div class="table-responsive">
    <table class="table align-middle table-row-dashed gy-5 dataTable no-footer">
        <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
        <tr class="text-center text-gray-400 gs-0" role="row">
            <th>#</th>
            <th>نام دانشگاه</th>
            <th>رشته</th>
            <th>وضعیت اپلای</th>
        </tr>
        </thead>
        <tbody class="fs-6 fw-bold text-gray-600">
        @foreach($universities->where('status', 1) as $university)
            <tr class="text-center">
                <td>{{ $university->id }}</td>
                <td><a href="{{ $university->link }}">{{ $university->university->title ?? "__"}}</a></td>
                <td>{{ $university->field }}</td>
                <td>{{ $university->level_status_title }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>