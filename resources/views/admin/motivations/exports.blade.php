<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>نام</th>
            <th>نام خانوادگی</th>
            <th>تاریخ تولد</th>
            <th>محل تولد</th>
            <th>شماره تماس</th>
            <th>ایمیل</th>
            <th>آدرس</th>
            <th>برای</th>
            <th>کشور</th>
            <th>درباره</th>
            <th>رزومه</th>
            <th>چرا آلمان</th>
            <th>برنامه بعد از فارغ التحصیلی</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td class="text-gray-600">{{ $motivation->name }}</td>
            <td class="text-gray-600">{{ $motivation->family }}</td>
            <td class="text-gray-600">{{ $motivation->birth_date }}</td>
            <td class="text-gray-600">{{ $motivation->birth_place }}</td>
            <td class="text-gray-600">{{ $motivation->phone }}</td>
            <td class="text-gray-600">{{ $motivation->email }}</td>
            <td class="text-gray-600">{{ $motivation->address }}</td>
            <td>

                @if ($motivation->to == 1)
                    سفارت
                @else
                    دانشگاه
                @endif
            </td>
            <td class="col-6 mb-2">

                @if ($motivation->country == 1)
                    ایران
                @else
                    کشور های دیگر
                @endif
            </td>

            <td class="col-12 my-2">

                {{ $motivation->about }}
            </td>

            <td class="col-12 my-2">

                {{ $motivation->resume }}
            </td>

            <td class="col-12 my-2">

                {{ $motivation->why_germany }}
            </td>

            <td class="col-12 my-2">

                {{ $motivation->after_graduation }}
            </td>
        </tr>

        </tbody>
    </table>
</div>