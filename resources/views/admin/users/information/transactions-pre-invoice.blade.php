<div class="table-responsive">
    <table class="table align-middle table-row-dashed gy-5 dataTable no-footer">
        <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
        <tr class="text-center text-gray-400 gs-0" role="row">
            <th>#</th>
            <th>مبلغ</th>
            <th>کد</th>
            <th>نوع تراکنش</th>
            <th>تاریخ</th>
            <th>دانلود</th>
        </tr>
        </thead>
        <tbody class="fs-6 fw-bold text-gray-600">
        @foreach($transactions->where('invoice_title', 'pre-invoice') as $transaction)
            <tr class="text-center">
                <td>{{ $transaction->id }}</td>
                <td>{{ number_format($transaction->final_amount) }} <small>{{$transaction->currency_title}}</small></td>
                <td>{{ $transaction->code }}</td>
                <td>{{ $transaction->invoice }}</td>
                <td>
                    <?php
                    $date = explode(' ',$transaction->created_at);
                    $date = explode('-',$date[0]);
                    $date = \App\Providers\JDF::gregorian_to_jalali($date[0],$date[1],$date[2],'/');
                    echo $date;
                    ?>
                </td>
                <td>
                    <a class="btn btn-info btn-sm" target="_blank"
                       href="{{ route('admin.generateInvoice',['id'=>$transaction->id]) }}">دانلود</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>