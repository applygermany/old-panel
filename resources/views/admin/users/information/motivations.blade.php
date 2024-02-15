
<div class="table-responsive">
    <table class="table align-middle table-row-dashed gy-5 dataTable no-footer">
        <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
        <tr class="text-center text-gray-400 gs-0" role="row">
            <th>#</th>
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
        </tr>
        </thead>
        <tbody class="fs-6 fw-bold text-gray-600">
        @foreach($motivations as $motivation)
            <tr class="text-center">
                <td>{{ $motivation->id }}</td>
                <td>
                    @switch($motivation->status)
                        @case(1)
                            <span class="badge badge-warning">
                                                در انتظار بررسی
                    </span>
                            @break
                        @case(2)
                            <span class="badge badge-success">
                        آماده شده
                    </span>
                            @break
                        @case(3)
                            <span class="badge badge-primary">
                        ادیت از سمت ادمین
                    </span>
                            @break
                        @case(4)
                            <span class="badge badge-danger">
                        ادیت از سمت کاربر
                    </span>
                            @break
                        @case(5)
                            <span class="badge badge-success">
                            تایید پشتیبان/کارشناس
                       </span>
                            @break
                        @case(6)
                            <span class="badge badge-danger">
                            رد پشتیبان/کارشناس
                       </span>
                            @break
                        @case(7)
                            <span class="badge badge-info">
                            اپلود نگارنده
                       </span>
                            @break
                        @default
                    @endswitch
                </td>
                <td>
                        <?php
                        $date = explode(' ',$motivation->created_at);
                        $date = explode('-',$date[0]);
                        $date = \App\Providers\JDF::gregorian_to_jalali($date[0],$date[1],$date[2],'/');
                        echo $date;
                        ?>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>