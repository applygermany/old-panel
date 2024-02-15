@extends('admin.lyout')

@section('title')
    پنل مدیریت - درخواست های همکاری
@endsection

@section('css')

@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">درخواست های همکاری</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">درخواست های همکاری</li>
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
                                    <span class="card-label fw-bolder fs-3 mb-1">جستجو</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchName" class="header-label">نام</label>
                                            <input type="text" name="searchName" class="form-control form-control-sm" id="searchName" placeholder="نام">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchFamily" class="header-label">فامیل</label>
                                            <input type="text" name="searchFamily" class="form-control form-control-sm" id="searchFamily" placeholder="فامیل">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchEmail" class="header-label">ایمیل</label>
                                            <input type="text" name="searchEmail" class="form-control form-control-sm" id="searchEmail" placeholder="ایمیل">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchField" class="header-label">رشته</label>
                                            <input type="text" name="searchField" class="form-control form-control-sm" id="searchField" placeholder="رشته">
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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست درخواست های همکاری</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.collaborations.list')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="showCollaboration">
        <div class="modal-dialog modal-l">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">نمایش درخواست</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="col-12 mb-2" id="textCollab"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click','.delete',function(e) {
            if(confirm('از حذف این مورد مطمئن هستید ؟')) {
                window.location.href = $(this).attr('href');
            } else {
                e.preventDefault();
            }
        });
        $(document).on('click','.pagination a',function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getCollaborations(page);
        });
        function getCollaborations(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getCollaborations') }}'+"?page="+page,
                type: "POST",
                data: { _token : '{{ csrf_token() }}', searchName : $('#searchName').val(), searchFamily : $('#searchFamily').val(), searchEmail : $('#searchEmail').val(), searchField : $('#searchField').val()},
                success: function(data){
                    $('#tableBox').html(data);
                }
            });
        }
        $(document).on('click','#search',function() {
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.getCollaborations') }}',
                type: "POST",
                data: { _token : '{{ csrf_token() }}', searchName : $('#searchName').val(), searchFamily : $('#searchFamily').val(), searchEmail : $('#searchEmail').val(), searchField : $('#searchField').val()},
                success: function(data){
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });
        $(document).on('click','.showCollaboration',function() {
            var resume = $(this).data('resume');
            var text = $(this).data('text');
            $('#textCollab').html(text);
            var showCollaboration = new bootstrap.Modal(document.getElementById('showCollaboration'), { keyboard: false });
            showCollaboration.show();
        });
    </script>
@endsection