@extends('admin.lyout')

@section('title')
    پنل مدیریت - زمان های مشاوره
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css"/>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                     data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                     class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">قالب های رزومه</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">زمان های مشاوره</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="cityt d-flex flex-column-fluid">
            <div class="container">
                <div class="post d-flex flex-column-fluid">
                    <div class="container">
                        <div class="row g-5 g-xl-8">
                            <div class="col-12">
                                <div class="card card-xl-stretch mb-8">
                                    <div class="card-header border-0">
                                        <h3 class="card-title align-items-start flex-column">
                                            <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1">
                                                اطلاعات
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="card-body py-3">
                                        <div class="row">
                                            <div class="col-12 col-lg-4">
                                                <div class="form-group float-label">
                                                    <p>نام</p>
                                                    <p>{{$expert->firstname}}</p>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-4">
                                                <div class="form-group float-label">
                                                    <p>نام خانوادگی</p>
                                                    <p>{{$expert->lastname}}</p>
                                                </div>
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
                                            <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1">
                                                ثبت رزرو
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="card-body py-3">
                                        <form method="post"
                                              action="{{route('admin.telSupportsReserveTime', ['id'=>$expert->id])}}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group float-label">
                                                        <label for="date" class="header-label"> تاریخ</label>
                                                        <input type="text" name="date"
                                                               class="form-control form-control-sm date" id="date"
                                                               placeholder=" تاریخ">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group float-label">
                                                        <label for="fromTime" class="header-label">ار ساعت</label>
                                                        <input type="text" name="fromTime"
                                                               class="form-control form-control-sm" id="fromTime"
                                                               placeholder="ار ساعت">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group float-label">
                                                        <label for="toTime" class="header-label">تا ساعت</label>
                                                        <input type="text" name="toTime"
                                                               class="form-control form-control-sm" id="toTime"
                                                               placeholder="تا ساعت">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group float-label">
                                                        <label class="header-label" for="user">کاربر</label>
                                                        <select name="user" id="user"
                                                                class="form-control form-select-sm"
                                                                data-control="select2">
                                                            <option value="">انتخاب کنید</option>
                                                            @foreach($users as $user)
                                                                <option value="{{ $user->id }}"
                                                                        data-value="{{$user}}">{{ $user->firstname }} {{ $user->lastname }}
                                                                    - 0{{$user->mobile}} - {{$user->email}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-8">
                                                    <div class="form-group float-label">
                                                        <label for="title" class="header-label">عنوان</label>
                                                        <input type="text" name="title"
                                                               class="form-control form-control-sm" id="title"
                                                               placeholder="عنوان">
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-success btn-sm col-4">
                                                ثبت مشاوره
                                            </button>

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
                                            <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1">
                                                لیست
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="card-body py-3">
                                        <div id="tableBox">
                                            @include('admin.telSupports.partials.time-list')
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

    <div class="modal fade" tabindex="-1" id="selectReserve">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ثبت رزرو</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="reserveForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="reserveTime">ثبت</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
    <script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>

    <script>
        $(".date").pDatepicker({
            format: "YYYY-MM-DD",
            onSelect: "year",
            initialValue: false
        });


        $(document).on('click', '.reserve', function () {
            var url = $(this).data('href');
            $.ajax({
                url: url,
                success: function (data) {
                    $('#reserveForm').html(data);
                    var editFactor = new bootstrap.Modal(document.getElementById('selectReserve'), {keyboard: false});
                    editFactor.show();
                }
            });
        });

        $(document).on('click', '#reserveTime', function () {
            var invalidFeedBacks = $(".invalid-feedback").map(function () {
                this.remove();
            }).get();
            $(".is-invalid").removeClass("is-invalid");
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.telSupportsExpertReserveTime') }}',
                type: "POST",
                data: new FormData($('#reserveForm')[0]),
                processData: false,
                contentType: false,
                success: function (data) {
                    dis.html('ثبت');
                    if (data === '1') {
                        $.ajax({
                            url: '{{ route('admin.telSupportsExpertGetTimes') }}',
                            type: "POST",
                            data:{
                                id: {{$expert->id}}
                            },
                            success: function (data) {
                                $('#tableBox').html(data);
                            }
                        });
                        Lobibox.notify('success', {
                            title: " عملیات موفق : ",
                            msg: "اطلاعات با موفقیت ثبت شد",
                            icon: 'fa fa-success',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });

                    } else {
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "خطا در ثبت اطلاعات",
                            icon: 'fa fa-warning',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                }
            });
        });

    </script>
@endsection