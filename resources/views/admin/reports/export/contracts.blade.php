<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>کاربر</th>
            <th>تاریخ</th>
            <th>ترم</th>
            <th>نوع قرارداد</th>
            <th>دانلود</th>
        </tr>
        </thead>
        <tbody>
        @foreach($contracts as $contract)
            <tr class="text-center">
                <td>{{ $contract->id }}</td>
                <td>
                    <a href="{{ route('admin.userProfile',['id'=>$contract->user_id]) }}">{{ $contract->user->firstname }} {{ $contract->user->lastname }}</a>
                </td>
                <td>{{ $contract->date }}</td>
                <td>{{ $contract->user->category_id !== 0 ? $categories->where('id', $contract->user->category_id)->first()->title :'---' }}</td>
                <td>{{ $contract->user->type === 2 ? 'پایه' : 'ویژه'}}</td>
                <td>
                    @if($contract->file_url !== null)
                        <a target="_blank" href="{{ route('madrak',['id'=>$contract->file_url])}}">دانلود</a>
                    @else
                        ---
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
