<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>عنوان</th>
             <th>قیمت</th>
            <th>تاریخ برگزاری</th>
            <th>برگزار کننده</th>
            <th>تخصص برگزار کننده</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($webinars as $webinar)
            <tr class="text-center">
                <td>{{ $webinar->id }}</td>
                <td>{{ $webinar->title }}</td>
                <td>{{ $webinar->price ?? 0 }}</td>
                <td>{{ $webinar->time }}</td>
                <td>{{ $webinar->organizer_name }}</td>
                <td>{{ $webinar->organizer_field }}</td>
                <td>
                    <a href="{{route('admin.getWebinarsParticipation',['webinarId'=>$webinar->id])}}"  class="btn btn-primary btn-sm">شرکت کنندگان</a>
{{--                    <a href="{{ route('admin.editWebinar',['id'=>$webinar->id]) }}" class="btn btn-warning btn-sm">ویرایش</a>--}}
{{--                    <a href="{{ route('admin.deleteWebinar',['id'=>$webinar->id]) }}" class="btn btn-danger btn-sm delete">حذف</a>--}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $webinars->links() !!}
