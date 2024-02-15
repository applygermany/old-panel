<table class="table table-hover">
    <thead>
        <tr class="text-center">
            <th>نام و نام خانوادگی</th>
            <th>مبلغ نهایی</th>
            <th>تخفیف</th>
            <th>کد پیگیری</th>
            <th>تاریخ </th>
            <th>وضعیت</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
        <tr class="text-center">
            <td>{{ $transaction->user->firstname }} {{ $transaction->user->lastname }}</td>
            <td>{{number_format($transaction->amount - $transaction->discount)}}</td>
            <td>{{ number_format($transaction->discount)}}</td>
            <td>{{$transaction->transaction_code}}</td>
            <td><?php
                $date = explode(' ', $transaction->updated_at);
                $date = explode('-', $date[0]);
                $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                echo $date . " " . explode(' ', $transaction->updated_at)[1];
                ?></td>
            <td>
                @if($transaction->status == 1)
                <span class="">پرداخت موفق</span>
                @elseif($transaction->status == 0)
                <span class="">پرداخت نشده</span>
                @elseif($transaction->status == 2)
                <span class="">پرداخت لغو شده</span>
                @else
                <span class="">پرداخت نا موفق</span>
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>
</table>