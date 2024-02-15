@extends('admin.lyout')

@section('title')
    پنل مدیریت - ویرایش مرحله
@endsection

@section('css')
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                    data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">ویرایش مرحله</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.applyLevels') }}">مراحل اپلای</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">ویرایش مرحله</li>
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
                                        ویرایش مرحله
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <form action="{{ route('admin.updateApplyLevel') }}" method="post" class="row"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $applyLevel->id }}">
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="title" class="header-label">عنوان</label>
                                            <input type="text" name="title"
                                                class="form-control form-control-sm @if ($errors->has('title')) is-invalid @endif"
                                                id="title" value="{{ $applyLevel->title }}">
                                            @if ($errors->has('title'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('title') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="link" class="header-label">لینک ویدئو</label>
                                            <input type="text" name="link"
                                                class="form-control form-control-sm @if ($errors->has('link')) is-invalid @endif"
                                                id="link" placeholder="لینک ویدئو" value="{{ $applyLevel->link }}">
                                            @if ($errors->has('link'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('link') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="pos" class="header-label">جایگاه</label>
                                            <input type="text" name="pos"
                                                class="form-control form-control-sm @if ($errors->has('pos')) is-invalid @endif"
                                                id="pos" value="{{ $applyLevel->pos }}">
                                            @if ($errors->has('pos'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('pos') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="phase" class="header-label">فاز</label>
                                            <input type="text" name="phase"
                                                class="form-control form-control-sm @if ($errors->has('phase')) is-invalid @endif"
                                                id="phase" value="{{ $applyLevel->phase }}">
                                            @if ($errors->has('phase'))
                                                <div class="invalid-feedback">
                                                    <small>{{ $errors->first('phase') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="phasePercent" class="header-label">درصد فاز</label>
                                            <input type="text" name="phasePercent"
                                                class="form-control form-control-sm @if ($errors->has('phasePercent')) is-invalid @endif"
                                                id="phasePercent" value="{{ $applyLevel->phase_percent }}">
                                            @if ($errors->has('phasePercent'))
                                                <div class="invalid-feedback">
                                                    <small>{{ $errors->first('phasePercent') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="progressPercent" class="header-label">درصد پیشرفت</label>
                                            <input type="text" name="progressPercent"
                                                class="form-control form-control-sm @if ($errors->has('progressPercent')) is-invalid @endif"
                                                id="progressPercent" value="{{ $applyLevel->progress_percent }}">
                                            @if ($errors->has('progressPercent'))
                                                <div class="invalid-feedback">
                                                    <small>{{ $errors->first('progressPercent') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="nextLevelButton" class="header-label">متن دکمه</label>
                                            <input type="text" name="nextLevelButton"
                                                class="form-control form-control-sm @if ($errors->has('nextLevelButton')) is-invalid @endif"
                                                id="nextLevelButton" value="{{ $applyLevel->next_level_button }}">
                                            @if ($errors->has('nextLevelButton'))
                                                <div class="invalid-feedback">
                                                    <small>{{ $errors->first('nextLevelButton') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-lg-9">
                                        <div class="form-group float-label">
                                            <label for="image" class="header-label">تصویر</label>
                                            <input type="file" name="image" class="form-control form-control-sm" id="image">
                                        </div>
                                    </div>
                                    <div class="row file-inputs">
                                        <div class="col-6 col-lg-3">
                                            <div class="form-group float-label">
                                                <label for="file" class="header-label">فایل 1</label>
                                                <input type="file" name="files[]" class="form-control form-control-sm"
                                                    id="file">
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <div class="form-group float-label">
                                                <label for="filename" class="header-label">نام فایل 1</label>
                                                <input type="text" name="filename[]"
                                                    class="form-control form-control-sm"
                                                    id="filename" placeholder="نام فایل">
                                               
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12 mt-2">
                                        <button type="button" class="btn btn-warning btn-sm add-file-input-btn">اضافه کردن
                                            فایل</button>
                                    </div>
                                    <div class="col-12  mt-4">
                                        <div class="form-group">
                                            <textarea name="text" class="editor @if ($errors->has('text')) is-invalid @endif"
                                                id="text">{{ $applyLevel->text }}</textarea>
                                            @if ($errors->has('text'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('text') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-warning btn-sm col-12">ویرایش</button>
                                    </div>
                                    <div class="col-12 mt-4 col-md-6">
                                        <img src="{{ route('imageLevel', ['id' => $applyLevel->id, 'ua' => strtotime($applyLevel->updated_at)]) }}"
                                            class="img-fluid">
                                    </div>
                                    <div class="col-12 mt-4 col-md-6">
                                        @php
                                            $directory = public_path('/uploads/level-file/' . $applyLevel->id . '/');
                                            
                                            $filecount = 0;
                                            $files = glob($directory . '*');
                         
                                            $filecount = count($files);
                                            
           
                                        @endphp
                                        @for ($i = 0; $i < $filecount; $i++)
                                        <a href="{{route('fileLevel', ['id' => $applyLevel->id, 'pos' => $i])}}"
                                            class="btn btn-info col-12">دانلود فایل {{$i+1}}</a>
                                        @endfor
                                       
                                       
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
    <script>
        $(".add-file-input-btn").click(function() {
            num = $(".file-inputs .col-lg-3").length / 2 + 1
            $(".file-inputs").append(`
            <div class="col-6 col-lg-3">
                <div class="form-group float-label">
                    <label for="file${num}" class="header-label">فایل ${num}</label>
                    <input type="file" name="files[]" class="form-control form-control-sm" id="file${num}">
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="form-group float-label">
                    <label for="filename${num}" class="header-label">نام فایل ${num}</label>
                    <input type="text" name="filename[]"
                        class="form-control form-control-sm"
                        id="filename${num}" placeholder="نام فایل ${num}">
                    
                </div>
            </div>
            `)
        })
    </script>
    <script type="text/javascript">
        var editor_config = {
            path_absolute: "/",
            selector: "textarea.editor",
            language: 'fa_IR',
            directionality: 'rtl',
            plugins: ["advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table directionality",
                "template paste textpattern"
            ],
            toolbar1: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
            toolbar2: "print preview media image | forecolor backcolor | blockquote",
            image_class_list: [{
                    title: 'None',
                    value: ''
                },
                {
                    title: 'Img Responsive',
                    value: 'img-responsive'
                },
                {
                    title: 'Img Rounded Responsive',
                    value: 'img-responsive img-rounded'
                }
            ],
            automatic_uploads: true,
            file_picker_types: "image",
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();

                    reader.onload = function() {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        // call the callback and populate the Title field with the file name
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            },
            relative_urls: false,
            file_browser_callback: function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no"
                });
            }
        };
        tinymce.init(editor_config);
    </script>
@endsection
