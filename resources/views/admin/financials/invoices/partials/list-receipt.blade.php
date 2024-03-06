<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>شماره رسید</th>
            <th>کاربر</th>
            <th>کد قرارداد</th>
            <th>حساب بانکی</th>
            <th>عنوان فاکتور</th>
            <th>مبلغ (ریال)</th>
            <th>مبلغ</th>
            <th>ایجاد</th>
            <th>تاریخ پرداخت</th>
            <th>عملیات
            <th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr class="text-center">
                <td>{{ $invoice->id }}</td>
                <td>
                    {{ $invoice->code }}

                    <br/>
                    {{$invoice->payment_method_title}}
                </td>
                <td>
                    <a href="{{ route('admin.userProfile',['id'=>$invoice->user_id]) }}">{{ $invoice->user->firstname }} {{ $invoice->user->lastname }}</a>
                </td>
                <td>
                    {{$invoice->user->contract_code}}
                </td>
                <td>
                    {{$invoice->status === 'published'? ($invoice->invoice_title === 'receipt' ? ($invoice->payment_method === 'bank' ? $invoice->bank->account_name : '---') : '---') : '---'}}
                    <br/>
                    {{$invoice->status === 'published'? ($invoice->invoice_title === 'receipt' ? ($invoice->payment_method === 'bank' ? $invoice->bank->shaba_number : '') : '') : ''}}
                </td>
                <td>{{ $invoice->status === 'published'? $invoice->invoice_type_title : 'تایید نشده' }}
                    <br/> {{$invoice->invoice}} </td>
                <td>{{ $invoice->ir_amount}}</td>
                <td>{{ $invoice->final_amount}} <br/> @if($invoice->final_amount!== '0')
                        {{$invoice->currency_title}}
                    @endif </td>
                <td>{{$invoice->jalali_date_confirmed}}</td>
                <td dir="ltr">
                    {{$invoice->payment_at}}
                </td>
                <td>
                    <a class="btn btn-info btn-sm" target="_blank"
                       href="{{ route('admin.generateInvoice',['id'=>$invoice->id,'hash'=>$token->generated_code]) }}">دانلود</a>

                    <a class="btn btn-warning btn-sm showInvoice"
                       data-href="{{ route('admin.showInvoice',['id'=>$invoice->id]) }}">مشاهده</a>

                    @if((auth()->user()->admin_permissions->financial_confirm ?? 0) != 0)
                        <a class="btn btn-primary btn-sm editInvoice"
                           data-href="{{ route('admin.editInvoice',['id'=>$invoice->id]) }}">ویرایش</a>
                        <a class="btn btn-danger btn-sm delete"
                           href="{{ route('admin.deleteInvoice',['id'=>$invoice->id]) }}">حذف</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $invoices->links() !!}
