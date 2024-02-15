@csrf
<input type="hidden" value="{{$bank->id}}" name="id">
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="form-group float-label">
            <label class="header-label" for="user">وضعیت</label>
            <select name="status" id="status" class="form-control form-select-sm"
                    data-control="select2">
                <option selected="{{$bank->status === 'publish' ? 'selected' : ''}}" value="publish">فعال</option>
                <option selected="{{$bank->status === 'drafted' ? 'selected' : ''}}" value="drafted">غیر فعال</option>
            </select>

        </div> @if ($errors->has('status'))
            <span class="invalid-feedback"><strong>{{ $errors->first('status') }}</strong></span>
        @endif
    </div>
    <div class="col-12 col-lg-6">
        <div class="form-group float-label">
            <label class="header-label" for="bank_name">نام بانک</label>
            <input type="text" name="bank_name"
                   class="form-control form-control-sm @if($errors->has('bank_name')) is-invalid @endif"
                   id="bank_name" value="{{$bank->bank_name}}" placeholder="نام بانک">
            @if ($errors->has('bank_name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('bank_name') }}</strong></span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-6">
        <div class="form-group float-label">
            <label class="header-label" for="bank_name">نام صاحب حساب</label>
            <input type="text" name="account_name" value="{{$bank->account_name}}"
                   class="form-control form-control-sm @if($errors->has('account_name')) is-invalid @endif"
                   id="account_name" placeholder="نام صاحب حساب">
            @if ($errors->has('account_name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('account_name') }}</strong></span>
            @endif
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="form-group float-label">
            <label class="header-label" for="card_number">شماره کارت</label>
            <input type="number" name="card_number" value="{{$bank->card_number}}"
                   class="form-control form-control-sm @if($errors->has('card_number')) is-invalid @endif"
                   id="card_number" placeholder="شماره کارت">
            @if ($errors->has('card_number'))
                <span class="invalid-feedback"><strong>{{ $errors->first('card_number') }}</strong></span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-6">
        <div class="form-group float-label">
            <label class="header-label" for="account_number">شماره حساب</label>
            <input type="text" name="account_number" value="{{$bank->account_number}}"
                   class="form-control form-control-sm @if($errors->has('account_number')) is-invalid @endif"
                   id="account_number" placeholder="شماره حساب">
            @if ($errors->has('account_number'))
                <span class="invalid-feedback"><strong>{{ $errors->first('account_number') }}</strong></span>
            @endif
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="form-group float-label">
            <label class="header-label" for="account_number">شماره شبا</label>
            <input type="text" name="shaba_number" value="{{$bank->shaba_number}}"
                   class="form-control form-control-sm @if($errors->has('shaba_number')) is-invalid @endif"
                   id="shaba_number" placeholder="شماره شبا">
            @if ($errors->has('shaba_number'))
                <span class="invalid-feedback"><strong>{{ $errors->first('shaba_number') }}</strong></span>
            @endif
        </div>
    </div>

</div>
