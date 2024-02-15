<div class="table-responsive" id="commentsList">
    <table class="table align-middle table-row-dashed gy-5 dataTable no-footer">
        <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
        <tr class="text-center text-gray-400 gs-0" role="row">
            <th>#</th>
            <th>تاریخ مشاوره</th>
            <th>کاربر</th>
            <th>موضوع</th>
            <th>تاریخ ثبت</th>

        </tr>
        </thead>
        <tbody class="fs-6 fw-bold text-gray-600">
        @foreach($pastTelSessions as $pastTelSession)

            <tr class="text-center">
                <td>{{ $pastTelSession->id }}</td>
                <td>{{ $pastTelSession->tel_date }}</td>
                <td>
                    @if( $pastTelSession->user ?? false)
                        <a href="{{route("admin.userProfile", ["id" => $pastTelSession->user->id]) }}">{{ $pastTelSession->user->firstname ?? ""}} {{ $pastTelSession->user->lastname ?? ""}}</a>
                    @endif
                </td>
                <td>{{ $pastTelSession->title ?? ""}}</td>
                <td>
                        <?php
                        $date2 = explode(' ', $pastTelSession->created_at);
                        $date = explode('-', $date2[0]);
                        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                        echo $date . " " . $date2[1];
                        ?>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="row">
    {!! $pastTelSessions->links() !!}
</div>