<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            @if($type === 'receipt' || $type === 'confirmed')
                <th>شماره رسید</th>
            @endif
            <th>کاربر</th>
            <th>کد قرارداد</th>
            <th>حساب بانکی</th>
            <th>عنوان فاکتور</th>
            <th>مبلغ (ریال)</th>
            <th>مبلغ</th>
            <th>ایجاد</th>
            @if($type === 'pre-invoice')
                <th>سررسید</th>
            @else
                <th>تاریخ پرداخت</th>
            @endif
            <th>عملیات
            <th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr class="text-center">
                <td>{{ $invoice->id }}</td>
                @if($type === 'receipt' || $type === 'confirmed')
                    <td>
                        {{ $invoice->code }}

                        <br/>
                        {{$invoice->payment_method_title}}
                    </td>
                @endif
                <td>
                    <a href="{{ route('admin.userProfile',['id'=>$invoice->user_id]) }}">{{ $invoice->user->firstname }} {{ $invoice->user->lastname }}</a>
                </td>
                <td>
                    {{$invoice->user->contract_code}}
                </td>
                <td>
                    @if($invoice['bankRelation'] and $invoice['payment_method'] =='bank')
                        {{$invoice['bankRelation']['account_name']}}
                        <br>
                        {{$invoice['bankRelation']['bank_name']}}-{{$invoice['bankRelation']['shaba_number']}}
                    @else
                        @if($invoice['payment_method'] =='bank')
                            ثبت نشده است
                        @else
                            پرداخت نقدی
                        @endif
                    @endif
                </td>
                <td>{{ $invoice->status === 'published'? $invoice->invoice_type_title : 'تایید نشده' }}
                    <br/> {{$invoice->invoice}} </td>
                <td>{{ $invoice->ir_amount}}</td>
                <td>{{ $invoice->euro_amount}} <br/> @if($invoice->euro_amount!== '0')
                        {{$invoice->currency_title}}
                    @endif </td>
                <td>{{$invoice->jalali_date_confirmed}}</td>
                <td dir="ltr">
                    {{$invoice->status === 'published'? ($invoice->invoice_title === 'pre-invoice' && $invoice->payment_at === null ? $invoice->different_days : $invoice->payment_at) : '---'}} </td>
                <td>
                    <a class="btn btn-info btn-sm" target="_blank"
                       href="{{ route('admin.generateInvoice',['id'=>$invoice->id]) }}">دانلود</a>

                    <a class="btn btn-primary btn-sm editInvoice"
                       data-href="{{ route('admin.editInvoice',['id'=>$invoice->id]) }}">ویرایش</a>

                    <a class="btn btn-success btn-sm"
                       href="{{ route('admin.acceptInvoiceManager',['id'=>$invoice->id]) }}">تایید مدیر
                        مالی </a>

                    @if($invoice->invoice_title === 'pre-invoice')
                        <a class="btn btn-warning btn-sm delete"
                           href="{{ route('admin.declineInvoice',['id'=>$invoice->id]) }}">رد فاکتور</a>
                    @endif

                    <a class="btn btn-danger btn-sm delete"
                       href="{{ route('admin.deleteInvoice',['id'=>$invoice->id]) }}">حذف</a>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $invoices->links() !!}
