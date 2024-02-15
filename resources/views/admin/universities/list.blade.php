<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>عنوان</th>
            <th>شهر</th>
            <th>استان</th>
            <th>مکان جغرافیایی</th>
            <th>جمعیت شهر</th>
            <th>هزینه زندگی</th>
            <th>ویرایش</th>
{{--            <th>حذف</th>--}}
        </tr>
        </thead>
        <tbody>
        @foreach($universities as $university)
            <tr class="text-center">
                <td>{{ $university->id }}</td>
                <td>{{ $university->title }}</td>
                <td>{{ $university->city }}</td>
                <td>{{ $university->state }}</td>
                <td>{{ $university->geographical_location }}</td>
                <td>{{ $university->city_crowd }}</td>
                <td>{{ $university->cost_living }}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit" data-url="{{ route('admin.editUniversity',['id'=>$university->id]) }}">ویرایش</button>
                </td>
{{--                <td>--}}
{{--                    <a href="{{ route('admin.deleteUniversity',['id'=>$university->id]) }}" class="btn btn-danger btn-sm delete">حذف</a>--}}
{{--                </td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $universities->links() !!}