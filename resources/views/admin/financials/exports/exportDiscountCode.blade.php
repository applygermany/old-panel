<div class="table-responsive userTable">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th>کاربر</th>
            <th>کد قرارداد</th>
            <th>نحوه پرداخت</th>
            <th>عنوان فاکتور</th>
            <th>مبلغ (ریال)</th>
            <th>مبلغ (یورو)</th>
            <th>ایجاد</th>
            <th>پرداخت</th>
            <th>سررسید</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr class="text-center">
                <td>{{ $invoice->id }}</td>
                <td>
                    {{ $invoice->user->firstname }} {{ $invoice->user->lastname }}
                    <br/>
                    {{$invoice->user->mobile}}
                    <br/>
                    {{$invoice->user->email}}
                </td>
                <td>{{$invoice->user->contract_code}}</td>
                <td>{{$invoice->status === 'published'? ($invoice->invoice_title === 'receipt' ? $invoice->payment_method_title : '---') : '---'}}</td>
                <td>{{$invoice->invoice}} </td>
                <td>{{ $invoice->ir_amount}}</td>
                <td>{{ $invoice->euro_amount}}</td>
                <td>{{$invoice->jalali_date_confirmed}}</td>
                <td>{{$invoice->payment_at}}</td>
                <td dir="ltr"> {{$invoice->status === 'published'? ($invoice->invoice_title === 'pre-invoice' ? $invoice->different_days : '---') : '---'}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
