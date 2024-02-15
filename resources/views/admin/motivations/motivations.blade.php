@extends('admin.lyout')

@section('title')
    پنل مدیریت - انگیزه نامه ها
@endsection

@section('css')
    <link rel="stylesheet" href="{{ url('assets/plugins/persian-date/persian-datepicker.min.css') }}">
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                     data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                     class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">انگیزه نامه ها</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">انگیزه نامه ها</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="cityt d-flex flex-column-fluid">
            <div class="container">
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">جستجو</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label for="searchUser" class="header-label">کاربر</label>
                                            <select name="searchUser" id="searchUser"
                                                    class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label for="searchWriter" class="header-label">نگارنده</label>
                                            <select name="searchWriter" id="searchWriter"
                                                    class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($users as $user)
                                                    @if($user->level === 6)
                                                        <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}
                                                            - {{ $user->mobile }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label for="searchTerm" class="header-label">ترم</label>
                                            <select name="searchTerm" id="searchTerm"
                                                    class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="searchId" class="header-label">شناسه</label>
                                            <input type="text" name="searchId" class="form-control form-control-sm"
                                                   id="searchId" placeholder="آیدی">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="searchStartDate" class="header-label">از تاریخ</label>
                                            <input type="text" name="searchStartDate"
                                                   class="form-control form-control-sm date" id="searchStartDate"
                                                   placeholder="از تاریخ">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="searchEndDate" class="header-label">تا تاریخ</label>
                                            <input type="text" name="searchEndDate"
                                                   class="form-control form-control-sm date" id="searchEndDate"
                                                   placeholder="تا تاریخ">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="button" id="search" class="btn btn-primary btn-sm col-12">جستجو
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست انگیزه نامه ها</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.motivations.list')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editMotivation">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش انگیزه نامه</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateMotivation">بروزرسانی</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="delete-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">حذف انگیزه نامه</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <p>آیا مایل به حذف انگیزه نامه می باشید؟</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <a href="" id="delete-modal-link" class="btn btn-danger">حذف</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ url('assets/plugins/persian-date/persian-date.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/persian-date/persian-datepicker.min.js') }}" type="text/javascript"></script>
    <script>
        $(".date").persianDatepicker({format: 'YYYY/MM/DD', initialValue: false});
        $(document).on('click', '.delete', function (e) {
            var deleteResume = new bootstrap.Modal(document.getElementById('delete-modal'), {keyboard: false});
            deleteResume.show();

            document.getElementById("delete-modal-link").href = $(this).data('url')
        });
        $(document).on('click', '.edit', function () {
            var url = $(this).data('url');
            $.ajax({
                url: url,
                success: function (data) {
                    $('#editForm').html(data);
                    var editMotivation = new bootstrap.Modal(document.getElementById('editMotivation'), {keyboard: false});
                    editMotivation.show();
                }
            });
        });
        $(document).on('click', '#updateMotivation', function () {
            var invalidFeedBacks = $(".invalid-feedback").map(function () {
                this.remove();
            }).get();
            $(".is-invalid").removeClass("is-invalid");
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.updateMotivation') }}',
                type: "POST",
                data: new FormData($('#editForm')[0]),
                processData: false,
                contentType: false,
                success: function (data) {
                    dis.html('بروزرسانی');

                    Lobibox.notify(data.type, {
                        title: "",
                        msg: data.msg,
                        icon: 'fa fa-' + data.type,
                        position: 'bottom left',
                        sound: false,
                        mouse_over: "pause"
                    });


                }
            });
        });
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getMotivations(page);
        });

        function getMotivations(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getMotivations') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchId: $('#searchId').val(),
                    searchUser: $('#searchUser').val(),
                    searchTitle: $('#searchTitle').val(),
                    searchStartDate: $('#searchStartDate').val(),
                    searchEndDate: $('#searchEndDate').val(),
                    searchWriter: $('#searchWriter').val(),
                    searchTerm: $('#searchTerm').val()
                },
                success: function (data) {
                    $('#tableBox').html(data);
                }
            });
        }

        $(document).on('click', '#search', function () {
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.getMotivations') }}',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchId: $('#searchId').val(),
                    searchUser: $('#searchUser').val(),
                    searchTitle: $('#searchTitle').val(),
                    searchStartDate: $('#searchStartDate').val(),
                    searchEndDate: $('#searchEndDate').val(),
                    searchWriter: $('#searchWriter').val(),
                    searchTerm: $('#searchTerm').val()
                },
                success: function (data) {
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });
    </script>
@endsection