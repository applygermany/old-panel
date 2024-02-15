@extends('admin.lyout')

@section('title')
    پنل مدیریت - تیم
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/nestable2/jquery.nestable.min.css') }}">
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-name d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">تیم</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">تیم</li>
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
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1" data-bs-toggle="collapse" data-bs-target="#collapseTeam" aria-expanded="false" aria-controls="collapseExample">
                                        عضو تیم جدید
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body collapse py-3" id="collapseTeam">
                                <form action="{{ route('admin.saveTeam') }}" method="post" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="name" class="header-label">نام</label>
                                            <input type="text" name="name" class="form-control form-control-sm @if($errors->has('name')) is-invalid @endif" id="name" placeholder="نام" value="{{ old('name') }}">
                                            @if($errors->has('name'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('name') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="field" class="header-label">تخصص</label>
                                            <input type="text" name="field" class="form-control form-control-sm @if($errors->has('field')) is-invalid @endif" id="field" placeholder="تخصص" value="{{ old('field') }}">
                                            @if($errors->has('field'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('field') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
{{--                                    <div class="col-12 col-md-4">--}}
{{--                                        <div class="form-group float-label">--}}
{{--                                            <label for="position" class="header-label">پوزیشن</label>--}}
{{--                                            <input type="text" name="position" class="form-control form-control-sm @if($errors->has('position')) is-invalid @endif" id="position" placeholder="پوزیشن" value="{{ old('position') }}">--}}
{{--                                            @if($errors->has('position'))--}}
{{--                                                <div class="invalid-feedback"><small>{{ $errors->first('position') }}</small></div>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="image" class="header-label">تصویر</label>
                                            <input type="file" name="image" class="form-control form-control-sm" id="image">
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
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1">
                                        تصویر هدر
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <form action="{{ route('admin.updateTeamHeader') }}" method="post" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="image" class="header-label">تصویر هدر</label>
                                            <input type="file" name="image" class="form-control form-control-sm" id="image">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-success btn-sm col-12">ذخیره</button>
                                    </div>
                                    <div class="form-group text-center col-12">
                                        <br>
                                        <img src="{{ route('teamHeader',['ua'=>strtotime(date('Y-m-d H:i:s'))]) }}" width="80%">
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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست اعضای تیم</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    <div class="d-flex">
                                        <p class="w-50px">#</p>
                                        <p class="w-50">نام</p>
                                        <p class="w-50">تخصص</p>
{{--                                        <p class="w-50">پوزیشن</p>--}}
                                        <p class="w-25">عملیات</p>
                                    </div>
                                    <div class="dd mt-4">
                                        <ol class="dd-list">
                                            @include('admin.teams.list')
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editTeam">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-name">ویرایش عضو تیم</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateTeam">بروزرسانی</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="{{ asset('assets/plugins/nestable2/jquery.nestable.min.js') }}"></script>

    <script>
        var maxDepth = 10;
    </script>

    <script>
        $('.dd').nestable({
            maxDepth: (typeof maxDepth !== 'undefined') ? maxDepth : 10,
            callback: function () {
                if (JSON.stringify($('.dd').nestable('serialize')) != JSON.stringify(teams)) {
                    saveChanges();
                }
            }
        });

        var teams = $('.dd').nestable('serialize');

        function saveChanges() {

            if (!teams.length) {
                return;
            }

            $.ajax({
                url: "{{route('admin.sortTeam')}}",
                type: 'post',
                data: {
                    teams: $('.dd').nestable('serialize'),
                },
                success: function () {
                    teams = $('.dd').nestable('serialize');
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                    //$('#save-changes').show();
                },
                complete: function () {
                    //$('#save-changes').hide();
                },

            });
        }

    </script>

    <script>
        $(document).on('click','.delete',function(e){
            if(confirm('از حذف این مورد مطمئن هستید ؟')) {
                window.location.href = $(this).attr('href');
            } else {
                e.preventDefault();
            }
        });
        $(document).on('click','.edit',function(){
            var url = $(this).data('url');
            $.ajax({
                url: url,
                success: function(data) {
                    $('#editForm').html(data);
                    var editTeam = new bootstrap.Modal(document.getElementById('editTeam'), { keyboard: false });
                    editTeam.show();
                }
            });
        });
        $(document).on('click','#updateTeam',function(){
            var invalidFeedBacks = $(".invalid-feedback").map(function() {
                this.remove();
            }).get();
            $(".is-invalid").removeClass("is-invalid");
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.updateTeam') }}',
                type: "POST",
                data: new FormData($('#editForm')[0]),
                processData: false,
                contentType: false,
                success: function(data) {
                    dis.html('بروزرسانی');
                    if(data == 1)
                    {
                        $.ajax({
                            url: '{{ route('admin.getTeams') }}',
                            type: "GET",
                            data: { _token : '{{ csrf_token() }}' },
                            success: function(data){
                                $('#tableBox').html(data);
                            }
                        });
                        Lobibox.notify('success', {
                            name: " عملیات موفق : ",
                            msg: "اطلاعات با موفقیت ویرایش شد",
                            icon: 'fa fa-success',
                            fieldition: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                    else if(data == 2)
                    {
                        Lobibox.notify('error', {
                            name: " عملیات نا موفق : ",
                            msg: "خطا در بروزرسانی اطلاعات",
                            icon: 'fa fa-warning',
                            fieldition: 'bottom left',
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
                            name: " عملیات نا موفق : ",
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
    </script>
@endsection