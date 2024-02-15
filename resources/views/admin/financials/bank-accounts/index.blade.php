@extends('admin.lyout')

@section('title')
    پنل مدیریت - حساب های بانکی
@endsection

@section('css')
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
                        <li class="breadcrumb-item text-dark">حساب های بانکی</li>
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
                                       data-bs-toggle="collapse" data-bs-target="#collapseNewOff" aria-expanded="false"
                                       aria-controls="collapseExample">
                                        حساب بانکی جدید
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body collapse py-3" id="collapseNewOff">
                                <form action="{{ route('admin.saveBankAccount') }}" method="post" class="row">
                                    @csrf
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="user">وضعیت</label>
                                            <select name="status" id="status" class="form-control form-select-sm"
                                                    data-control="select2">
                                               <option value="publish">فعال</option>
                                               <option value="drafted">غیر فعال</option>
                                            </select>

                                        </div> @if ($errors->has('status'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('status') }}</strong></span>
                                        @endif
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="bank_name">نام بانک</label>
                                            <input type="text" name="bank_name"
                                                   class="form-control form-control-sm @if($errors->has('bank_name')) is-invalid @endif"
                                                   id="bank_name" placeholder="نام بانک">
                                            @if ($errors->has('bank_name'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('bank_name') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="bank_name">نام صاحب حساب</label>
                                            <input type="text" name="account_name"
                                                   class="form-control form-control-sm @if($errors->has('account_name')) is-invalid @endif"
                                                   id="account_name" placeholder="نام صاحب حساب">
                                            @if ($errors->has('account_name'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('account_name') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="card_number">شماره کارت</label>
                                            <input type="number" name="card_number"
                                                   class="form-control form-control-sm @if($errors->has('card_number')) is-invalid @endif"
                                                   id="card_number" placeholder="شماره کارت">
                                            @if ($errors->has('card_number'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('card_number') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="account_number">شماره حساب</label>
                                            <input type="text" name="account_number"
                                                   class="form-control form-control-sm @if($errors->has('account_number')) is-invalid @endif"
                                                   id="account_number" placeholder="شماره حساب">
                                            @if ($errors->has('account_number'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('account_number') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="account_number">شماره شبا</label>
                                            <input type="text" name="shaba_number"
                                                   class="form-control form-control-sm @if($errors->has('shaba_number')) is-invalid @endif"
                                                   id="shaba_number" placeholder="شماره شبا">
                                            @if ($errors->has('shaba_number'))
                                                <span class="invalid-feedback"><strong>{{ $errors->first('shaba_number') }}</strong></span>
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
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست حساب ها</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    <div class="table-responsive userTable">
                                        <table class="table">
                                            <thead class="table-light">
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>نام بانک</th>
                                                <th>نام صاحب حساب</th>
                                                <th>شماره کارت</th>
                                                <th>شماره حساب</th>
                                                <th>شماره شبا</th>
                                                <th>وضعیت</th>
                                                <th>عملیات<th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($banks as $bankAccount)
                                                <tr class="text-center">
                                                    <td>{{ $bankAccount->id }}</td>
                                                    <td>{{ $bankAccount->bank_name }}</td>
                                                    <td>{{ $bankAccount->account_name }}</td>
                                                    <td>{{ $bankAccount->card_number }}</td>
                                                    <td>{{ $bankAccount->account_number }}</td>
                                                    <td>{{ $bankAccount->shaba_number }}</td>
                                                    <td>{{ $bankAccount->status_title }}</td>
                                                    <td>
                                                        <a class="btn btn-success btn-sm edit"
                                                           data-href="{{ route('admin.editBankAccount',['id'=>$bankAccount->id]) }}">ویرایش</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="editBankAccount">
        <div class="modal-dialog modal-l">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش</h5>
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
    <script>

        $(document).on('click', '.edit', function () {
            var url = $(this).data('href');
            $.ajax({
                url: url,
                success: function (data) {
                    $('#editForm').html(data);
                    var editBankAccount = new bootstrap.Modal(document.getElementById('editBankAccount'), {keyboard: false});
                    editBankAccount.show();
                }
            });
        });

        $(document).on('click', '#updateFactor', function () {
            var invalidFeedBacks = $(".invalid-feedback").map(function () {
                this.remove();
            }).get();
            $(".is-invalid").removeClass("is-invalid");
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.updateBankAccount') }}',
                type: "POST",
                data: new FormData($('#editForm')[0]),
                processData: false,
                contentType: false,
                success: function (data) {
                    dis.html('بروزرسانی');
                    if (data == 1) {
                        Lobibox.notify('success', {
                            title: " عملیات موفق : ",
                            msg: "اطلاعات با موفقیت ویرایش شد",
                            icon: 'fa fa-success',
                            position: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });

                        window.location.redirect()
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
    </script>
@endsection
