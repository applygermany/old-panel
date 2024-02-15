
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css"/>
<style>
    .pwt-btn-calendar {
        display: none !important;
    }
</style>

@csrf
<input type="hidden" name="id" value="{{$invoice->id}}">
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" for="user">کاربر</label>
            <input type="text" name="user" value="{{ $invoice->user->firstname }} {{ $invoice->user->lastname }}
                        - 0{{$invoice->user->mobile}} - {{$invoice->user->email}}" class="form-control form-control-sm" disabled>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" for="invoiceType">فاکتور برای</label>
            <select name="invoice_type" id="paymentType" disabled
                    class="form-control form-select-sm" data-control="select2">
                <option value="" selected>انتخاب کنید</option>
                <option {{$invoice->invoice_type === 'resume' ? 'selected' : ''}} value="resume">رزومه و
                    انگیزه نامه
                </option>
                <option {{$invoice->invoice_type === 'final' ? 'selected' : ''}} value="final">تسویه نهایی
                </option>
                <option {{$invoice->invoice_type === 'tel-support' ? 'selected' : ''}} value="tel-support">مشاوره تلفنی
                </option>
                <option {{$invoice->invoice_type === 'other' ? 'selected' : ''}} value="other">پیش پرداخت</option>
            </select>

        </div> @if ($errors->has('invoice_type'))
            <span class="invalid-feedback"><strong>{{ $errors->first('invoice_type') }}</strong></span>
        @endif
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" for="paymentMethod">نوع تسویه</label>
            <select name="payment_method" id="paymentMethod" onchange="selectPaymentMethod(this)"
                    class="form-control form-select-sm" data-control="select2">
                <option value="" selected>انتخاب کنید</option>
                <option {{$invoice->payment_method === 'cash' ? 'selected' : ''}} value="cash">پرداخت نقدی
                </option>
                <option {{$invoice->payment_method === 'bank' ? 'selected' : ''}} value="bank">واریز به
                    حساب
                </option>
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
                    <option {{$invoice->bank_id === $bank->id ? 'selected' : ''}}
                    value="{{$bank->id}}">{{$bank->bank_name}}
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
            <input type="text" name="ir_amount" value="{{$invoice->ir_amount}}"
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
            <input type="text" name="euro_amount" value="{{$invoice->euro_amount}}"
                   class="form-control form-control-sm @if($errors->has('euro_amount')) is-invalid @endif"
                   id="euro_amount" disabled placeholder="مبلغ فاکتور به یورو">
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
                <option {{$invoice->currency === 'euro' ? 'selected' : ''}} value="euro">یورو
                </option>
                <option {{$invoice->currency === 'dollar' ? 'selected' : ''}} value="dollar">دلار
                </option>
            </select>
        </div> @if ($errors->has('currency'))
            <span class="invalid-feedback"><strong>{{ $errors->first('currency') }}</strong></span>
        @endif
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" for="invoice_description">توضیحات فاکتور</label>
            <input type="text" name="invoice_description" value="{{$invoice->invoice_description}}"
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
            <input type="text" name="paymentAt" value="{{$invoice->payment_at}}" required
                   class="form-control form-control-sm date example1" id="paymentAt" autocomplete="off">
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <a class="btn btn-info w-100" href="https://api.applygermany.net/contract/{{$invoice->user->id}}" target="_blank" >دانلود قرارداد</a>
    </div>

</div>


<script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>
<script>
    $(".date").pDatepicker({
        format: "YYYY/MM/DD",
        onSelect: "year",
        initialValue: false
    });

    function selectPaymentMethod(value) {
        if (value.value === 'cash') {
            document.getElementById('bankAccount').disabled = true
        } else {
            document.getElementById('bankAccount').disabled = false
        }
    }
</script>