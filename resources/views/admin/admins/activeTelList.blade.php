<div class="table-responsive">
    <table class="table align-middle table-row-dashed gy-5 dataTable no-footer">
        <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
        <tr class="text-center text-gray-400 gs-0" role="row">
            <th>#</th>
            <th>تاریخ مشاوره</th>
            <th>کاربر</th>
            <th>موضوع</th>
            <th>تاریخ ثبت</th>
            <th>حذف</th>
        </tr>
        </thead>
        <tbody class="fs-6 fw-bold text-gray-600">
        @foreach($activeTelSessions as $activeTelSession)
            <tr class="text-center">
                <td>{{ $activeTelSession->id }}</td>
                <td>
                        <?php
                        $date2 = explode(' ', $activeTelSession->day_tel);
                        $date = explode('-', $date2[0]);
                        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                        echo $date . " " . $activeTelSession->from_time;
                        ?>
                </td>
                <td>
                    @if( $activeTelSession->userTell->user ?? false)
                        <a href="{{route("admin.userProfile", ["id" => $activeTelSession->userTell->user->id]) }}">{{ $activeTelSession->userTell->user->firstname ?? ""}} {{ $activeTelSession->userTell->user->lastname ?? ""}}</a>
                    @endif
                </td>
                <td>{{ $activeTelSession->userTell->title ?? ""}}</td>

                <td>
                        <?php
                        $date2 = explode(' ', $activeTelSession->created_at);
                        $date = explode('-', $date2[0]);
                        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                        echo $date . " " . $date2[1];
                        ?>
                </td>
                <td>
                    @if( $activeTelSession->userTell->user ?? false)
                        <a href="{{ route('admin.deleteTelSupportUser',['id'=>$activeTelSession->id]) }}"
                           class="btn btn-danger btn-sm">خذف مشاوره</a>
                    @else
                        <a href="{{ route('admin.deleteTelSupport',['id'=>$activeTelSession->id]) }}"
                           class="btn btn-danger btn-sm">حذف</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="row">
    {!! $activeTelSessions->links() !!}
</div>

