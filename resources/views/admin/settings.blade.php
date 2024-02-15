@extends('admin.lyout')

@section('title')
پنل مدیریت - تماس با ما
@endsection

@section('css')

@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">تماس با ما</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">تماس با ما</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid">
            <div class="container">
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1">
                                        تماس با ما
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <form action="{{ route('admin.updateSettings') }}" method="post" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="email" class="header-label">ایمیل</label>
                                            <input type="text" name="email" class="form-control form-control-sm @if($errors->has('email')) is-invalid @endif" id="email" value="{{ $settings->email }}">
                                            @if($errors->has('email'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('email') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="mobile" class="header-label">موبایل</label>
                                            <input type="text" name="mobile" class="form-control form-control-sm @if($errors->has('mobile')) is-invalid @endif" id="mobile" value="{{ $settings->mobile }}">
                                            @if($errors->has('mobile'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('mobile') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="phone" class="header-label">شماره ثابت</label>
                                            <input type="text" name="phone" class="form-control form-control-sm @if($errors->has('phone')) is-invalid @endif" id="phone" value="{{ $settings->phone }}">
                                            @if($errors->has('phone'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('phone') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <div class="form-group float-label">
                                            <label for="responseTime" class="header-label">زمان پاسخگویی</label>
                                            <input type="text" name="responseTime" class="form-control form-control-sm @if($errors->has('responseTime')) is-invalid @endif" id="responseTime" value="{{ $settings->response_time }}">
                                            @if($errors->has('responseTime'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('responseTime') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group float-label">
                                            <label for="address" class="header-label">آدرس</label>
                                            <input type="text" name="address" class="form-control form-control-sm @if($errors->has('address')) is-invalid @endif" id="address" value="{{ $settings->address }}">
                                            @if($errors->has('address'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('address') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-sm col-12">بروزرسانی</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        var editor_config = {
            path_absolute : "/",
            selector: "textarea.editor",
            language: 'fa_IR',
            directionality : 'rtl',
            plugins: [ "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table directionality",
                "template paste textpattern" ],
            toolbar1: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
            toolbar2: "print preview media image | forecolor backcolor | blockquote",
            image_class_list: [
                {title: 'None', value: ''},
                {title: 'Img Responsive', value: 'img-responsive'},
                {title: 'Img Rounded Responsive', value: 'img-responsive img-rounded'}
            ],
            relative_urls: false,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };
        tinymce.init(editor_config);
    </script>
@endsection