@extends('admin.lyout')

@section('title')
پنل مدیریت - ویرایش سوال
@endsection

@section('css')

@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">ویرایش سوال</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.cfaq') }}">سوالات متداول همکاری</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">ویرایش سوال</li>
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
                                        ویرایش سوال
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <form action="{{ route('admin.updateCFaq') }}" method="post" class="row">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $cfaq->id }}">
                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="question" class="header-label">سوال</label>
                                            <input type="text" name="question" class="form-control form-control-sm @if($errors->has('question')) is-invalid @endif" id="question" value="{{ $cfaq->question }}">
                                            @if($errors->has('question'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('question') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="answer" class="header-label">پاسخ</label>
                                            <textarea name="answer" class="editor @if($errors->has('answer')) is-invalid @endif" id="answer">{{ $cfaq->answer }}</textarea>
                                            @if($errors->has('answer'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('answer') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-warning btn-sm col-12">ویرایش</button>
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