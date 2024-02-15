@extends('admin.resumes.preview.master-layout')
@section('content')

    @include('admin.resumes.preview.partials.aside', ['resume'=>$resume])

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
                    توضیحات بیشتر
                </h2>
            </div>
            <p>
                {!! nl2br($resume->text) !!}
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
                    سوابق تحصیلی
                </h2>
            </div>
            <ul class="exp-menu-container">
                @foreach($resume->educationRecords()->get() as $educationRecord)
                    <li class="exp-menu-item">
                        <div class="relative top-[-0.35rem] rtl:pr-4 ltr:pl-4">
                            <div class="text-xl font-semibold text-slate-700 mb-2 dark:text-slate-400">
                                {{ $educationRecord->grade }}
                            </div>
                            <div class="text-sm text-slate-500">{{ $educationRecord->from_date_year }}
                                - {{$educationRecord->from_date_month}} | {{ $educationRecord->to_date_year }}
                                - {{$educationRecord->to_date_month}}</div>
                            <table class="w-full mb-2">
                                <tr class="flex justify-between mb-4">
                                    <td>رشته</td>
                                    <td>{{ $educationRecord->field }}</td>
                                </tr>
                                <tr class="flex justify-between mb-4">
                                    <td>معدل</td>
                                    <td>{{ $educationRecord->grade_score }}</td>
                                </tr>
                                <tr class="flex justify-between mb-4">
                                    <td>شهر</td>
                                    <td>{{ $educationRecord->city }}</td>
                                </tr>
                                <tr class="flex justify-between mb-4">
                                    <td>مدرسه یا دانشگاه</td>
                                    <td>{{ $educationRecord->school_name }}</td>
                                </tr>
                            </table>
                            <br>
                            <div class="relative top-[-0.35rem] rtl:pr-4 ltr:pl-4">
                                <div class="text-xl font-semibold text-slate-700 mb-2 dark:text-slate-400">
                                    توضیحات
                                </div>
                            </div>
                            <p> {{$educationRecord->text1}}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="mb-10">
            <div class="flex items-center mb-4 text-slate-900 dark:text-slate-200">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
                <h2 class="mx-2 text-2xl font-bold">
                    زبان ها
                </h2>
            </div>
            @foreach($resume->languages()->get() as $language)
                <div class=" items-center justify-between">
                    <span class="font-bold text-slate-700 text-lg dark:text-slate-400">{{$language->title}}</span>
                    <table class="w-full mb-2">
                        <tr class="flex justify-between mb-4">
                            <td>میزان تسلط:</td>
                            <td><span>{{ $language->fluency_level }}</span></td>
                            <td>مدرک:</td>
                            <td>{{ $language->degree }}</td>
                            <td>نمره:</td>
                            <td class="text-right">{{ $language->score }}</td>
                        </tr>
                    </table>
                </div>
            @endforeach
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
                    سوابق کاری
                </h2>
            </div>
            <ul class="exp-menu-container">
                @foreach($resume->works()->get() as $work)
                    <li class="exp-menu-item">
                        <div class="relative top-[-0.35rem] rtl:pr-4 ltr:pl-4">
                            <div class="text-xl font-semibold text-slate-700 mb-2 dark:text-slate-400">
                                {{ $work->company_name }} | {{ $work->position }}
                            </div>
                            <div class="text-sm text-slate-500">{{ $work->from_date_year }} - {{$work->from_date_month}}
                                | {{ $work->to_date_year }} - {{$work->to_date_month}}</div>
                            <table class="w-full mb-2">
                                <tr class="flex justify-between mb-4">
                                    <td>شهر</td>
                                    <td>{{ $work->city }}</td>
                                </tr>
                            </table>
                            <br>
                            <div class="relative top-[-0.35rem] rtl:pr-4 ltr:pl-4">
                                <div class="text-xl font-semibold text-slate-700 mb-2 dark:text-slate-400">
                                    توضیحات
                                </div>
                            </div>
                            <p> {{$work->text1}}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="mb-10">
            <div class="flex items-center mb-4 text-slate-900 dark:text-slate-200">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
                <h2 class="mx-2 text-2xl font-bold">
                    دانش نرم افزاری
                </h2>
            </div>
            <div class="grid grid-cols1 sm:grid-cols-2 gap-y-4 gap-x-6">
                @foreach($resume->softwareKnowledges()->get() as $softwareKnowledge)
                    <div>
                        <span class="inline-block font-medium text-slate-700 text-md mb-2 dark:text-slate-400">{{$softwareKnowledge->title}}</span>
                        <div class="w-full bg-primary/25 rounded-full">
                            <div class="progress-bar" style="width: {{$softwareKnowledge->fluency_level*20}}%;">
                                <span class="px-2">{{$softwareKnowledge->fluency_level}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
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
                    دوره ها و مدارک
                </h2>
            </div>
            <ul class="exp-menu-container">
                @foreach($resume->courses()->get() as $course)
                    <li class="exp-menu-item">
                        <div class="relative top-[-0.35rem] rtl:pr-4 ltr:pl-4">
                            <div class="text-xl font-semibold text-slate-700 mb-2 dark:text-slate-400">
                                {{ $course->title }}
                            </div>
                            <table class="w-full mb-2">
                                <tr class="flex justify-between mb-4">
                                    <td>برگزار کننده</td>
                                    <td>{{ $course->organizer }}</td>

                                    <td>تاریخ (سال)</td>
                                    <td>{{ $course->year }}</td>
                                </tr>
                            </table>
                        </div>
                    </li>
                @endforeach
            </ul>
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
                    سوابق پژوهشی ، افتخارات
                </h2>
            </div>
            <ul class="exp-menu-container">
                @foreach($resume->researchs()->get() as $research)
                    <li class="exp-menu-item">
                        <div class="relative top-[-0.35rem] rtl:pr-4 ltr:pl-4">
                            <div class="text-xl font-semibold text-slate-700 mb-2 dark:text-slate-400">
                                {{ $research->title }}
                            </div>
                            <table class="w-full mb-2">
                                <tr class="flex justify-between mb-4">
                                    <td>نوع</td>
                                    <td>{{ $research->type }}</td>
                                    <td>تاریخ</td>
                                    <td>{{ $research->year }}</td>
                                </tr>
                            </table>
                            <br>
                            <div class="relative top-[-0.35rem] rtl:pr-4 ltr:pl-4">
                                <div class="text-xl font-semibold text-slate-700 mb-2 dark:text-slate-400">
                                    توضیحات
                                </div>
                            </div>
                            <p> {{$research->text1}}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="mb-10">
            <div class="flex items-center mb-2 text-slate-900 dark:text-slate-200">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>
                <h2 class="mx-2 text-2xl font-bold dark:text-slate-200">
                    تفریحات
                </h2>
            </div>
            <div class="flex flex-wrap">
                @foreach($resume->hobbies()->get() as $hobby)
                <span class="favorite-item">{{$hobby->title}}</span>
                @endforeach
            </div>
        </div>
    </main>

@endsection