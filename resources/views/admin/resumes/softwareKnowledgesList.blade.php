<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>عنوان</th>
            <th>میزان تسلط</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resume->softwareKnowledges()->get() as $softwareKnowledge)
            <tr class="text-center">
                <td>{{ $softwareKnowledge->title }}</td>
                <td>{{ $softwareKnowledge->fluency_level }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>