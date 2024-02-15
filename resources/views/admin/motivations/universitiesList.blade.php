@foreach($motivation->universities()->get() as $university)
<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>نام دانشگاه</th>
            <th>مقطع</th>
            <th>رشته</th>
            <th>زبان تحصیل</th>
        </tr>
        </thead>
        <tbody>

            <tr class="text-center">
                <td>{{ $university->name }}</td>
                <td>{{ $university->grade }}</td>
                <td>{{ $university->field }}</td>
                <td>{{ $university->language }}</td>
            </tr>
            <tr>
                <td colspan="1">
                    علت انتخاب این دانشگاه
                </td>
                <td colspan="3">
                    {{$university->text1}}
                </td>
            </tr>
            <tr style="border-bottom: 1px black solid;margin-bottom: 1em">
                <td colspan="1">
                    علت انتخاب این رشته
                </td>
                <td colspan="3">
                    {{$university->text2}}
                </td>
            </tr>

        </tbody>
    </table>
</div>
@endforeach