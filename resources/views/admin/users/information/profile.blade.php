@extends('admin.lyout') @section('title')
    پنل مدیریت - پروفایل کاربر
@endsection @section('css')
@endsection @section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                     data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                     class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">پروفایل کاربر</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}">داشبورد</a></li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.users') }}">کاربران</a></li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-dark">پروفایل کاربر</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="d-flex flex-column flex-xl-row">
                    <div class="flex-column flex-lg-row-auto w-100 w-xl-400px mb-10">
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-body pt-15">
                                <div class="d-flex flex-center flex-column mb-5">
                                    <div class="symbol symbol-100px symbol-circle mb-7">
                                        <img src="{{ route('imageUser', ['id' => $user->id, 'ua' => strtotime($user->updated_at)]) }}"
                                             alt="image">
                                    </div>
                                    <a href="#"
                                       class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">{{ $user->firstname }}
                                        {{ $user->lastname }}</a>
                                    <div class="fs-5 fw-bold text-gray-400 mb-6">{{ $user->mobile }}</div>
                                    <div class="fs-5 fw-bold text-gray-400 mb-6">{{ $user->email }}</div>
                                    <div class="fs-5 fw-bold text-gray-400 mb-6">{{ $user->codemelli }}</div>
                                    <div class="d-flex flex-wrap flex-center">
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span
                                                        class="w-50px">{{ number_format($user->invoices[0]->transaction_sum) }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">جمع تراکنش ها</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-stack fs-4 py-3">
                                    <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse"
                                         href="#kt_customer_view_details" role="button" aria-expوed="false"
                                         aria-controls="kt_customer_view_details">
                                        <span class="ms-2">جزییات</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div id="kt_customer_view_details" class="collapse show">
                                    <div class="py-5 fs-6">
                                        <div class="fw-bolder mt-5">شناسه</div>
                                        <div class="text-gray-600">{{ $user->id }}</div>
                                        <div class="fw-bolder mt-5">کد قرارداد</div>
                                        <div class="text-gray-600">{{ $user->contract_code }}</div>
                                        <div class="fw-bolder mt-5">پکیج</div>
                                        <div class="text-gray-600">{{ (($user->type == 1) ? "عادی" : (($user->type == 3) ? "نقره ای" : "طلایی") ) }}</div>
                                        <div class="fw-bolder mt-5">ایمیل</div>
                                        <div class="text-gray-600">{{ $user->email }}</div>
                                        <div class="fw-bolder mt-5">آدرس</div>
                                        <div class="text-gray-600">{{ $user->acceptances?$user->acceptances[0]->address:'' }}</div>
                                        <br/>
                                        <a href="{{url('/contract', ['id'=>$user->id])}}" target="_blank"
                                           class="btn btn-info w-100">دانلود قرارداد</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-lg-row-fluid ms-lg-15">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4 active" data-kt-countup-tabs="true"
                                   data-bs-toggle="tab" href="#transations-receipt">رسید ها</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                   data-bs-toggle="tab" href="#transations-pre-invoice">پیش فاکتور ها</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                   href="#applyStatus">وضعیت اپلای</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                   href="#motivation">انگیزه نامه</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                   href="#resume">رزومه</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="transations-receipt" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>رسید ها</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="transactionsBox">
                                            @include('admin.users.information.transactions-receipt')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="transations-pre-invoice" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>فاکتور ها</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="transactionsBox">
                                            @include('admin.users.information.transactions-pre-invoice')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="motivation" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>انگیزه نامه </h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="transactionsBox">
                                            @include('admin.users.information.motivations')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="resume" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>رزومه </h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="transactionsBox">
                                            @include('admin.users.information.resumes')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="applyStatus" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>وضعیت اپلای</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="universitiesBox">
                                            @include('admin.users.information.applyStatus-pre-invoice')
                                        </div>
                                    </div>
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
    <script>

    </script>
@endsection
