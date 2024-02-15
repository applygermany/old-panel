@extends('admin.motivations.preview.master-layout')
@section('content')

    @include('admin.motivations.preview.partials.aside', ['motivation'=>$motivation])

    <main class="bg-white p-6 lg:py-8 lg:px-10 rounded-3xl dark:bg-zinc-900/90" style="width: 100% !important;">
        <div class="mb-10">
            <div class="flex items-center text-slate-900 dark:text-slate-200 mb-2">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <h2 class="mx-2 text-2xl font-bold">
                    بیوگرافی
                </h2>
            </div>
            <p>
                {!! nl2br($motivation->about) !!}
            </p>
        </div>

        <div class="mb-10">
            <div class="flex items-center text-slate-900 dark:text-slate-200 mb-2">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <h2 class="mx-2 text-2xl font-bold">

                    درباره سوابق تحصیلی و کاری
                </h2>
            </div>
            <p>
                {!! nl2br($motivation->resume) !!}
            </p>
        </div>

        <div class="mb-10">
            <div class="flex items-center text-slate-900 dark:text-slate-200 mb-2">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <h2 class="mx-2 text-2xl font-bold">
                    به چه علت آلمان را برای تحصیل انتخاب کرده اید؟
                </h2>
            </div>
            <p>
                {!! nl2br($motivation->why_germany) !!}
            </p>
        </div>

        <div class="mb-10">
            <div class="flex items-center text-slate-900 dark:text-slate-200 mb-2">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <h2 class="mx-2 text-2xl font-bold">
                    برنامه پس از فارغ التحصیلی
                </h2>
            </div>
            <p>
                {!! nl2br($motivation->after_graduation) !!}
            </p>
        </div>

        <div class="mb-10">
            <div class="flex items-center text-slate-900 dark:text-slate-200 mb-2">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <h2 class="mx-2 text-2xl font-bold">
                    توضیحات اضافی
                </h2>
            </div>
            <p>
                {!! nl2br($motivation->extra_text) !!}
            </p>
        </div>

        <div class="mb-10">
            <div class="flex items-center mb-4 text-slate-900 dark:text-slate-200">
                <svg class="w-6 h-6" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                    <line x1="6" y1="1" x2="6" y2="4"/>
                    <line x1="10" y1="1" x2="10" y2="4"/>
                    <line x1="14" y1="1" x2="14" y2="4"/>
                </svg>
                <h2 class="mx-2 text-2xl font-bold">
                    دانشگاه ها
                </h2>
            </div>
            <ul class="exp-menu-container">
                @foreach($motivation->universities()->get() as $university)
                    <li class="exp-menu-item">
                        <div class="relative top-[-0.35rem] rtl:pr-4 ltr:pl-4">
                            <div class="text-xl font-semibold text-slate-700 mb-2 dark:text-slate-400">
                                {{ $university->name }}
                            </div>
                            <table class="w-full  mb-2">
                                <tr class="flex justify-between mb-4">
                                    <td>مقطع تحصیلی</td>
                                    <td>رشته تحصیلی</td>
                                    <td>زبان تحصیلی</td>
                                </tr>
                                <tr class="flex justify-between mb-4">
                                    <td>{{$university->grade}}</td>
                                    <td>{{$university->field}}</td>
                                    <td>{{$university->language}}</td>
                                </tr>
                            </table>
                            <br>
                            <div class="relative top-[-0.35rem] rtl:pr-4 ltr:pl-4">
                                <div class="text-xl font-semibold text-slate-700 mb-2 dark:text-slate-400">
                                    علت انتخاب این دانشگاه
                                </div>
                            </div>
                            <p> {{$university->text1}}</p>
                            <br/>
                            <div class="relative top-[-0.35rem] rtl:pr-4 ltr:pl-4">
                                <div class="text-xl font-semibold text-slate-700 mb-2 dark:text-slate-400">
                                    علت انتخاب این رشته
                                </div>
                            </div>
                            <p> {{$university->text2}}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

    </main>

@endsection