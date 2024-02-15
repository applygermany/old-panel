<!DOCTYPE html>
<html lang="fa" dir="rtl" class="h-full">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="قالب HTML شخصی و رزومه"/>
    <title>اپلای جرمنی | رزمه</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{url('motivation/icons/apple-touch-icon.png')}}"/>
    <link rel="icon" sizes="192x192" href="{{url('motivation/icons/android-chrome-192x192.png')}}"/>
    <link rel="icon" sizes="512x512" href="{{url('motivation/icons/android-chrome-512x512.png')}}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{url('motivation/icons/favicon-32x32.png')}}"/>
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('motivation/icons/favicon-16x16.png')}}"/>
    <link rel="stylesheet" href="{{url('motivation/css/main.min.css')}}"/>
    <script>
        if (localStorage.theme === "dark" || !"theme" in localStorage) {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
    </script>
</head>

<body
        class="bg-slate-50 antialiased text-slate-600 mx-4 sm:mx-6 mb-4 dark:bg-[#131313] dark:text-slate-400 selection:bg-[#007bff] selection:text-white">
<div class="max-w-7xl mx-auto">
    <header class="py-4">
        <div class="flex justify-between items-center">
            <button id="toggleMenu" data-toggle="false" class="z-20  hover:cursor-pointer sm:hidden">
                <svg class="icon" id="menuIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
                <svg id="closeIcon" class="hidden icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <div class="rtl:pr-2 ltr:pl-2 hidden sm:flex sm:items-center">
                <!-- Add "index.html" if you are not using a local server or host -->
                <a href="/" draggable="false" class="flex items-center rtl:ml-6 ltr:mr-6">
                    <img src="{{url('assets/media/logos/logo.png')}}" alt="Apply Germany" draggable="false"
                         class="h-9 w-auto rtl:ml-2 ltr:mr-2 drag-none rounded-lg" />
                </a>
                <nav>
                    <ul class="flex gap-x-4">
                        <li>
                            <a href="{{route('admin.showResume', ['id' => $resume->id])}}"
                               class="font-medium text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200">بازگشت</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="flex flex-row-reverse items-center rtl:gap-x-2 ltr:gap-x-1">
                <button id="toggleDarkMode">
                    <svg class="icon hidden" id="moonIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                    </svg>
                    <svg class="icon hidden" id="sunIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5" />
                        <line x1="12" y1="1" x2="12" y2="3" />
                        <line x1="12" y1="21" x2="12" y2="23" />
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                        <line x1="1" y1="12" x2="3" y2="12" />
                        <line x1="21" y1="12" x2="23" y2="12" />
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                    </svg>
                </button>
            </div>
        </div>
        <nav id="mobileMenu" class="hidden mt-4 mb-2 rtl:mr-2.5 ltr:ml-2.5 sm:hidden">
            <ul class="space-y-4 rtl:border-r ltr:border-l border-slate-200 dark:border-slate-700">
                <li>
                    <a href="{{route('admin.showResume', ['id' => $resume->id])}}"
                       class="text-slate-600 block rtl:pr-4 ltr:pl-4 text-base font-medium hover:text-slate-900 rtl:focus:border-r-2 ltr:focus:border-l-2 focus:border-slate-700 rtl:hover:border-r-2 ltr:hover:border-l-2 hover:border-slate-700 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-200"
                       >بازگشت</a>
                </li>
            </ul>
        </nav>
    </header>
    <div class="bg-white flex flex-col shadow-md rounded-3xl lg:flex-row dark:bg-zinc-900">
        @yield('content')
    </div>
    <br/><br/>
</div>
<script src="{{url('motivation/js/main.min.js')}}" defer></script>
</body>