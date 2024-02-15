@extends('admin.lyout')

@section('title')
    پنل مدیریت - درخواست های شرکت در وبینار
@endsection

@section('css')
    <link rel="stylesheet" href="{{ url('assets/plugins/persian-date/persian-datepicker.min.css') }}">
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                    data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">درخواست های شرکت در وبینار</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">درخواست های شرکت در وبینار</li>
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
                                            <input type="text" name="searchName" class="form-control form-control-sm"
                                                id="searchName" placeholder="نام">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchFamily" class="header-label">فامیل</label>
                                            <input type="text" name="searchFamily" class="form-control form-control-sm"
                                                id="searchFamily" placeholder="فامیل">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchEmail" class="header-label">ایمیل</label>
                                            <input type="text" name="searchEmail" class="form-control form-control-sm"
                                                id="searchEmail" placeholder="ایمیل">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchMobile" class="header-label">موبایل</label>
                                            <input type="text" name="searchMobile" class="form-control form-control-sm"
                                                id="searchMobile" placeholder="موبایل">
                                        </div>
                                    </div>

                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchGrade" class="header-label">مدرک</label>
                                            <input type="text" name="searchGrade" class="form-control form-control-sm"
                                                id="searchGrade" placeholder="مدرک">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchField" class="header-label">رشته</label>
                                            <input type="text" name="searchField" class="form-control form-control-sm"
                                                id="searchField" placeholder="رشته">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchStartDate" class="header-label">از تاریخ</label>
                                            <input type="text" name="searchStartDate"
                                                class="form-control form-control-sm date" id="searchStartDate"
                                                placeholder="از تاریخ">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchEndDate" class="header-label">تا تاریخ</label>
                                            <input type="text" name="searchEndDate"
                                                class="form-control form-control-sm date" id="searchEndDate"
                                                placeholder="تا تاریخ">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <button type="button" id="search" class="btn btn-primary btn-sm col-12">جستجو
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <form id="exportForm"
                                            action="{{ route('admin.exportParticipants', ['webinarId' => $webinarId]) }}"
                                            method="post">
                                            @csrf
                                            <input type="hidden" id="nameExport" name="exportFirstName">
                                            <input type="hidden" id="nameExport2" name="exportLastName">
                                            <input type="hidden" id="priceExport" name="exportPrice">
                                            <input type="hidden" id="emailExport" name="exportEmail">
                                            <input type="hidden" id="mobileExport" name="exportMobile">
                                            <input type="hidden" id="fieldExport" name="exportField">
                                            <input type="hidden" id="gradeExport" name="exportGrade">
                                            <input type="hidden" id="instagramExport" name="exportInstagram">
                                            <input type="hidden" id="telegramExport" name="exportTelegram">
                                            <input type="hidden" id="dateExport" name="exportDate">
                                        </form>
                                        <button type="button" id="export" class="btn btn-sm btn-dark col-12">خروجی
                                            اکسل
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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست درخواست های شرکت در وبینار</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.webinars.participationList')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="showWebinarsParticipation">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">نمایش مشخصات</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <div class="modal-body row" id="webinarsParticipation">
                    <div class="col-12 col-lg-6 mb-2" id="telegram"></div>
                    <div class="col-12 col-lg-6 mb-2" id="instagram"></div>
                    <div class="col-12 col-lg-6 mb-2" id="field"></div>
                    <div class="col-12 col-lg-6 mb-2" id="grade"></div>
                    <div class="col-12">
                        <iframe src="" id="webinarReceipt" class="col-12" style="height: 500px; width: 100%; object-fit: fill"></iframe>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('assets/plugins/persian-date/persian-date.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/persian-date/persian-datepicker.min.js') }}" type="text/javascript"></script>
    <script>
        $(".date").persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValue: false
        });
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getWebinarsParticipation(page);
        });

        function getWebinarsParticipation(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getWebinarsParticipationPagination') }}' + "?page=" + page + "&webinarId=" + "{{$webinarId}}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchName: $('#searchName').val(),
                    searchFamily: $('#searchFamily').val(),
                    searchEmail: $('#searchEmail').val(),
                    searchMobile: $('#searchMobile').val(),
                    searchGrade: $('#searchGrade').val(),
                    searchField: $('#searchField').val(),
                    searchStartDate: $('#searchStartDate').val(),
                    searchEndDate: $('#searchEndDate').val()
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
                url: '{{ route('admin.getWebinarsParticipationPagination') }}' + "?webinarId=" + "{{$webinarId}}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchName: $('#searchName').val(),
                    searchFamily: $('#searchFamily').val(),
                    searchEmail: $('#searchEmail').val(),
                    searchMobile: $('#searchMobile').val(),
                    searchGrade: $('#searchGrade').val(),
                    searchField: $('#searchField').val(),
                    searchStartDate: $('#searchStartDate').val(),
                    searchEndDate: $('#searchEndDate').val()
                },
                success: function(data) {
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });
        $(document).on('click', '.showWebinar', function() {
            $('#telegram').html("تلگرام : " + $(this).data('telegram'));
            $('#instagram').html("اینستاگرام : " + $(this).data('instagram'));
            $('#field').html("رشته : " + $(this).data('field'));
            $('#grade').html("مقطع : " + $(this).data('grade'));
            $('#webinarReceipt').attr("src", $(this).data('webinar_receipt'));
            var showWebinarsParticipation = new bootstrap.Modal(document.getElementById(
                'showWebinarsParticipation'), {
                    keyboard: false
                });
            showWebinarsParticipation.show();
        });
        $(document).on('click', '#export', function() {
            $('#exportForm').submit();

        });
    </script>
@endsection
