<aside class="bg-secondary rounded-3xl p-8 dark:bg-gray-800">
    <div class=" flex flex-col justify-center items-center sticky top-8 lg:w-[260px]">
        <img src="{{url('motivation/images/profile.png')}}" alt="a faceless man" class="w-full max-w-[250px] mb-6"/>
        <h1 class="text-2xl text-slate-200 font-medium mb-2">{{$motivation->name}} {{$motivation->family}}</h1>
        <table class="w-full text-slate-300 text-sm max-w-[250px] mb-2">
            <tr class="flex justify-between mb-4">
                <td>شماره تماس</td>
                <td>{{$motivation->phone}}</td>
            </tr>
            <tr class="flex justify-between mb-4">
                <td>ایمیل</td>
                <td>{{$motivation->email}}</td>
            </tr>
            <tr class="border-1 border-t border-slate-700 dark:border-slate-600 w-1/2 block mx-auto mb-4"></tr>
            <tr class="flex justify-between mb-4">
                <td>تاریخ تولد</td>
                <td>{{$motivation->birth_date}}</td>
            </tr>
            <tr class="flex justify-between mb-4">
                <td>محل تولد</td>
                <td>{{$motivation->birth_place}}</td>
            </tr>
            <tr class="flex justify-between mb-4">
                <td>محل تولدآدرس</td>
                <td>{{$motivation->address}}</td>
            </tr>
            <tr class="border-1 border-t border-slate-700 dark:border-slate-600 w-1/2 block mx-auto mb-4"></tr>
            <tr class="flex justify-between mb-4">
                <td>ارائه به</td>
                <td>{{$motivation->to === 1 ? 'سفارت' : 'دانشگاه'}}</td>
            </tr>
            @if($motivation->to === 1)
                <tr class="flex justify-between mb-4">
                    <td>ارائه به</td>
                    <td>{{$motivation->country === 1 ? 'ایران' : 'سایر گشور ها'}}</td>
                </tr>
            @endif
        </table>
        @if($motivation->url_uploaded_from_user)
            @foreach($motivation->url_uploaded_from_user as $key => $item)
                <a class="btn-cta" href={{$item}}>دانلود فایل {{$key+1}}</a>
            @endforeach
        @endif
        <hr/>
        <a class="btn-cta" href={{route('admin.downloadMotivationPreview', ['id' => $motivation->id])}}>دانلود خروجی PDF</a>

    </div>
</aside>