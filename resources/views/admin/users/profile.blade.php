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
                                    <div class="d-flex flex-wrap flex-center">
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span class="w-75px">{{ $user->acceptances()->count() }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">درخواست ها</div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span class="w-50px">{{ $user->universities()->count() }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">دانشگاه ها</div>
                                        </div>
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
                                        <div class="fw-bolder mt-5">پکیج</div>
                                        <div class="text-gray-600">{{ (($user->type == 1) ? "عادی" : (($user->type == 3) ? "نقره ای" : "طلایی") ) }}</div>
                                        <div class="fw-bolder mt-5">ایمیل</div>
                                        <div class="text-gray-600">{{ $user->email }}</div>
                                        <div class="fw-bolder mt-5">تاریخ عضویت</div>
                                        <div class="text-gray-600">
                                            <?php
                                            $date = explode(' ', $user->created_at);
                                            $date = explode('-', $date[0]);
                                            $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                                            echo $date;
                                            ?>
                                        </div>
                                        <div class="fw-bolder mt-5">تاریخ ثبت درخواست پذیرش</div>
                                        <div class="text-gray-600">
                                            <?php
                                            if (count($acceptances) > 0) {
                                                $date = explode(' ', $acceptances[0]->created_at);
                                                $date = explode('-', $date[0]);
                                                $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                                                echo $date;
                                            } else {
                                                echo '_';
                                            }

                                            ?>
                                        </div>
                                        @foreach($user->supervisors as $supervisor)
                                            @if ($supervisor->supervisor->level === 5)
                                                <div class="fw-bolder mt-5">کارشناس مربوطه</div>
                                                <div class="text-gray-600">
                                                    <a href="#">{{ $supervisor->supervisor->firstname }}
                                                        {{ $supervisor->supervisor->lastname }}</a>
                                                </div>
                                            @endif
                                            @if ($supervisor->supervisor->level === 2)
                                                <div class="fw-bolder mt-5">پشتیبان مربوطه</div>
                                                <div class="text-gray-600">
                                                    <a href="#">{{ $supervisor->supervisor->firstname }}
                                                        {{ $supervisor->supervisor->lastname }}</a>
                                                </div>
                                            @endif
                                        @endforeach

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
                                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                   href="#acceptances">درخواست ها</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                   href="#universities">دانشگاه ها</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                   href="#applyStatus">وضعیت اپلای</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                   data-bs-toggle="tab" href="#transations">تراکنش ها</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                   data-bs-toggle="tab" href="#userTelSupports">پشتیبانی تلفنی</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                   data-bs-toggle="tab" href="#uploads">مدارک</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                   data-bs-toggle="tab" href="#resume_upload">رزومه</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                   data-bs-toggle="tab" href="#motivation_upload">انگیزه نامه</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                   data-bs-toggle="tab" href="#invites">کد دعوت</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="acceptances" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>درخواست ها</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="acceptancesBox">
                                            @include('admin.users.acceptancesList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="universities" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>دانشگاه ها</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <form action="{{ route('admin.addUserUniversity') }}" class="row"
                                              method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <div class="col-12 col-lg-4">
                                                <div class="form-group float-label">
                                                    <label for="university" class="header-label">دانشگاه</label>
                                                    <select name="university" id="university"
                                                            class="form-control form-select-sm @if ($errors->has('university')) is-invalid @endif"
                                                            data-control="select2">
                                                        <option value="">انتخاب کنید</option>
                                                        @foreach ($allUniversities as $allUniversity)
                                                            <option value="{{ $allUniversity->id }}">
                                                                {{ $allUniversity->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('university'))
                                                        <div class="invalid-feedback">
                                                            <small>{{ $errors->first('university') }}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-4">
                                                <div class="form-group float-label">
                                                    <label class="header-label" for="field">رشته</label>
                                                    <input type="text" name="field"
                                                           class="form-control form-control-sm @if ($errors->has('field')) is-invalid @endif"
                                                           id="field" placeholder="رشته">
                                                    @if ($errors->has('field'))
                                                        <span
                                                                class="invalid-feedback"><strong>{{ $errors->first('field') }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-4">
                                                <div class="form-group float-label">
                                                    <label class="header-label" for="chanceGetting">شانس قبولی</label>
                                                    <input type="text" name="chanceGetting"
                                                           class="form-control form-control-sm @if ($errors->has('chanceGetting')) is-invalid @endif"
                                                           id="chanceGetting" placeholder="شانس قبولی">
                                                    @if ($errors->has('chanceGetting'))
                                                        <span
                                                                class="invalid-feedback"><strong>{{ $errors->first('chanceGetting') }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group float-label">
                                                    <label class="header-label" for="link">لینک</label>
                                                    <input type="text" name="link"
                                                           class="form-control form-control-sm @if ($errors->has('link')) is-invalid @endif"
                                                           id="link" placeholder="لینک">
                                                    @if ($errors->has('link'))
                                                        <span
                                                                class="invalid-feedback"><strong>{{ $errors->first('link') }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group float-label">
                                                    <label class="header-label" for="desc">توضیحات</label>
                                                    <input type="text" name="desc"
                                                           class="form-control form-control-sm @if ($errors->has('desc')) is-invalid @endif"
                                                           id="desc" placeholder="توضیحات">
                                                    @if ($errors->has('desc'))
                                                        <span
                                                                class="invalid-feedback"><strong>{{ $errors->first('desc') }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="offer">پیشنهاد کارشناس</label>
                                                    <input type="checkbox" name="offer" class="form-check-input"
                                                           id="offer">
                                                    @if ($errors->has('offer'))
                                                        <span
                                                                class="invalid-feedback"><strong>{{ $errors->first('offer') }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-danger btn-sm col-12">اضافه
                                                    کردن
                                                </button>
                                            </div>
                                        </form>
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="universitiesBox">
                                            @include('admin.users.universitiesList')
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
                                            @include('admin.users.applyStatusList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="transations" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>تراکنش ها</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="transactionsBox">
                                            @include('admin.users.transactionsList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="userTelSupports" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>پشتیبانی تلفنی</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="userTelSupportsBox">
                                            @include(
                                                'admin.users.userTelSupportsList'
                                            )
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="uploads" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>مدارک</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="uploadsBox">
                                            @include('admin.users.uploadsList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="resume_upload" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>لیست رزومه ها</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="uploadsBox">
                                            @include('admin.users.resumeUpload')
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="motivation_upload" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>لیست انگیزه نامه ها</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="uploadsBox">
                                            @include('admin.users.motivationUpload')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="invites" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>کد دعوت</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="uploadsBox">
                                            @include('admin.users.affCodeList')
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
        $(document).on('click', '.delete', function (e) {
            if (confirm('از حذف این مورد مطمئن هستید ؟')) {
                window.location.href = $(this).attr('href');
            } else {
                e.preventDefault();
            }
        });
        $(document).on('click', '#acceptancesBox .pagination a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            userAcceptances(page);
        });

        function userAcceptances(page) {
            $('#acceptancesBox').html(
                '<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.userAcceptances') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: '{{ $user->id }}'
                },
                success: function (data) {
                    $('#acceptancesBox').html(data);
                }
            });
        }

        $(document).on('click', '#universitiesBox .pagination a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            userUniversities(page);
        });

        function userUniversities(page) {
            $('#universitiesBox').html(
                '<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.userUniversities') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: '{{ $user->id }}'
                },
                success: function (data) {
                    $('#universitiesBox').html(data);
                }
            });
        }

        $(document).on('click', '#transactionsBox .pagination a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            userTransactions(page);
        });

        function userTransactions(page) {
            $('#transactionsBox').html(
                '<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.userTransactions') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: '{{ $user->id }}'
                },
                success: function (data) {
                    $('#transactionsBox').html(data);
                }
            });
        }

        $(document).on('click', '#userUserTelSupports .pagination a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            userUserTelSupports(page);
        });

        function userUserTelSupports(page) {
            $('#userUserTelSupportsBox').html(
                '<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.userUserTelSupports') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: '{{ $user->id }}'
                },
                success: function (data) {
                    $('#userUserTelSupportsBox').html(data);
                }
            });
        }

        $(document).on('click', '#uploads .pagination a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            uploads(page);
        });

        function uploads(page) {
            $('#uploadsBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.userUploads') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: '{{ $user->id }}'
                },
                success: function (data) {
                    $('#uploadsBox').html(data);
                }
            });
        }
    </script>
@endsection
