@extends('admin.lyout')

@section('title')
    پنل مدیریت - کاربران
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
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">کاربران</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">کاربران</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid">
            <div class="container">
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1"
                                       data-bs-toggle="collapse" data-bs-target="#collapseNewUser" aria-expanded="false"
                                       aria-controls="collapseExample">
                                        کاربر جدید
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body collapse py-3" id="collapseNewUser">
                                <form action="{{ route('admin.saveUser') }}" method="post" class="row">
                                    @csrf
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="mobile" class="header-label">موبایل</label>
                                            <input type="text" name="mobile"
                                                   class="form-control form-control-sm @if($errors->has('mobile')) is-invalid @endif"
                                                   id="mobile" placeholder="موبایل" value="{{ old('mobile') }}">
                                            @if($errors->has('mobile'))
                                                <div class="invalid-feedback">
                                                    <small>{{ $errors->first('mobile') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="email" class="header-label">ایمیل</label>
                                            <input type="text" name="email"
                                                   class="form-control form-control-sm @if($errors->has('email')) is-invalid @endif"
                                                   id="email" placeholder="ایمیل" value="{{ old('email') }}">
                                            @if($errors->has('email'))
                                                <div class="invalid-feedback">
                                                    <small>{{ $errors->first('email') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="password" class="header-label">گذرواژه</label>
                                            <input type="password" name="password"
                                                   class="form-control form-control-sm @if($errors->has('password')) is-invalid @endif"
                                                   id="password" placeholder="گذرواژه" value="{{ old('password') }}">
                                            @if($errors->has('password'))
                                                <div class="invalid-feedback">
                                                    <small>{{ $errors->first('password') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="firstname" class="header-label">نام</label>
                                            <input type="text" name="firstname"
                                                   class="form-control form-control-sm @if($errors->has('firstname')) is-invalid @endif"
                                                   id="firstname" placeholder="نام" value="{{ old('firstname') }}">
                                            @if($errors->has('firstname'))
                                                <div class="invalid-feedback">
                                                    <small>{{ $errors->first('firstname') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="lastname" class="header-label">نام خانوادگی</label>
                                            <input type="text" name="lastname"
                                                   class="form-control form-control-sm @if($errors->has('lastname')) is-invalid @endif"
                                                   id="lastname" placeholder="نام خانوادگی"
                                                   value="{{ old('lastname') }}">
                                            @if($errors->has('lastname'))
                                                <div class="invalid-feedback">
                                                    <small>{{ $errors->first('lastname') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success btn-sm col-12">ثبت</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
                                                   class="form-control form-control-sm" id="searchFirstname"
                                                   placeholder="نام">
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group float-label">
                                            <label for="searchLastname" class="header-label">نام خانوادگی</label>
                                            <input type="text" name="searchLastname"
                                                   class="form-control form-control-sm" id="searchLastname"
                                                   placeholder="نام خانوادگی">
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
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="searchType" class="header-label">نوع</label>
                                            <select name="searchType" class="form-control form-control-sm"
                                                    id="searchType">
                                                <option value="">همه</option>
                                                <option value="1">معمولی</option>
                                                <option value="2">ویژه</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="fromdate">از تاریخ</label>
                                            <input type="text" name="fromdate"
                                                   class="form-control form-control-sm date @if($errors->has('fromdate')) is-invalid @endif"
                                                   id="fromdate">
                                            @if ($errors->has('fromdate'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('fromdate') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="todate">تا تاریخ</label>
                                            <input type="text" name="todate"
                                                   class="form-control form-control-sm date @if($errors->has('todate')) is-invalid @endif"
                                                   id="todate">
                                            @if ($errors->has('todate'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('todate') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="nationalCode">شماره ملی</label>
                                            <input type="text" name="nationalCode"
                                                   class="form-control form-control-sm  @if($errors->has('nationalCode')) is-invalid @endif"
                                                   id="nationalCode">
                                            @if ($errors->has('nationalCode'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('nationalCode') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="contractCode">شماره قرارداد</label>
                                            <input type="text" name="contractCode"
                                                   class="form-control form-control-sm  @if($errors->has('contractCode')) is-invalid @endif"
                                                   id="contractCode">
                                            @if ($errors->has('contractCode'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('contractCode') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <button type="button" id="search" class="btn btn-primary btn-sm col-12">جستجو
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <form id="exportForm" action="{{ route('admin.exportUsers') }}" method="post">
                                            @csrf
                                            <input type="hidden" id="nameExport" name="searchFirstname">
                                            <input type="hidden" id="nameExport2" name="searchLastname">
                                            <input type="hidden" id="fromdate2" name="fromdate">
                                            <input type="hidden" id="todate2" name="todate">
                                            <input type="hidden" id="typeExport" name="searchType">
                                            <input type="hidden" id="searchPhone2" name="searchPhone">
                                            <input type="hidden" id="contractCode2" name="contractCode2">
                                            <input type="hidden" id="nationalCode2" name="nationalCode2">
                                            <button type="submit" id="export" class="btn btn-sm btn-dark col-12">خروجی
                                                اکسل
                                            </button>
                                        </form>

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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست کاربران</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.users.information.list')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editUser">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش کاربر</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateUser">بروزرسانی</button>
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
            if (confirm('از حذف این مورد مطمئن هستید ؟')) {
                window.location.href = $(this).attr('href');
            } else {
                e.preventDefault();
            }
        });
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getUsers(page);
        });

        function getUsers(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getUsersInformation') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchFirstname: $('#searchFirstname').val(),
                    searchLastname: $('#searchLastname').val(),
                    searchMobile: $('#searchMobile').val(),
                    searchEmail: $('#searchEmail').val(),
                    fromdate: $('#fromdate').val(),
                    todate: $('#todate').val(),
                    searchType: $('#searchType').val(),
                    nationalCode: $('#nationalCode').val(),
                    contractCode: $('#contractCode').val()
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
                url: '{{ route('admin.getUsersInformation') }}',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchFirstname: $('#searchFirstname').val(),
                    searchLastname: $('#searchLastname').val(),
                    searchMobile: $('#searchMobile').val(),
                    searchEmail: $('#searchEmail').val(),
                    fromdate: $('#fromdate').val(),
                    todate: $('#todate').val(),
                    searchType: $('#searchType').val(),
                    nationalCode: $('#nationalCode').val(),
                    contractCode: $('#contractCode').val()
                },
                success: function (data) {
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });
        $(document).on('click', '#exports', function () {
            var searchMobile = $('#searchMobile').val();
            var searchFirstname = $('#searchFirstname').val();
            var searchType = $('#searchType').val();
            var fromdate = $('#fromdate').val();
            var todate = $('#todate').val();
            var nationalCode = $('#nationalCode').val();
            var contractCode = $('#contractCode').val();
            $('#searchPhone2').val(searchMobile);
            $('#nameExport').val(searchFirstname);
            $('#typeExport').val(searchType);
            $('#fromdate2').val(fromdate);
            $('#todate2').val(todate);
            $('#nationalCode2').val(nationalCode);
            $('#contractCode2').val(contractCode);
            $('#exportForm').submit();
        });

    </script>
@endsection