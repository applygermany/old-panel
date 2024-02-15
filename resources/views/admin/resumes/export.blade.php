<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
            <tr class="text-center">
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>زبان</th>
                <th>تاریخ تولد</th>
                <th>محل تولد </th>
                <th>شماره تماس</th>
                <th>ایمیل</th>
                <th>آدرس</th>
                <th>شبکه های اجتماعی</th>
            
            </tr>
        </thead>
        <tbody>

            <tr>
                                    <td class="text-gray-600">{{ $resume->name }}</td>
                                    <td class="text-gray-600">{{ $resume->family }}</td>
                                    <td class="text-gray-600">{{ $resume->language }}</td>
                                    <td class="text-gray-600">{{ $resume->birth_date }}</td>
                                    <td class="text-gray-600">{{ $resume->birth_place }}</td>
                                    <td class="text-gray-600">{{ $resume->phone }}</td>
                                    <td class="text-gray-600">{{ $resume->email }}</td>
                                    <td class="text-gray-600">{{ $resume->address }}</td>
                                    <td class="text-gray-600">{{ $resume->socialmedia_links }}</td>
            </tr>

        </tbody>
    </table>
