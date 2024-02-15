@extends('admin.lyout')

@section('title')
    پنل مدیریت - مدیران
@endsection

@section('css')
    <style>
        .permissions label {
            margin: 0 5px;
        }
    </style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                     data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                     class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">مدیران</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">مدیران</li>
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
                                        مدیر جدید
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body collapse py-3" id="collapseNewUser">
                                <form action="{{ route('admin.saveAdmin') }}" method="post" class="row">
                                    @csrf
                                    <div class="col-12 col-lg-4">
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
                                    <div class="col-12 col-lg-4">
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

                                    <div class="col-12 col-lg-4">
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
                                    <div class="col-12 col-lg-4">
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
                                    <div class="col-12 col-lg-4">
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
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="level" class="header-label">نوع کاربر</label>
                                            <select name="level" class="form-control form-control-sm" id="level">
                                                <option value="2">پشتیبان</option>
                                                <option value="3">کارشناس ارشد</option>
                                                <option value="5">کارشناس</option>
                                                <option value="6">نگارنده</option>
                                                <option value="7">مشاور</option>
                                                <option @if (auth()->user()->isSuperAdmin == 0) disabled
                                                        @endif value="4">ادمین
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="p-2 col-12 support_permissions" style="display:none">
                                        دسترسی های کارشناس
                                        <div class="mt-2 col-12">
                                            <label>
                                                مشاوره تلفنی ویژه
                                                <input type="checkbox" name="sup_perm_tel" checked="false">
                                            </label>
                                        </div>
                                        <div class="mt-2 col-12">
                                            <label>
                                                مشاوره تلفنی عادی
                                                <input type="checkbox" name="sup_perm_tel_normal" checked="false">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row levels" style="display:none">
                                        <div class="mt-2 col-4">
                                            <label>
                                               لول 1
                                                <input type="checkbox" name="sup_level_one">
                                            </label>
                                        </div>
                                        <div class="mt-2 col-4">
                                            <label>
                                                لول 2
                                                <input type="checkbox" name="sup_level_two">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="p-2 col-12 permissions" style="display:none">
                                        دسترسی های ادمین
                                        <div class="mt-2 col-12">
                                            <label>
                                                داشبورد
                                                <input type="checkbox" name="perm_dashboard" checked="false">
                                            </label>
                                            <label>
                                                مدیریت ادمین
                                                <input type="checkbox" name="perm_admins" checked="false">
                                            </label>
                                            <label>
                                                مدیریت کاربران
                                                <input type="checkbox" name="perm_users" checked="false">
                                            </label>
                                            <label>
                                                مدیریت اطلاعات کاربران
                                                <input type="checkbox" name="perm_users_information" checked="false">
                                            </label>
                                            <label>
                                                سفارشات
                                                <input type="checkbox" name="perm_orders" checked="false">
                                            </label>
                                            <label>
                                                مدیریت اپلای
                                                <input type="checkbox" name="perm_applies" checked="false">
                                            </label>
                                            <label>
                                                مدیریت وبینار
                                                <input type="checkbox" name="perm_webinars" checked="false">
                                            </label>
                                            <label>
                                                مدیریت دانشگاه ها
                                                <input type="checkbox" name="perm_universities" checked="false">
                                            </label>
                                            <label>
                                                امور مالی
                                                <input type="checkbox" name="perm_financial" checked="false">
                                            </label>
                                            <label>
                                                تنظیمات
                                                <input type="checkbox" name="perm_settings" checked="false">
                                            </label>

                                            <label>
                                                چت
                                                <input type="checkbox" name="perm_chat" checked="false">
                                            </label>

                                            <label>
                                                نوتیفیکیشن
                                                <input type="checkbox" name="perm_notification" checked="false">
                                            </label>

                                            <label>
                                                گزارشات
                                                <input type="checkbox" name="perm_reports" checked="false">
                                            </label>

                                            <label>
                                               مدیر مالی
                                                <input type="checkbox" name="perm_financial_confirm" checked="false">
                                            </label>

                                            <label>
                                                نتایج مشاوره
                                                <input type="checkbox" name="perm_telsupport_result" checked="false">
                                            </label>

                                            <label>
                                               مشاوره تلفنی
                                                <input type="checkbox" name="perm_telSupports" checked="false">
                                            </label>
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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست مدیران</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.admins.list')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editAdmin">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش مدیر</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateAdmin">بروزرسانی</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getAdmins(page);
        });

        function getAdmins(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getAdmins') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchFirstname: $('#searchFirstname').val(),
                    searchLastname: $('#searchLastname').val(),
                    searchMobile: $('#searchMobile').val(),
                    searchEmail: $('#searchEmail').val()
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
                url: '{{ route('admin.getAdmins') }}',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchFirstname: $('#searchFirstname').val(),
                    searchLastname: $('#searchLastname').val(),
                    searchMobile: $('#searchMobile').val(),
                    searchEmail: $('#searchEmail').val()
                },
                success: function (data) {
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });
        $(document).on('click', '.activate', function () {
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: dis.data('url'),
                success: function (data) {
                    if (data == 1) {
                        dis.html('فعال');
                        dis.removeClass('btn-danger');
                        dis.addClass('btn-success');
                        Lobibox.notify('success', {
                            title: " عملیات موفق : ",
                            msg: "اطلاعات با موفقیت ویرایش شد",
                            icon: 'fa fa-success',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    } else if (data == 2) {
                        dis.html('غیر فعال');
                        dis.removeClass('btn-success');
                        dis.addClass('btn-danger');
                        Lobibox.notify('success', {
                            title: " عملیات موفق : ",
                            msg: "اطلاعات با موفقیت ویرایش شد",
                            icon: 'fa fa-success',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    } else {
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "خطا در ویرایش اطلاعات",
                            icon: 'fa fa-error',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                }
            });
        });
        $(document).on('click', '.edit', function () {
            var url = $(this).data('url');
            $.ajax({
                url: url,
                success: function (data) {
                    $('#editForm').html(data);
                    var editAdmin = new bootstrap.Modal(document.getElementById('editAdmin'), {keyboard: false});
                    editAdmin.show();
                }
            });
        });
        $(document).on('click', '#updateAdmin', function () {
            var invalidFeedBacks = $(".invalid-feedback").map(function () {
                this.remove();
            }).get();
            $(".is-invalid").removeClass("is-invalid");
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.updateAdmin') }}',
                type: "POST",
                data: new FormData($('#editForm')[0]),
                processData: false,
                contentType: false,
                success: function (data) {
                    dis.html('بروزرسانی');
                    if (data == 1) {
                        $.ajax({
                            url: '{{ route('admin.getAdmins') }}',
                            type: "POST",
                            data: {
                                _token: '{{ csrf_token() }}',
                                searchFirstname: $('#searchFirstname').val(),
                                searchLastname: $('#searchLastname').val(),
                                searchMobile: $('#searchMobile').val(),
                                searchEmail: $('#searchEmail').val()
                            },
                            success: function (data) {
                                $('#tableBox').html(data);
                            }
                        });
                        Lobibox.notify('success', {
                            title: " عملیات موفق : ",
                            msg: "اطلاعات با موفقیت ویرایش شد",
                            icon: 'fa fa-success',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    } else if (data == 2) {
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "خطا در بروزرسانی اطلاعات",
                            icon: 'fa fa-warning',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    } else if (data == 3) {
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "این ایمیل قبلاً ثبت شده است",
                            icon: 'fa fa-warning',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    } else if (data == 4) {
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "این موبایل قبلاً ثبت شده است",
                            icon: 'fa fa-warning',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    } else {
                        $.each(data['errors'], function (key, value) {
                            var el = $('#' + key);
                            el.addClass('is-invalid');
                            el.parent().append('<div class="invalid-feedback"><strong>' + value + '</strong></div>');
                        });
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "خطا در ویرایش اطلاعات",
                            icon: 'fa fa-warning',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                }
            });
        });
        $('[name=level]').on('change', function () {
            if (this.value === '4') {
                $(".permissions").show();
            } else {
                $(".permissions").hide();
            }
            if (this.value === '5' || this.value === '7' || this.value === '3') {
                $(".support_permissions").show();
                $(".levels").show();
            } else {
                $(".support_permissions").hide();
                $(".levels").hide();
            }
        });

    </script>
@endsection