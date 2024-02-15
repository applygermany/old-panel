<!DOCTYPE html>
<html direction="rtl" dir="rtl" style="direction: rtl" >
<head>
    <meta charset="utf-8" />
    <title>اپلای جرمنی | ورود به پنل ادمین</title>
    <meta name="description" content="مترونیک admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services و widgets." />
    <meta name="keywords" content="مترونیک, bootstrap, bootstrap 5, Angular 11, VueJs, React, Laravel, admin themes, web design, figma, web development, ree admin themes, bootstrap admin, bootstrap dashboard" />
    <link rel="canonical" href="Https://preview.keenthemes.com/metronic8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ url('assets/media/logos/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ url('assets/plugins/global/plugins.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/style.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/lobibox-master/dist/css/lobibox.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body id="kt_body" class="bg-dark header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-size1: 100% 50%; background-image: url({{ url('assets/media/misc/outdoor.png') }})">
        <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
            <a href="https://applygermany.net/" class="mb-12">
                <img alt="Logo" src="{{ url('assets/media/logos/logo.png') }}" class="h-45px" />
            </a>
            <div class="w-lg-500px bg-white rounded shadow-sm p-10 p-lg-15 mx-auto">
                <form class="form w-100" action="{{ route('admin.login') }}" method="post">
                    @csrf
                    <div class="text-center mb-10">
                        <h1 class="text-dark mb-3">ورود  به پنل مدیر</h1>
                    </div>
                    <div class="fv-row mb-10">
                        <label for="emailMobile" class="form-label fs-6 fw-bolder text-dark">ایمیل/موبایل</label>
                        <input class="form-control form-control-lg form-control-solid" type="text" name="emailMobile" id="emailMobile" placeholder="ایمیل/موبایل" />
                        @if ($errors->has('emailMobile'))
                            <div class="m-0 p-0 mb-3 text-danger"><small>{{ $errors->first('emailMobile') }}</small></div>
                        @endif
                    </div>
                    <div class="fv-row mb-10">
                        <div class="d-flex flex-stack mb-2">
                            <label for="password" class="form-label fw-bolder text-dark fs-6 mb-0">گذرواژه</label>
                            <a href="" class="link-primary fs-6 fw-bolder">گذرواژه را فراموش کرده اید ؟</a>
                        </div>
                        <input class="form-control form-control-lg form-control-solid" type="password" name="password" id="password" />
                        @if ($errors->has('password'))
                            <div class="m-0 p-0 mb-3 text-danger"><small>{{ $errors->first('password') }}</small></div>
                        @endif
                    </div>
                    <div class="text-center">
                        <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                            <span class="indicator-label">ورود</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ url('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ url('assets/js/scripts.bundle.js') }}"></script>
<script src="{{ url('assets/plugins/lobibox-master/dist/js/lobibox.min.js') }}"></script>
<script src="{{ url('assets/plugins/lobibox-master/dist/js/messageboxes.min.js') }}"></script>
<script src="{{ url('assets/plugins/lobibox-master/dist/js/notifications.min.js') }}"></script>
<script>
    @if(session()->get('error'))
       $( document ).ready(function(){
        Lobibox.notify('error', {
            title: " عملیات نا موفق : ",
            msg: "{{ session()->get('error')}}",
            icon: 'fa fa-warning',
            position: 'bottom left',
            sound: false,
            mouse_over: "pause"
        });
    });
    @endif
    @if(session()->get('success'))
        $( document ).ready(function(){
        Lobibox.notify('success', {
            title: " عملیات موفق : ",
            msg: "{{ session()->get('success')}}",
            icon: 'fa fa-check',
            position: 'bottom left',
            sound: false,
            mouse_over: "pause"
        });
    });
    @endif
</script>
</body>
</html>