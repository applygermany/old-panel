@extends('admin.lyout')

@section('title')
    پنل مدیریت - کاربران
@endsection

@section('css')
    <link rel="stylesheet" href="{{ url('assets/plugins/persian-date/persian-datepicker.min.css') }}">
@endsection

@section('content')
    <style>
        #export{
            border-radius: 5px;
        }
    </style>
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
                                    <span class="card-label fw-bolder fs-3 mb-1">خروجی اکسل</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <form action="{{route('admin.export.user.exel')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-md-12">
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
                                        <div class="col-12 col-md-12">
                                            <div class="form-group float-label">
                                                <label for="telSupportStatus" class="header-label">وضعیت مشاوره تلفنی</label>
                                                <select name="telSupportStatus" class="form-control form-control-sm"
                                                        id="telSupportStatus">
                                                    <option value="1">فقط آنهایی که مشاوره تلفنی نداشته اند</option>
                                                    <option value="2">فقط آنهایی که مشاوره تلفنی داشته اند</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label class="header-label" for="fromdate">از تاریخ</label>
                                                <input required  type="text" name="fromdate"
                                                       class="form-control form-control-sm date @if($errors->has('fromdate')) is-invalid @endif"
                                                       id="fromdate">
                                                @if ($errors->has('fromdate'))
                                                    <span class="invalid-feedback"><strong>{{ $errors->first('fromdate') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label class="header-label" for="todate">تا تاریخ</label>
                                                <input required  type="text" name="todate"
                                                       class="form-control form-control-sm date @if($errors->has('todate')) is-invalid @endif"
                                                       id="todate">
                                                @if ($errors->has('todate'))
                                                    <span class="invalid-feedback"><strong>{{ $errors->first('todate') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <button type="submit" style="margin: 0 auto;display: block" id="export" class="btn btn-primary">
                                                خروجی اکسل
                                            </button>
                                        </div>
                                    </div>
                                </form>

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
    </script>
@endsection