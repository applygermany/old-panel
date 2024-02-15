@extends('admin.lyout')

@section('title')
    پنل مدیریت - مدیریت رنگ های قالب 
@endsection

@section('css')

@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">رنگ های قالب</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">رنگ های قالب</li>
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
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1"
                                       data-bs-toggle="collapse" data-bs-target="#collapseUniversity" aria-expanded="false" aria-controls="collapseExample">
                                        رنگ جدید
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body collapse py-3" id="collapseUniversity">
                                <form action="{{ route('admin.saveColor') }}" method="post" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-12 col-md-6">
                                        <div class="form-group float-label">
                                            <label for="name" class="header-label">نام رنگ</label>
                                            <input type="text" name="name" class="form-control form-control-sm @if($errors->has('name')) is-invalid @endif" id="name" placeholder="نام" value="{{ old('name') }}">
                                            @if($errors->has('name'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('name') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group float-label">
                                            <label for="code" class="header-label">کد رنگی HEX (بدون #)</label>
                                            <input type="text" name="code" class="form-control form-control-sm @if($errors->has('code')) is-invalid @endif" id="code" placeholder="6 حرفی" value="{{ old('code') }}">
                                            @if($errors->has('code'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('code') }}</small></div>
                                            @endif
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
                                    <div class="col-12 col-md-6">
                                        <div class="form-group float-label">
                                            <label for="searchName" class="header-label">عنوان رنگ</label>
                                            <input type="text" name="searchName" class="form-control form-control-sm" id="searchName" placeholder="نام رنگ">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group float-label">
                                            <label for="searchCode" class="header-label">کد رنگ</label>
                                            <input type="text" name="searchCode" class="form-control form-control-sm" id="searchCode" placeholder="کد">
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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست رنگ ها</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.resumeTemplateColors.list')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editTemplate">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش رنگ</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateTemplate">بروزرسانی</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
       
        $(document).on('click','.pagination a',function(e){
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getTemplates(page);
        });
        function getTemplates(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getResumeTemplateColors') }}'+"?page="+page,
                type: "POST",
                data: { _token : '{{ csrf_token() }}', searchCode : $('#searchCode').val(),searchName : $('#searchName').val()},
                success: function(data){
                    $('#tableBox').html(data);
                }
            });
        }
        $(document).on('click','#search',function() {
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.getResumeTemplateColors') }}',
                type: "POST",
                data: { _token : '{{ csrf_token() }}', searchCode : $('#searchCode').val(),searchName : $('#searchName').val()},
                success: function(data){
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });

        
    </script>
@endsection