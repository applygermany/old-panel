<div class="table-responsive">
    <table class="table align-middle table-row-dashed gy-5 dataTable no-footer">
        <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
            <tr class="text-center text-gray-400 gs-0" role="row">
                <th>#</th>
                <th>نام</th>
                <th>موبایل/ایمیل</th>
                <th>وضعیت</th>
                <th>تاریخ ثبت</th>

            </tr>
        </thead>
        <tbody class="fs-6 fw-bold text-gray-600">
            @foreach ($invites as $invite)
                <tr class="text-center">
                    <td>{{ $invite->id }}</td>
                    <td>{{ $invite->firstname }} {{ $invite->lastname }}</td>
                    <td>{{ $invite->mobile ? $invite->mobile : $invite->email }}</td>

                    <td>
                        @if ($invite->invoices()->where('invoice_type', 'final')->first())
                            تسویه حساب
                        @elseif($invite->userUniversities()->where('status', 3)->first())
                            اخذ پذیرش
                        @elseif($invite->userUniversities()->first())
                            در دست اپلای
                        @elseif($invite->acceptances()->first())
                            درخواست اخذ پذیرش
                        @else
                            ثبت نام در پورتال
                        @endif
                    </td>
                    <td>
                        <?php
                        $date = explode(' ', $invite->created_at);
                        $date = explode('-', $date[0]);
                        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                        echo $date;
                        ?>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="row">
    {!! $invites->links() !!}
</div>
