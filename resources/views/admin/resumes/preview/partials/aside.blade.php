<aside class="bg-secondary rounded-3xl p-8 dark:bg-gray-800">
    <div class=" flex flex-col justify-center items-center sticky top-8 lg:w-[260px]">
        <!-- Profile Picture / تصویر پروفایل -->
        <img src="{{ route('imageResume',['id'=>$resume->id,'ua'=>strtotime($resume->user->updated_at)]) }}" alt="a faceless man" class="w-full max-w-[250px] mb-6"/>
        <!-- Fullname / نام کامل -->
        <h1 class="text-2xl text-slate-200 font-medium mb-2">{{$resume->name}} {{$resume->family}}</h1>
        <!-- Personal Information / اطاعات شخصی -->
        <table class="w-full text-slate-300 text-sm max-w-[250px] mb-2">
            <tr class="flex justify-between mb-4">
                <td>شماره سفارش</td>
                <td>{{$resume->id}}</td>
            </tr>
            <tr class="flex justify-between mb-4">
                <td>نام قالب</td>
                <td>{{$resume->theme}}</td>
            </tr>
            <tr class="flex justify-between mb-4">
                <td>کد رنگ</td>
                <td>{{$resume->color}}</td>
            </tr>
            <tr class="border-1 border-t border-slate-700 dark:border-slate-600 w-1/2 block mx-auto mb-4"></tr>
            <tr class="flex justify-between mb-4">
                <td>شماره تماس</td>
                <td>{{$resume->phone}}</td>
            </tr>
            <tr class="flex justify-between mb-4">
                <td>ایمیل</td>
                <td>{{$resume->email}}</td>
            </tr>
            <tr class="flex justify-between mb-4">
                <td>زبان</td>
                <td>{{$resume->language}}</td>
            </tr>
            <tr class="border-1 border-t border-slate-700 dark:border-slate-600 w-1/2 block mx-auto mb-4"></tr>
            <tr class="flex justify-between mb-4">
                <td>تاریخ تولد</td>
                <td>{{$resume->birth_date}}</td>
            </tr>
            <tr class="flex justify-between mb-4">
                <td>محل تولد</td>
                <td>{{$resume->birth_place}}</td>
            </tr>
            <tr class="flex justify-between mb-4">
                <td>محل تولدآدرس</td>
                <td>{{$resume->address}}</td>
            </tr>
            <tr class="border-1 border-t border-slate-700 dark:border-slate-600 w-1/2 block mx-auto mb-4"></tr>
            <tr class="flex justify-between mb-4">
                <td>شبکه های اجتماعی</td>
                <td>{{$resume->socialmedia_links}}</td>
            </tr>
        </table>

        <hr/>
        <a class="btn-cta" href={{route('admin.downloadResumePreview', ['id' => $resume->id])}}>دانلود خروجی PDF</a>

    </div>
</aside>