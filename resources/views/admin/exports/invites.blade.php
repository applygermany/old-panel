<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>#</th>
            <th> معرف</th>
            <th>نام و نام خانوادگی</th>
            <th>موبایل/ایمیل</th>
            <th>تاریخ عضویت</th>
            <th>تاریخ ویژه</th>
            <th>آخرین وضعیت</th>
            {{-- <th>تعداد زیرمجموعه</th> --}}
        </tr>
        </thead>
        <tbody>
        @foreach($invites as $user)
            <tr class="text-center">
                <td>{{ $user->id }}</td>
                <td>{{ $user->invite_user_info->firstname }} {{ $user->invite_user_info->lastname }}
                    <br/>{{ $user->user_id }}</td>
                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                <td>{{ $user->mobile ? $user->mobile : $user->email }}</td>
                <td>
                    @php
                        $date = explode(' ',$user->created_at);
                        $date = explode('-',$date[0]);
                        $date = \App\Providers\JDF::gregorian_to_jalali($date[0],$date[1],$date[2],'/');
                        echo $date;
                    @endphp
                </td>
                <td>
                    @php
                        if($user->acceptances->count() > 0){
                            $date = explode(' ',$user->acceptances[0]->created_at);
                            $date = explode('-',$date[0]);
                            $date = \App\Providers\JDF::gregorian_to_jalali($date[0],$date[1],$date[2],'/');
                            echo $date;
                       }else{
                            echo "---";
                       }
                    @endphp
                </td>
                <td>{{ $user->user_last_status }}</td>
                {{-- <td>{{ count($user->invites) }}</td> --}}

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
