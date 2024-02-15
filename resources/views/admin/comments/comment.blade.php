@extends('admin.lyout')

@section('title')
    پنل مدیریت - کامنت ها
@endsection

@section('css')

@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">کامنت ها</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">کامنت ها</li>
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
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1"
                                       data-bs-toggle="collapse" data-bs-target="#collapseNewComment" aria-expanded="false" aria-controls="collapseExample">
                                        کامنت جدید
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body collapse py-3" id="collapseNewComment">
                                <form action="{{ route('admin.saveComment') }}" method="post" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="name" class="header-label">نام دانشجو</label>
                                            <input type="text" name="name" class="form-control form-control-sm @if($errors->has('name')) is-invalid @endif" id="name" placeholder="نام دانشجو" value="{{ old('name') }}">
                                            @if($errors->has('name'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('name') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="university" class="header-label">نام دانشگاه</label>
                                            <input type="text" name="university" class="form-control form-control-sm @if($errors->has('university')) is-invalid @endif" id="university" placeholder="نام دانشگاه" value="{{ old('university') }}">
                                            @if($errors->has('university'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('university') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="field" class="header-label">رشته</label>
                                            <input type="text" name="field" class="form-control form-control-sm @if($errors->has('field')) is-invalid @endif" id="field" placeholder="رشته" value="{{ old('field') }}">
                                            @if($errors->has('field'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('field') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                   
                                    <div class="col-12 col-lg-8">
                                        <div class="form-group float-label">
                                            <label for="text" class="header-label">متن</label>
                                            <textarea rows="4" type="text" name="text" class="form-control form-control-sm @if($errors->has('text')) is-invalid @endif" id="text" placeholder="متن">{{ old('text') }}</textarea>
                                            @if($errors->has('text'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('text') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="rating" class="header-label">امتیاز</label>
                                            <input type="text" name="rating" class="form-control form-control-sm @if($errors->has('rating')) is-invalid @endif" id="rating" placeholder="امتیاز" value="{{ old('rating') }}">
                                            @if($errors->has('rating'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('rating') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <div class="form-group float-label">
                                            <label for="photo" class="header-label">آپلود تصویر</label>
                                            <input type="file" name="photo" class="form-control form-control-sm" id="photo">
                                            @if($errors->has('photo'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('photo') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-success btn-sm col-12">ثبت</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست کامنت ها</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.comments.list')
                                </div>
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
        $(document).on('click','.delete',function(e){
            if(confirm('از حذف این مورد مطمئن هستید ؟')) {
                window.location.href = $(this).attr('href');
            } else {
                e.preventDefault();
            }
        });
    </script>
@endsection