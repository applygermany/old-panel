@extends('admin.lyout')

@section('title')
    پنل مدیریت - فاکتور ها
@endsection

@section('css')
@endsection

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css"/>
    <style>
        .pwt-btn-calendar {
            display: none !important;
        }
    </style>

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
                <div class="col-12">
                    <div class="card card-xl-stretch mb-8">
                        <div class="card-header border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">صدور فاکتور جدید</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.saveInvoice') }}" method="post" class="row">
                                @csrf
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="user">کاربر</label>
                                        <select name="user" id="user" class="form-control form-select-sm"
                                                data-control="select2" onchange="downloadContract(this)">
                                            <option value="">انتخاب کنید</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" data-value="{{$user}}">{{ $user->firstname }} {{ $user->lastname }}
                                                    - 0{{$user->mobile}} - {{$user->email}}</option>
                                            @endforeach
                                        </select>

                                    </div> @if ($errors->has('user'))
                                        <span class="invalid-feedback"><strong>{{ $errors->first('user') }}</strong></span>
                                    @endif
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="invoice_title">نوع فاکتور</label>
                                        <select name="invoice_title" id="invoice_title"
                                                onchange="selectInvoiceType(this)"
                                                class="form-control form-select-sm" data-control="select2">
                                            <option value="" selected>انتخاب کنید</option>
                                            <option value="receipt">رسید</option>
                                            <option value="pre-invoice">پیش فاکتور</option>
                                        </select>

                                    </div> @if ($errors->has('invoice_title'))
                                        <span class="invalid-feedback"><strong>{{ $errors->first('invoice_title') }}</strong></span>
                                    @endif
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="invoiceType">فاکتور برای</label>
                                        <select name="invoice_type" id="paymentType"
                                                class="form-control form-select-sm" data-control="select2">
                                            <option value="" selected>انتخاب کنید</option>
                                            <option value="resume">رزومه و انگیزه نامه</option>
                                            <option value="final">تسویه نهایی</option>
                                            <option value="tel-support">مشاوره تلفنی</option>
                                            <option value="other">پیش پرداخت</option>
                                        </select>

                                    </div> @if ($errors->has('invoice_type'))
                                        <span class="invalid-feedback"><strong>{{ $errors->first('invoice_type') }}</strong></span>
                                    @endif
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="paymentMethod">نوع تسویه</label>
                                        <select name="payment_method" id="paymentMethod"
                                                onchange="selectPaymentMethod(this)"
                                                class="form-control form-select-sm" disabled>
                                            <option value="" selected>انتخاب کنید</option>
                                            <option value="online">پرداخت آنلاین</option>
                                            <option value="cash">پرداخت نقدی</option>
                                            <option value="bank">واریز به حساب</option>
                                        </select>

                                    </div> @if ($errors->has('payment_method'))
                                        <span class="invalid-feedback"><strong>{{ $errors->first('payment_method') }}</strong></span>
                                    @endif
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="bankAccount">حساب های بانکی</label>
                                        <select name="bankAccount" id="bankAccount"
                                                class="form-control form-select-sm" data-control="select2">
                                            <option value="" selected>انتخاب کنید</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">{{$bank->bank_name}}
                                                    | {{$bank->card_number}}</option>
                                            @endforeach
                                        </select>

                                    </div> @if ($errors->has('payment_method'))
                                        <span class="invalid-feedback"><strong>{{ $errors->first('payment_method') }}</strong></span>
                                    @endif
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="ir_amount">مبلغ به ریال</label>
                                        <input type="text" name="ir_amount" disabled
                                               class="form-control form-control-sm @if($errors->has('ir_amount')) is-invalid @endif"
                                               id="ir_amount" placeholder="مبلغ فاکتور به ریال">
                                        @if ($errors->has('ir_amount'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('ir_amount') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="euro_amount">مبلغ</label>
                                        <input type="text" value="0" name="euro_amount"
                                               class="form-control form-control-sm @if($errors->has('euro_amount')) is-invalid @endif"
                                               id="euro_amount" placeholder="مبلغ فاکتور">
                                        @if ($errors->has('euro_amount'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('euro_amount') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="currency">نوع ارز</label>
                                        <select name="currency" id="currency"
                                                class="form-control form-select-sm" data-control="select2">
                                            <option value="" selected>انتخاب کنید</option>
                                            <option value="euro">یورو</option>
                                            <option value="dollar">دلار</option>
                                        </select>
                                    </div> @if ($errors->has('currency'))
                                        <span class="invalid-feedback"><strong>{{ $errors->first('currency') }}</strong></span>
                                    @endif
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="discount_type">نوع تخفیف</label>
                                        <select name="discount_type" class="form-control form-select-sm"
                                                id="discount_type">
                                            <option value="percent">درصدی</option>
                                            <option value="fixed">مقداری</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="discount_amount">تخفیف</label>
                                        <input type="text" name="discount_amount"
                                               class="form-control form-control-sm @if($errors->has('discount_amount')) is-invalid @endif"
                                               id="discount_amount" placeholder="تخفیف">
                                        @if ($errors->has('discount'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('discount_amount') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="discount_description">توضیحات تخفیف</label>
                                        <input type="text" name="discount_description"
                                               class="form-control form-control-sm @if($errors->has('discount_description')) is-invalid @endif"
                                               id="discount_description" placeholder="توضیحات">
                                        @if ($errors->has('discount_description'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('discount_description') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="invoice_description">توضیحات فاکتور</label>
                                        <input type="text" name="invoice_description"
                                               class="form-control form-control-sm @if($errors->has('invoice_description')) is-invalid @endif"
                                               id="invoice_description" placeholder="توضیحات">
                                        @if ($errors->has('invoice_description'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('invoice_description') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label for="paymentAt" class="header-label">تاریخ پرداخت</label>
                                        <input type="text" name="paymentAt" required disabled value=""
                                               class="form-control form-control-sm date example1" id="paymentAt" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="discount_code">کد تخفیف</label>
                                        <input type="text" name="discount_code"
                                               class="form-control form-control-sm @if($errors->has('discount_code')) is-invalid @endif"
                                               id="discount_code" placeholder="کد تخفیف">
                                        @if ($errors->has('discount_code'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('discount_code') }}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 col-lg-4 d-none">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="discount_code_inviter">کد معرف</label>
                                        <input type="text" name="discount_code_inviter" disabled
                                               class="form-control form-control-sm @if($errors->has('discount_code_inviter')) is-invalid @endif"
                                               id="discount_code_inviter" placeholder="کد معرف">
                                        @if ($errors->has('discount_code_inviter'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('discount_code_inviter') }}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="balance">کیف پول</label>
                                        <input type="text" name="balance"
                                               class="form-control form-control-sm @if($errors->has('balance')) is-invalid @endif"
                                               id="balance" placeholder="کیف پول">
                                        @if ($errors->has('balance'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('balance') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="extra_universities">تعداد دانشگاه اضافه</label>
                                        <input type="text" disabled name="extra_universities"
                                               class="form-control form-control-sm @if($errors->has('extra_universities')) is-invalid @endif"
                                               id="extra_universities" placeholder="دانشگاه اضافه">
                                        @if ($errors->has('extra_universities'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('extra_universities') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group float-label">
                                        <label class="header-label" for="extra_universities_total_price">مجموع هزینه دانشگاه های اضافی(یورو)</label>
                                        <input type="text" disabled name="extra_universities_total_price"
                                               class="form-control form-control-sm @if($errors->has('extra_universities_total_price')) is-invalid @endif"
                                               id="extra_universities_total_price" placeholder="هزینه دانشگاه های اضافی">
                                        @if ($errors->has('extra_universities_total_price'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('extra_universities_total_price') }}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-12 col-lg-6">
                                        <button class="btn btn-warning w-100" type="button" id="checkCode">بررسی کد
                                        </button>
                                    </div>


                                    <div class="col-12 col-lg-6">
                                        <a class="btn btn-info w-100" href="#" id="downloadContract" target="_blank"
                                           style="display: none">دانلود قرارداد</a>
                                    </div>
                                </div>

                                <br/>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-success btn-sm col-12">ثبت</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="editFactor">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تایید پرداخت</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateFactor">بروزرسانی</button>
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
            format: "YYYY/MM/DD",
            onSelect: "year"
        });
        function selectInvoiceType(value) {
            if (value.value === 'pre-invoice') {
                document.getElementById('paymentMethod').disabled = true
                // document.getElementById('bankAccount').disabled = true
                document.getElementsByName('ir_amount')[0].disabled = true
                document.getElementsByName('paymentAt')[0].disabled = true
            } else {
                document.getElementById('paymentMethod').disabled = false
                // document.getElementById('bankAccount').disabled = false
                document.getElementsByName('ir_amount')[0].disabled = false
                document.getElementsByName('paymentAt')[0].disabled = false
            }
        }

        function selectPaymentMethod(value) {
            if (value.value === 'bank') {
                document.getElementById('bankAccount').disabled = false
            } else {
                document.getElementById('bankAccount').disabled = true
            }
        }

        function downloadContract(value) {
            document.getElementById("downloadContract").href = "https://api.applygermany.net/contract/" + value.value
            document.getElementById('downloadContract').style.display = ""
            $.ajax({
                url: '{{route('admin.getUserInfo')}}',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    'userId': value.value,
                },
                success: function (data) {
                    document.getElementsByName('balance')[0].value = data.balance;
                    document.getElementsByName('discount_code_inviter')[0].value = data.userId;
                    document.getElementsByName('extra_universities')[0].value = data.extraUniversities;
                    document.getElementsByName('extra_universities_total_price')[0].value = data.extraUniversitiesTotalPrice;
                }
            });
        }

        $(document).on('click', '#checkCode', function () {
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            $.ajax({
                url: '{{route('admin.checkCode')}}',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    'type': document.getElementsByName('invoice_type')[0].value,
                    'userId': document.getElementsByName('user')[0].value,
                    'code': document.getElementsByName('discount_code')[0].value,
                },
                success: function (data) {
                    dis.html('بررسی کد');
                    if (data.status === 1) {
                        document.getElementsByName('discount_amount')[0].value = parseFloat(document.getElementsByName('discount_amount')[0].value) + parseFloat(data.amount);
                        document.getElementsByName('discount_type')[0].value = data.type;

                        Lobibox.notify('success', {
                            title: " عملیات موفق : ",
                            msg: "کد تخفیف ثبت شد",
                            icon: 'fa fa-success',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });

                    } else {
                        document.getElementsByName('discount_code')[0].value = ''
                        document.getElementsByName('discount_amount')[0].value = '';
                        document.getElementsByName('discount_type')[0].value = '';
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "کد تخفیف معتبر نمی باشد",
                            icon: 'fa fa-warning',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                }
            });
        });

        $(document).on('click', '#checkCodeInviter', function () {
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            $.ajax({
                url: '{{route('admin.checkCodeInviter')}}',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    'type': document.getElementsByName('invoice_type')[0].value,
                    'userId': document.getElementsByName('user')[0].value,
                    'code': document.getElementsByName('discount_code_inviter')[0].value,
                },
                success: function (data) {
                    dis.html('بررسی کد');
                    if (data.status === 1) {

                        document.getElementsByName('discount_amount')[0].value = parseFloat(document.getElementsByName('discount_amount')[0].value) + parseFloat(data.amount);
                        document.getElementsByName('discount_type')[0].value = data.type;

                        Lobibox.notify('success', {
                            title: " عملیات موفق : ",
                            msg: "کد تخفیف ثبت شد",
                            icon: 'fa fa-success',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });

                    } else {
                        document.getElementsByName('discount_code_inviter')[0].value = ''
                        document.getElementsByName('discount_amount')[0].value = '';
                        document.getElementsByName('discount_type')[0].value = '';
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "کد تخفیف معتبر نمی باشد",
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
