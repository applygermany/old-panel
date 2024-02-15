<!DOCTYPE html>
<html lang="fa" dir="rtl" class="h-full">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="{{url('motivation/css/pdf.css')}}"/>
</head>

<body class="bg-slate-50 antialiased text-slate-600 mx-4 mb-4 dark:text-slate-400 selection:bg-[#007bff] selection:text-white">
<div class="pdfbody">
    <div class="max-w-7xl mx-auto">
        <br/><br/>
        <div class="bg-white flex flex-col shadow-md rounded-3xl lg:flex-row dark:bg-zinc-900">
            @yield('content')
        </div>
        <br/><br/>
    </div>
</div>
</body>
</html>