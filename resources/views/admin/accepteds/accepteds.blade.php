@extends('admin.lyout')

@section('title')
پنل مدیریت - پذیرفته شدگان
@endsection

@section('css')

@endsection

@section('content')
<div class="content d-flex flex-column flex-column-fluid">
    <div class="toolbar">
        <div class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">پذیرفته شدگان</h1>
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">پذیرفته شدگان</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="fieldt d-flex flex-column-fluid">
        <div class="container">
            <div class="row g-5 g-xl-8">
                <div class="col-12">
                    <div class="card card-xl-stretch mb-8">
                        <div class="card-header border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1" data-bs-toggle="collapse" data-bs-target="#collapseNewAccepted" aria-expanded="false" aria-controls="collapseExample">
                                    پذیرفته شده جدید
                                </a>
                            </h3>
                        </div>
                        <div class="card-body collapse py-3" id="collapseNewAccepted">
                            <form action="{{ route('admin.saveAccepted') }}" method="post" class="row" enctype="multipart/form-data">
                                @csrf
                                <div class="col-12 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="name" class="header-label">نام دانشجو</label>
                                            <input type="text" name="name" id="name" class="form-control form-select-sm @if($errors->has('name')) is-invalid @endif">

                                            @if($errors->has('name'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('name') }}</small></div>
                                            @endif
                                        </div>

                                    
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="photo" class="header-label">تصویر دانشجو </label>
                                            <input type="file" name="photo" class="form-control form-control-sm" id="photo">
                                        </div>
                                    </div>
                                <div class="col-12 university-base row">
                                <div class="col-12 university-container row">
                                    <div class="col-12 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="university[0][id]" class="header-label">دانشگاه</label>
                                            <select cs="select" name="university[0][id]" id="university[0][id]" class="form-control select-l-0 form-select-sm @if($errors->has('university[0][id]')) is-invalid @endif" data-control="select-l-1">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($universities as $allUniversity)
                                                <option value="{{ $allUniversity->id }}">{{ $allUniversity->title }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('university[0][id]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[0][id]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-6 col-lg-2">
                                        <div class="form-group float-label">
                                            <label for="university[0][field]" class="header-label">رشته</label>
                                            <input type="text" name="university[0][field]" class="form-control form-control-sm @if($errors->has('university[0][field]')) is-invalid @endif" id="university[0][field]" placeholder="رشته" value="{{ old('university[0][field]') }}">
                                            @if($errors->has('university[0][field]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[0][field]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <div class="form-group float-label">
                                            <label for="university[0][grade]" class="header-label">مقطع</label>
                                            <input type="text" name="university[0][grade]" class="form-control form-control-sm @if($errors->has('university[0][grade]')) is-invalid @endif" id="university[0][grade]" placeholder="مقطع" value="{{ old('university[0][grade]') }}">
                                            @if($errors->has('university[0][grade]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[0][grade]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <div class="form-group float-label">
                                            <label for="university[0][semester]" class="header-label">ترم</label>
                                            <input type="text" name="university[0][semester]" class="form-control form-control-sm @if($errors->has('university[0][semester]')) is-invalid @endif" id="university[0][semester]" placeholder="ترم" value="{{ old('university[0][semester]') }}">
                                            @if($errors->has('university[0][semester]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[0][semester]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="university[0][file]" class="header-label">تصویر پذیرش </label>
                                            <input type="file" name="university[0][file]" class="form-control form-control-sm" id="university[0][file]">
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-12 mt-2 mb-4">
                                    <button type="button" class="btn btn-info btn-sm col-4 add-university"><i class="fas fa-plus"></i>اضافه کردن دانشگاه</button>
                                </div>


                                <div class="col-12 col-lg-6">
                                    <div class="form-group float-label">
                                        <label for="visaImage" class="header-label">تصویر ویزا</label>
                                        <input type="file" name="visaImage" class="form-control form-control-sm" id="visaImage">
                                    </div>
                                </div>
                           
                                <div class="col-12 col-lg-6">
                                        <div class="form-group float-label">
                                            <label for="video" class="header-label"> ویدیو</label>
                                            <input type="file" name="video" class="form-control form-control-sm @if($errors->has('video')) is-invalid @endif" id="video" placeholder=" ویدیو" value="{{ old('video') }}">
                                        </div>
                                </div>
                              
                                <div class="col-12 mt-4">
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
                                        <label for="searchName" class="header-label">نام</label>
                                        <input type="text" name="searchName" class="form-control form-control-sm" id="searchName" placeholder="نام">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group float-label">
                                        <label for="searchSemester" class="header-label">ترم </label>
                                        <input type="text" name="searchSemester" class="form-control form-control-sm" id="searchSemester" placeholder="ترم">
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <button type="button" id="search" class="btn btn-primary btn-sm col-12">جستجو</button>
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
                                <span class="card-label fw-bolder fs-3 mb-1">لیست پذیرفته شدگان</span>
                            </h3>
                        </div>
                        <div class="card-body py-3">
                            <div id="tableBox">
                                @include('admin.accepteds.list')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="editAccepted">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ویرایش پذیرفته شده</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fa fa-window-close fa-2x text-danger"></span>
                </div>
            </div>

            <form class="modal-body" id="editForm">

            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                <button type="button" class="btn btn-warning" id="updateAccepted">بروزرسانی</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).on('click', '.delete', function(e) {
        if (confirm('از حذف این مورد مطمئن هستید ؟')) {
            window.location.href = $(this).attr('href');
        } else {
            e.preventDefault();
        }
    });
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        getAccepteds(page);
    });

    function getAccepteds(page) {
        $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
        $.ajax({
            url: "{{ route('admin.getAccepteds') }}" + "?page=" + page,
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                searchName: $('#searchName').val(),
                searchSemester: $('#searchSemester').val(),

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
            url: '{{ route('admin.getAccepteds') }}',
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                searchName: $('#searchName').val(),
                searchSemester: $('#searchSemester').val(),

            },
            success: function(data) {
                dis.html('جستجو');
                $('#tableBox').html(data);
            }
        });
    });
    $(document).on('click', '.edit', function() {
        var url = $(this).data('url');
        $.ajax({
            url: url,
            success: function(data) {
                $('#editForm').html(data);
                var editAccepted = new bootstrap.Modal(document.getElementById('editAccepted'), {
                    keyboard: false
                });
                editAccepted.show();
                $(".add-university-edit").click(function(){
        id = $("select[class*=select-e]").length
        $(".university-base-edit").append(`<div class="col-12 university-container row">
                                    <div class="col-12 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="university[${id}][id]" class="header-label">دانشگاه</label>
                                            <select cs="select" name="university[${id}][id]" id="university[${id}][id]" class="form-control select-e-${id} form-select-sm @if($errors->has('university[${id}][id]')) is-invalid @endif" data-control="select-e-${id}">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($universities as $allUniversity)
                                                <option value="{{ $allUniversity->id }}">{{ $allUniversity->title }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('university[${id}][id]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[${id}][id]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-6 col-lg-2">
                                        <div class="form-group float-label">
                                            <label for="university[${id}][field]" class="header-label">رشته</label>
                                            <input type="text" name="university[${id}][field]" class="form-control form-control-sm @if($errors->has('university[${id}][field]')) is-invalid @endif" id="university[${id}][field]" placeholder="رشته" value="{{ old('university[${id}][field]') }}">
                                            @if($errors->has('university[${id}][field]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[${id}][field]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <div class="form-group float-label">
                                            <label for="university[${id}][grade]" class="header-label">مقطع</label>
                                            <input type="text" name="university[${id}][grade]" class="form-control form-control-sm @if($errors->has('university[${id}][grade]')) is-invalid @endif" id="university[${id}][grade]" placeholder="مقطع" value="{{ old('university[${id}][grade]') }}">
                                            @if($errors->has('university[${id}][grade]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[${id}][grade]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <div class="form-group float-label">
                                            <label for="university[${id}][semester]" class="header-label">ترم</label>
                                            <input type="text" name="university[${id}][semester]" class="form-control form-control-sm @if($errors->has('university[${id}][semester]')) is-invalid @endif" id="university[${id}][semester]" placeholder="ترم" value="{{ old('university[${id}][semester]') }}">
                                            @if($errors->has('university[${id}][semester]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[${id}][semester]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="university[${id}][file]" class="header-label">تصویر پذیرش </label>
                                            <input type="file" name="university[${id}][file]" class="form-control form-control-sm" id="university[${id}][file]">
                                        </div>
                                    </div>
                                </div>`)
                          $("[cs='select']").select2()

    })
            }
        });
    });

    $(".add-university").click(function(){
        id = $("select[class*=select-l]").length
    $(".university-base").append(`<div class="col-12 university-container row">
                                    <div class="col-12 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="university[${id}][id]" class="header-label">دانشگاه</label>
                                            <select cs="select" name="university[${id}][id]" id="university[${id}][id]" class="form-control select-l-${id} form-select-sm @if($errors->has('university[${id}][id]')) is-invalid @endif" data-control="select-l-${id}">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($universities as $allUniversity)
                                                <option value="{{ $allUniversity->id }}">{{ $allUniversity->title }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('university[${id}][id]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[${id}][id]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-6 col-lg-2">
                                        <div class="form-group float-label">
                                            <label for="university[${id}][field]" class="header-label">رشته</label>
                                            <input type="text" name="university[${id}][field]" class="form-control form-control-sm @if($errors->has('university[${id}][field]')) is-invalid @endif" id="university[${id}][field]" placeholder="رشته" value="{{ old('university[${id}][field]') }}">
                                            @if($errors->has('university[${id}][field]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[${id}][field]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <div class="form-group float-label">
                                            <label for="university[${id}][grade]" class="header-label">مقطع</label>
                                            <input type="text" name="university[${id}][grade]" class="form-control form-control-sm @if($errors->has('university[${id}][grade]')) is-invalid @endif" id="university[${id}][grade]" placeholder="مقطع" value="{{ old('university[${id}][grade]') }}">
                                            @if($errors->has('university[${id}][grade]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[${id}][grade]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <div class="form-group float-label">
                                            <label for="university[${id}][semester]" class="header-label">ترم</label>
                                            <input type="text" name="university[${id}][semester]" class="form-control form-control-sm @if($errors->has('university[${id}][semester]')) is-invalid @endif" id="university[${id}][semester]" placeholder="ترم" value="{{ old('university[${id}][semester]') }}">
                                            @if($errors->has('university[${id}][semester]'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('university[${id}][semester]') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="university[${id}][file]" class="header-label">تصویر پذیرش </label>
                                            <input type="file" name="university[${id}][file]" class="form-control form-control-sm" id="university[${id}][file]">
                                        </div>
                                    </div>
                                </div>
                          `)
                          $("[cs='select']").select2()


    })
    $(document).on('click', '#updateAccepted', function() {
        var invalidFeedBacks = $(".invalid-feedback").map(function() {
            this.remove();
        }).get();
        $(".is-invalid").removeClass("is-invalid");
        var dis = $(this);
        dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        $.ajax({
            url: '{{ route('admin.updateAccepted') }}',
            type: "POST",
            data: new FormData($('#editForm')[0]),
            processData: false,
            contentType: false,
            success: function(data) {
                dis.html('بروزرسانی');
                if (data == 1) {
                    $.ajax({
                        url: '{{ route('admin.getAccepteds') }}',
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            searchName: $('#searchName').val(),
                            searchSemester: $('#searchSemester').val(),
                
                        },
                        success: function(data) {
                            $('#tableBox').html(data);
                        }
                    });
                    Lobibox.notify('success', {
                        title: " عملیات موفق : ",
                        msg: "اطلاعات با موفقیت ویرایش شد",
                        icon: 'fa fa-success',
                        fieldition: 'bottom left',
                        sound: false,
                        mouse_over: "pause"
                    });
                } else if (data == 2) {
                    Lobibox.notify('error', {
                        title: " عملیات نا موفق : ",
                        msg: "خطا در بروزرسانی اطلاعات",
                        icon: 'fa fa-warning',
                        fieldition: 'bottom left',
                        sound: false,
                        mouse_over: "pause"
                    });
                } else {
                    $.each(data['errors'], function(key, value) {
                        var el = $('#' + key);
                        el.addClass('is-invalid');
                        el.parent().append('<div class="invalid-feedback"><strong>' + value + '</strong></div>');
                    });
                    Lobibox.notify('error', {
                        title: " عملیات نا موفق : ",
                        msg: "خطا در ویرایش اطلاعات",
                        icon: 'fa fa-warning',
                        fieldition: 'bottom left',
                        sound: false,
                        mouse_over: "pause"
                    });
                }
            }
        });
    });
    $("[cs='select']").select2()

</script>
@endsection