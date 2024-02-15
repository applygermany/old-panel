@extends('admin.lyout')

@section('title')
    پنل مدیریت - تخفیف ها
@endsection

@section('css')
    <link rel="stylesheet" href="{{ url('assets/plugins/persian-date/persian-datepicker.min.css') }}">
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">تخفیف ها</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">تخفیف ها</li>
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
                                       data-bs-toggle="collapse" data-bs-target="#collapseNewOff" aria-expanded="false" aria-controls="collapseExample">
                                        تخفیف جدید
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body collapse py-3" id="collapseNewOff">
                                <form action="{{ route('admin.saveOff') }}" method="post" class="row">
                                    @csrf
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="type">نوع تخفیف</label>
                                            <select name="type" class="form-control form-select-sm" id="type">
                                                <option value="1">درصدی</option>
                                                <option value="2">مقداری</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="discount">مقدار تخفیف</label>
                                            <input type="text" name="discount" class="form-control form-control-sm @if($errors->has('discount')) is-invalid @endif" id="discount" placeholder="مقدار تخفیف">
                                            @if ($errors->has('discount'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('discount') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="off_type">نوع استفاده</label>
                                            <select name="off_type" id="off_type" class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                <option value="resume">رزومه و انگیزه نامه</option>
                                                <option value="tel-support">مشاوره تلفنی</option>
                                                <option value="final">تسویه نهایی</option>
                                                <option value="other">پیش پرداخت</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="user">کاربران</label>
                                            <select name="user" id="user" class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                <option value="0">ارسال به همه کاربران</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="start_date">از تاریخ</label>
                                            <input type="text" name="start_date" class="form-control form-control-sm date @if($errors->has('start_date')) is-invalid @endif" id="start_date" placeholder="از تاریخ">
                                            @if ($errors->has('start_date'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('start_date') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="end_date">تا تاریخ</label>
                                            <input type="text" name="end_date" class="form-control form-control-sm date @if($errors->has('end_date')) is-invalid @endif" id="end_date" placeholder="تا تاریخ">
                                            @if ($errors->has('end_date'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('end_date') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="code">کد تخفیف</label>
                                            <input type="text" name="code" class="form-control form-control-sm @if($errors->has('code')) is-invalid @endif" id="code" placeholder="کد تخفیف">
                                            @if ($errors->has('code'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('code') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="max">سقف مجاز استفاده</label>
                                            <input type="text" name="max" class="form-control form-control-sm @if($errors->has('max')) is-invalid @endif" id="max" placeholder="تعداد">
                                            @if ($errors->has('max'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('max') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="max">سقف مجاز استفاده هر کاربر</label>
                                            <input type="text" name="userMax" class="form-control form-control-sm @if($errors->has('userMax')) is-invalid @endif" id="userMax" placeholder="تعداد">
                                            @if ($errors->has('userMax'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('userMax') }}</strong></span>
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
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="searchUser" class="header-label">کاربر</label>
                                            <select name="searchUser" id="searchUser" class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }} - {{ $user->mobile }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="searchCode" class="header-label">کد تخفیف</label>
                                            <input type="text" name="searchCode" class="form-control form-control-sm" id="searchCode" placeholder="کد تخفیف">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="row">
                                            <button type="button" id="search" class="btn btn-primary btn-sm col-6">جستجو</button>

                                            <form action="{{route('admin.off.exports')}}" method="post" class=" col-6">
                                                @csrf
                                                <input type="hidden" name="exportUser" id="exportUser">
                                                <input type="hidden" name="exportCode" id="exportCode">
                                                <button type="submit" id="export" class="btn btn-info btn-sm col-12">خروجی اکسل</button>
                                            </form>
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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست تخفیف ها</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.financials.listOffs')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editOff">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش تخفیف</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateOff">بروزرسانی</button>
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
        $(document).on('click','.delete',function(e){
            if(confirm('از حذف این مورد مطمئن هستید ؟')) {
                window.location.href = $(this).attr('href');
            } else {
                e.preventDefault();
            }
        });
        $(document).on('click','.pagination a',function(e){
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getOffs(page);
        });
        function getOffs(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getOffs') }}'+"?page="+page,
                type: "POST",
                data: { _token : '{{ csrf_token() }}', searchUser : $('#searchUser').val(), searchCode : $('#searchCode').val()},
                success: function(data){
                    $('#tableBox').html(data);
                }
            });
        }
        $(document).on('click','#search',function() {
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.getOffs') }}',
                type: "POST",
                data: { _token : '{{ csrf_token() }}', searchUser : $('#searchUser').val(), searchCode : $('#searchCode').val()},
                success: function(data){
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });

        $(document).on('click','#exports',function() {
            document.getElementById("exportUser").value = $('#searchUser').val()
            document.getElementById("exportCode").value = $('#searchCode').val()
        });

        $(document).on('click','.activate',function(){
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: dis.data('url'),
                success: function(data){
                    if(data == 1) {
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
                    }
                    else if(data == 2) {
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
        $(document).on('click','.edit',function(){
            var url = $(this).data('url');
            $.ajax({
                url: url,
                success: function(data) {
                    $('#editForm').html(data);
                    var editOff = new bootstrap.Modal(document.getElementById('editOff'), { keyboard: false });
                    editOff.show();
                    $(".date").persianDatepicker({format: 'YYYY/MM/DD', initialValue: false});
                }
            });
        });
        $(document).on('click','#updateOff',function(){
            var invalidFeedBacks = $(".invalid-feedback").map(function() {
                this.remove();
            }).get();
            $(".is-invalid").removeClass("is-invalid");
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.updateOff') }}',
                type: "POST",
                data: new FormData($('#editForm')[0]),
                processData: false,
                contentType: false,
                success: function(data) {
                    dis.html('بروزرسانی');
                    if(data == 1)
                    {
                        $.ajax({
                            url: '{{ route('admin.getOffs') }}',
                            type: "POST",
                            data: { _token : '{{ csrf_token() }}', searchName : $('#searchName').val(), searchMobile : $('#searchMobile').val()},
                            success: function(data){
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
                    }
                    else if(data == 2)
                    {
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "خطا در بروزرسانی اطلاعات",
                            icon: 'fa fa-warning',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                    else if(data == 3)
                    {
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "این ایمیل قبلاً ثبت شده است",
                            icon: 'fa fa-warning',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                    else {
                        $.each( data['errors'], function( key, value ) {
                            var el = $('#'+key);
                            el.addClass('is-invalid');
                            el.parent().append('<div class="invalid-feedback"><strong>'+value+'</strong></div>');
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
    </script>
@endsection