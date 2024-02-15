@extends('admin.lyout')

@section('title')
    پنل مدیریت - قراردادها
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
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label for="searchUser" class="header-label">کاربر</label>
                                            <select name="searchUser" id="searchUser"
                                                    class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}
                                                        - {{ $user->mobile }}</option>
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
                                            <label for="userType" class="header-label">نوع قرارداد</label>
                                            <select name="userType" id="userType"
                                                    class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                <option value="3">پایه</option>
                                                <option value="2">ویژه</option>
                                            </select>
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
                                    <div class="col-9">
                                        <button type="button" id="search" class="btn btn-primary btn-sm col-12">جستجو
                                        </button>
                                    </div>
                                    <div class="col-3">
                                        <form id="exportForm"
                                              action="{{ route('admin.contractExport') }}"
                                              method="post">
                                            @csrf
                                            <input type="hidden" id="exportUser" name="exportUser">
                                            <input type="hidden" id="exportTerm" name="exportTerm">
                                            <input type="hidden" id="exportUserType" name="exportUserType">
                                            <input type="hidden" id="exportFromDate" name="exportFromDate">
                                            <input type="hidden" id="exportToDate" name="exportToDate">
                                        </form>
                                        <button type="button" id="export" class="btn btn-dark btn-sm col-12">خروجی
                                            اکسل</button>
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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست قرارداد ها</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.reports.partials.contractList')
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

        $(".date").persianDatepicker({format: 'YYYY/MM/DD', initialValue: false});
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getContracts(page);
        });

        function getContracts(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.contractsSearch') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    userType: $('#userType').val(),
                    searchUser: $('#searchUser').val(),
                    searchStartDate: $('#searchStartDate').val(),
                    searchEndDate: $('#searchEndDate').val(),
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
                url: '{{ route('admin.contractsSearch') }}',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    userType: $('#userType').val(),
                    searchUser: $('#searchUser').val(),
                    searchStartDate: $('#searchStartDate').val(),
                    searchEndDate: $('#searchEndDate').val(),
                    searchTerm: $('#searchTerm').val()
                },
                success: function (data) {
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });

        $(document).on('click', '#exports', function () {
            $("#exportUser").val($("#searchUser").val())
            $("#exportTerm").val($("#searchTerm").val())
            $("#exportFromDate").val($("#searchStartDate").val())
            $("#exportToDate").val($("#searchEndDate").val())
            $("#exportUserType").val($("#userType").val())
            $('#exportForm').submit();
        });
    </script>
@endsection