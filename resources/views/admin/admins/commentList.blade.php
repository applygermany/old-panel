<div class="table-responsive" id="commentsList">
    <table class="table align-middle table-row-dashed gy-5 dataTable no-footer">
        <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
        <tr class="text-center text-gray-400 gs-0" role="row">
            <th>#</th>
            <th>کاربر</th>
            <th>کامنت</th>
            <th>امتیاز</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody class="fs-6 fw-bold text-gray-600">
        @foreach($comments as $comment)

            <tr class="text-center">
                <td>{{ $comment->id }}</td>
                <td>{{ $comment->author }}</td>
                <td>{{ $comment->text }}</td>
                <td>{{ $comment->score }}</td>
                <td>
                        <?php
                        $date2 = explode(' ', $comment->created_at);
                        $date = explode('-', $date2[0]);
                        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                        echo $date . " " . $date2[1];
                        ?>
                </td>
                <td><a href="{{route('admin.deleteTelComment', ['id'=>$comment->id])}}" class="btn btn-danger">حذف</a> </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="row">
    {!! $comments->links() !!}
</div>

@section('script')
    <script type="text/javascript">
        $(document).on('click', '.page-link', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getComments(page)
        });

        function getComments(page) {
            $('#commentsList').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getAdminComments')}}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: {{$comments[0]->owner_id}}
                },
                success: function (data) {
                    $('#commentsList').html(data);
                }
            });
        }
    </script>
@endsection