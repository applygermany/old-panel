@extends('admin.lyout')

@section('title')
    پنل مدیریت - فروش موفق
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
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">رزومه ها</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">گزارشات</li>
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
                                    <div class="col-6 col-md-3">
                                        <div class="form-group float-label">
                                            <label for="searchFirstname" class="header-label">نام</label>
                                            <input type="text" name="searchFirstname"
                                                class="form-control form-control-sm" id="searchFirstname" placeholder="نام">
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group float-label">
                                            <label for="searchLastname" class="header-label">نام خانوادگی</label>
                                            <input type="text" name="searchLastname" class="form-control form-control-sm"
                                                id="searchLastname" placeholder="نام خانوادگی">
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group float-label">
                                            <label for="searchMobile" class="header-label">موبایل</label>
                                            <input type="text" name="searchMobile" class="form-control form-control-sm"
                                                id="searchMobile" placeholder="موبایل">
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group float-label">
                                            <label for="searchEmail" class="header-label">ایمیل</label>
                                            <input type="text" name="searchEmail" class="form-control form-control-sm"
                                                id="searchEmail" placeholder="ایمیل">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="button" id="search"
                                            class="btn btn-primary btn-sm col-12">جستجو</button>
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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست مدیران</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.reports.partials.adminsList')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('assets/plugins/persian-date/persian-date.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/persian-date/persian-datepicker.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getAdmins(page);
        });

        function getAdmins(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getWorkExperience') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchFirstname: $('#searchFirstname').val(),
                    searchLastname: $('#searchLastname').val(),
                    searchMobile: $('#searchMobile').val(),
                    searchEmail: $('#searchEmail').val()
                },
                success: function(data) {
                    $('#tableBox').html(data);
                }
            });
        }
        $(document).on('click', '#search', function() {
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.getWorkExperience') }}',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchFirstname: $('#searchFirstname').val(),
                    searchLastname: $('#searchLastname').val(),
                    searchMobile: $('#searchMobile').val(),
                    searchEmail: $('#searchEmail').val()
                },
                success: function(data) {
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });
    </script>
@endsection
