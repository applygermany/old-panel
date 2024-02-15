<div class="table-responsive">
    <table class="table align-middle table-row-dashed gy-5 dataTable no-footer">
        <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
        <tr class="text-center text-gray-400 gs-0" role="row">
            <th>#</th>
            <th>مقطع درخواستی</th>
            <th>تاریخ ثبت</th>
            <th>نمایش جزئیات</th>
        </tr>
        </thead>
        <tbody class="fs-6 fw-bold text-gray-600">
        @foreach($acceptances as $acceptance)
            <tr class="text-center">
                <td>{{ $acceptance->id }}</td>
                <td>{{ $acceptance->admittance }}</td>
                <td>
                        <?php
                        $date = explode(' ', $acceptance->created_at);
                        $date = explode('-', $date[0]);
                        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                        echo $date;
                        ?>
                </td>
                <td>
                    <a href="javascript:{}" onclick="showAcceptanceModal({{ $acceptance->id }})"
                       class="btn btn-info btn-sm">نمایش جزئیات</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="modal fade" tabindex="-1" id="showAcceptance">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">نمایش درخواست اپلای</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="fa fa-window-close fa-2x text-danger"></span>
                </div>
            </div>

            <div class="modal-body">


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                <a id="updateButton" href class="btn btn-info">ویرایش</a>
            </div>
        </div>
    </div>
</div>
@section('script')
    <script>
        function showAcceptanceModal(id) {
            $.get("{{route("admin.getUserAcceptance")}}", {id: id}, function (data) {
                $("#showAcceptance .modal-body").html(data)
                $("#updateButton").attr('href', "{{url('admin/setUpdateAcceptance')}}" + '/' + id)
                $("#showAcceptance").modal("show")
            })
        }
    </script>

@endsection
<div class="row">
    {!! $acceptances->links() !!}
</div>