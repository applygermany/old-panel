@extends('admin.lyout')

@section('title')
    پنل مدیریت - فاکتور ها
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
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">فاکتور ها</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">امور مالی</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">فاکتور ها</li>
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
                                    <span class="card-label fw-bolder fs-3 mb-1">جستجو</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="searchUser" class="header-label">کاربر</label>
                                            <select name="searchUser" id="searchUser"
                                                    class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" id="invoiceTitle" name="invoiceTitle" value="confirmed">
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="contractCode">کد قرارداد</label>
                                            <input type="text" name="contractCode"
                                                   class="form-control form-control-sm"
                                                   id="contractCode" placeholder="کد قرارداد">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="paymentMethod" class="header-label">نوع پرداخت</label>
                                            <select name="paymentMethod[]" id="paymentMethod" multiple
                                                    class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                <option value="online">آنلاین</option>
                                                <option value="cash">نقد</option>
                                                <option value="bank">واریز به حساب</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="payFor" class="header-label">فاکتور برای</label>
                                            <select name="payFor[]" id="payFor" multiple
                                                    class="form-control form-select-sm" data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                <option value="resume">رزومه و انگیزه نامه</option>
                                                <option value="final">تسویه نهایی</option>
                                                <option value="other">سایر</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="searchStartDate" class="header-label">از تاریخ</label>
                                            <input type="text" name="searchStartDate"
                                                   class="form-control form-control-sm date" id="searchStartDate"
                                                   placeholder="از تاریخ">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="searchEndDate" class="header-label">تا تاریخ</label>
                                            <input type="text" name="searchEndDate"
                                                   class="form-control form-control-sm date" id="searchEndDate"
                                                   placeholder="تا تاریخ">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="code" class="header-label">کد رسید</label>
                                            <input type="text" name="code"
                                                   class="form-control form-control-sm" id="code"
                                                   placeholder="کد رسید">
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <button type="button" id="search" class="btn btn-primary btn-sm col-12">جستجو
                                        </button>
                                    </div>
                                    <div class="col-3">
                                        <form id="exportForm"
                                              action="{{ route('admin.exportInvoice') }}"
                                              method="post">
                                            @csrf
                                            <input type="hidden" id="exportUser" name="exportUser">
                                            <input type="hidden" id="exportContractCode" name="exportContractCode">
                                            <input type="hidden" id="exportPaymentMethod" name="exportPaymentMethod">
                                            <input type="hidden" id="exportPayFor" name="exportPayFor">
                                            <input type="hidden" id="exportStartDate" name="exportStartDate">
                                            <input type="hidden" id="exportEndDate" name="exportEndDate">
                                            <input type="hidden" id="exportInvoiceTitle" name="exportInvoiceTitle">
                                            <input type="hidden" id="exportCode" name="exportCode">
                                        </form>
                                        <button type="button" id="export" class="btn btn-dark btn-sm col-12">خروجی
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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست فاکتور ها</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.financials.invoices.partials.list-confirmed')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="editInvoice">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش فاکتور</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateInvoice">بروزرسانی</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ url('assets/plugins/persian-date/persian-date.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/persian-date/persian-datepicker.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $(".date").persianDatepicker({format: 'YYYY/MM/DD', initialValue: false});
        });
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getInvoice(page);
        });

        function getInvoice(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.invoiceSearch') }}' + "?page=" + page,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchStartDate: $('#searchStartDate').val(),
                    searchEndDate: $('#searchEndDate').val(),
                    searchUser: $('#searchUser').val(),
                    contractCode: $('#contractCode').val(),
                    paymentMethod: $('#paymentMethod').val(),
                    invoiceTitle: $('#invoiceTitle').val(),
                    payFor: $('#payFor').val(),
                    code: $('#code').val()
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
                url: '{{ route('admin.invoiceSearch') }}',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    searchStartDate: $('#searchStartDate').val(),
                    searchEndDate: $('#searchEndDate').val(),
                    searchUser: $('#searchUser').val(),
                    contractCode: $('#contractCode').val(),
                    paymentMethod: $('#paymentMethod').val(),
                    invoiceTitle: $('#invoiceTitle').val(),
                    payFor: $('#payFor').val(),
                    code: $('#code').val()
                },
                success: function (data) {
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });

        $(document).on('click', '.editInvoice', function () {
            var url = $(this).data('href');
            $.ajax({
                url: url,
                success: function (data) {
                    $('#editForm').html(data);
                    var editFactor = new bootstrap.Modal(document.getElementById('editInvoice'), {keyboard: false});
                    editFactor.show();
                }
            });
        });

        $(document).on('click', '#updateInvoice', function () {
            var invalidFeedBacks = $(".invalid-feedback").map(function () {
                this.remove();
            }).get();
            $(".is-invalid").removeClass("is-invalid");
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.updateInvoiceManager') }}',
                type: "POST",
                data: new FormData($('#editForm')[0]),
                processData: false,
                contentType: false,
                success: function (data) {
                    dis.html('برورسانی');
                    if (data === '1') {
                        $.ajax({
                            url: '{{ route('admin.invoiceSearch') }}',
                            type: "POST",
                            data: {
                                _token: '{{ csrf_token() }}',
                                searchStartDate: $('#searchStartDate').val(),
                                searchEndDate: $('#searchEndDate').val(),
                                searchUser: $('#searchUser').val(),
                                contractCode: $('#contractCode').val(),
                                paymentMethod: $('#paymentMethod').val(),
                                invoiceTitle: $('#invoiceTitle').val(),
                                payFor: $('#payFor').val(),
                                code: $('#code').val()
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

                    } else {
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "خطا در بروزرسانی اطلاعات",
                            icon: 'fa fa-warning',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                }
            });
        });

        $(document).on('click', '#export', function () {
            $("#exportStartDate").val($("#searchStartDate").val())
            $("#exportEndDate").val($("#searchEndDate").val())
            $("#exportUser").val($("#searchUser").val())
            $("#exportContractCode").val($("#contractCode").val())
            $("#exportPaymentMethod").val($("#paymentMethod").val())
            $("#exportPayFor").val($("#payFor").val())
            $("#exportInvoiceTitle").val($("#invoiceTitle").val())
            $("#exportCode").val($("#code").val())
            $('#exportForm').submit();
        });
    </script>
@endsection
