
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label">کاربر</label>
            <input type="text" disabled value="{{ $invoice->user->firstname }} {{ $invoice->user->lastname }}
                        - 0{{$invoice->user->mobile}} - {{$invoice->user->email}}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" >نوع فاکتور</label>
            <input type="text" disabled value="{{ $invoice->invoice_type_title }}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" >فاکتور برای</label>
            <input type="text" disabled value="{{ $invoice->invoice }}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label">نوع تسویه</label>
            <input type="text" disabled value="{{ $invoice->payment_method_title }}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label">حساب بانکی</label>
            <input type="text" disabled value="{{ $invoice->bank->card_number }}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label">مبلغ به ریال</label>
            <input type="text" disabled value="{{ $invoice->ir_amount }}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" >مبلغ به یورو</label>
            <input type="text" disabled value="{{ $invoice->euro_amount }}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" >نوع تخفیف</label>
            <input type="text" disabled value="{{ $invoice->discount_type === 'percent' ? 'درصدی' : 'ثابت' }}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" >میزان تخفیف</label>
            <input type="text" disabled value="{{ $invoice->discount_amount }}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" >شرح تخفیف</label>
            <input type="text" disabled value="{{ $invoice->discount_description }}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" >توضیحات فاکتور</label>
            <input type="text" disabled value="{{ $invoice->invoice_description }}" class="form-control form-control-sm" >
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <a class="btn btn-info w-100" href="https://api.applygermany.net/contract/{{$invoice->user->id}}" target="_blank" >دانلود قرارداد</a>
    </div>
</div>