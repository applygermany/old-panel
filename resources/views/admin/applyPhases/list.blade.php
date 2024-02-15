<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>فاز</th>
            <th>عنوان</th>
            <th>توضیحات</th>
            <th>ویرایش</th>
        </tr>
        </thead>
        <tbody>
        @foreach($applyPhases as $applyPhase)
            <tr class="text-center">
                <td>{{ $applyPhase->id }}</td>
                <td>{{ $applyPhase->id }}</td>
                <td>{{ $applyPhase->title }}</td>
                <td>{{ $applyPhase->description }}</td>
               
                <td>
                    <a href="{{ route('admin.editApplyPhase',['id'=>$applyPhase->id]) }}" class="btn btn-warning btn-sm">ویرایش</a>
                </td>
              
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
