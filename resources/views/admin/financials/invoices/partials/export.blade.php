<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            @if($invoices && $invoices[0]->invoice_title === 'receipt')
                <th>شماره رسید</th>
            @endif
            <th>کاربر</th>
            <th>کد ملی</th>
            <th>ایمیل</th>
            <th>کد قرارداد</th>
            <th>عنوان فاکتور</th>
            <th>نوع فاکتور</th>
            <th>دانشگاه اضافه</th>
            <th>مبلغ</th>
            <th>نوع ارز</th>
            <th>مبلغ ریال</th>
            <th>تخفیف</th>
            <th>پرداخت نهایی</th>
            <th>ایجاد</th>
            <th>تایید</th>
            <th>نوع پرداخت</th>
            <th>حساب بانکی</th>
            <th>نام بانک</th>
            <th>صاحب حساب بانکی</th>
            @if($invoices && $invoices[0]->invoice_title === 'pre-invoice')
                <th>سررسید</th>
            @else
                <th>تاریخ پرداخت</th>
            @endif
            <th>توضیحات تخفیف</th>
            <th>توضیحات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr class="text-center">
                <td>{{ $invoice->id }}</td>
                @if($invoices && $invoices[0]->invoice_title === 'receipt')
                    <td>{{ $invoice->code }}</td>
                @endif
                <td>
                    <a href="{{ route('admin.userProfile',['id'=>$invoice->user_id]) }}">{{ $invoice->user->firstname }} {{ $invoice->user->lastname }}</a>
                </td>
                <td>{{$invoice->user->codemelli}}</td>
                <td>{{$invoice->user->email}}</td>
                <td>{{$invoice->user->contract_code}}</td>
                <td>{{$invoice->invoice}} </td>
                <td>{{ $invoice->invoice_type_title }} </td>
                <td>
                    @if($invoice->extra_college==0)
                        ---
                     @else
                        {{ $invoice->extra_college }}
                    @endif
                </td>
                <td>{{ $invoice->euro_amount}}</td>
                <td>{{ $invoice->currency_title}}</td>
                <td>{{ $invoice->ir_amount}}</td>
                <td>{{ $invoice->discount_amount}}</td>
                <td>{{ $invoice->final_amount}}</td>
                <td>{{$invoice->jalali_date}}</td>
                <td>{{$invoice->updated_jalali}}</td>
                <td>{{$invoice->payment_method_title}}</td>
                <td>{{$invoice->payment_method !== 'cash' ? ($invoice->bank->card_number === '0' ?
($invoice->bank->shaba_number === '0' ? $invoice->bank->card_number : $invoice->bank->shaba_number) : '') : ''}}</td>
                <td>
                    @if($invoice['bankRelation'])
                        {{$invoice['bankRelation']['bank_name']}}
                    @else
                        @if($invoice['payment_method'] =='bank')
                            ثبت نشده است
                        @else
                            پرداخت نقدی
                        @endif
                    @endif
                </td>
                <td>
                    @if($invoice['bankRelation'])
                        {{$invoice['bankRelation']['account_name']}}
                    @else
                        @if($invoice['payment_method'] =='bank')
                            ثبت نشده است
                        @else
                            پرداخت نقدی
                        @endif
                    @endif
                </td>

                <td> {{$invoice->invoice_title === 'pre-invoice' ? $invoice->different_days : $invoice->payment_at}} </td>
                <td>{{$invoice->discount_description}}</td>
                <td>{{$invoice->invoice_description}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
