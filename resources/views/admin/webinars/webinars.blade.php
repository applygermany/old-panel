@extends('admin.lyout')

@section('title')
    پنل مدیریت - وبینار ها
@endsection

@section('css')

@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">وبینار ها</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">وبینار ها</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="cityt d-flex flex-column-fluid">
            <div class="container">
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1"
                                       data-bs-toggle="collapse" data-bs-target="#collapseWebinar" aria-expanded="false" aria-controls="collapseExample">
                                        وبینار جدید
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body collapse py-3" id="collapseWebinar">
                                <form action="{{ route('admin.saveWebinar') }}" method="post" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="title">عنوان</label>
                                            <input type="text" name="title" class="form-control form-control-sm @if($errors->has('title')) is-invalid @endif" id="title" placeholder="عنوان" value="{{ old('title') }}">
                                            @if($errors->has('title'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('title') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="time">زمان برگزاری</label>
                                            <input type="text" name="time" class="form-control form-control-sm @if($errors->has('time')) is-invalid @endif" id="time" placeholder="زمان برگزاری" value="{{ old('time') }}">
                                            @if($errors->has('time'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('time') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="paymentPrice">مبلغ ورودی </label>
                                            <input type="text" name="paymentPrice" class="form-control form-control-sm @if($errors->has('paymentPrice')) is-invalid @endif" id="paymentPrice" placeholder="مبلغ" value="{{ old('paymentPrice') }}">
                                            @if($errors->has('paymentPrice'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('paymentPrice') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="slug">SLUG</label>
                                            <input type="text" name="slug" class="form-control form-control-sm @if($errors->has('slug')) is-invalid @endif" id="slug" placeholder="Slug" value="{{ old('slug') }}">
                                            @if($errors->has('slug'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('slug') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="headlines" class="header-label">سرفصل ها</label>
                                            <textarea name="headlines" class="editor @if($errors->has('headlines')) is-invalid @endif" id="headlines" placeholder="سرفصل ها">{{ old('headlines') }}</textarea>
                                            @if($errors->has('headlines'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('headlines') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="paymentText" class="header-label">متن پرداخت</label>
                                            <textarea name="paymentText" class="editor @if($errors->has('paymentText')) is-invalid @endif" id="paymentText" placeholder="متن پرداخت">{{ old('paymentText') }}</textarea>
                                            @if($errors->has('paymentText'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('paymentText') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="organizerName">نام برگزار کننده</label>
                                            <input type="text" name="organizerName" class="form-control form-control-sm @if($errors->has('organizerName')) is-invalid @endif" id="organizerName" placeholder="نام برگزار کننده" value="{{ old('organizerName') }}">
                                            @if($errors->has('organizerName'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('organizerName') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="organizerField">تخصص برگزار کننده</label>
                                            <input type="text" name="organizerField" class="form-control form-control-sm @if($errors->has('organizerField')) is-invalid @endif" id="organizerField" placeholder="تخصص برگزار کننده" value="{{ old('organizerField') }}">
                                            @if($errors->has('organizerField'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('organizerField') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="firstMeeting">تاریخ برگزاری جلسه اول</label>
                                            <input type="text" name="firstMeeting" class="form-control form-control-sm @if($errors->has('firstMeeting')) is-invalid @endif" id="firstMeeting" placeholder="تاریخ برگزاری جلسه اول" value="{{ old('firstMeeting') }}">
                                            @if($errors->has('firstMeeting'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('firstMeeting') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="firstMeetingStartTime">ساعت شروع جلسه اول</label>
                                            <input type="text" name="firstMeetingStartTime" class="form-control form-control-sm @if($errors->has('firstMeetingStartTime')) is-invalid @endif" id="firstMeetingStartTime" placeholder="ساعت شروع جلسه اول" value="{{ old('firstMeetingStartTime') }}">
                                            @if($errors->has('firstMeetingStartTime'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('firstMeetingStartTime') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="firstMeetingEndTime">ساعت خاتمه جلسه اول</label>
                                            <input type="text" name="firstMeetingEndTime" class="form-control form-control-sm @if($errors->has('firstMeetingEndTime')) is-invalid @endif" id="firstMeetingEndTime" placeholder="ساعت خاتمه جلسه اول" value="{{ old('firstMeetingEndTime') }}">
                                            @if($errors->has('firstMeetingEndTime'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('firstMeetingEndTime') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="secondMeeting">تاریخ برگزاری جلسه دوم</label>
                                            <input type="text" name="secondMeeting" class="form-control form-control-sm @if($errors->has('secondMeeting')) is-invalid @endif" id="secondMeeting" placeholder="تاریخ برگزاری جلسه دوم" value="{{ old('secondMeeting') }}">
                                            @if($errors->has('secondMeeting'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('secondMeeting') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="secondMeetingStartTime">ساعت شروع جلسه دوم</label>
                                            <input type="text" name="secondMeetingStartTime" class="form-control form-control-sm @if($errors->has('secondMeetingStartTime')) is-invalid @endif" id="secondMeetingStartTime" placeholder="ساعت شروع جلسه دوم" value="{{ old('secondMeetingStartTime') }}">
                                            @if($errors->has('secondMeetingStartTime'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('secondMeetingStartTime') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="secondMeetingEndTime">ساعت خاتمه جلسه دوم</label>
                                            <input type="text" name="secondMeetingEndTime" class="form-control form-control-sm @if($errors->has('secondMeetingEndTime')) is-invalid @endif" id="secondMeetingEndTime" placeholder="ساعت خاتمه جلسه دوم" value="{{ old('secondMeetingEndTime') }}">
                                            @if($errors->has('secondMeetingEndTime'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('secondMeetingEndTime') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="image" class="header-label">تصویر</label>
                                            <input type="file" name="image" class="form-control form-control-sm" id="image">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="organizerImage" class="header-label">عکس برگزار کننده</label>
                                            <input type="file" name="organizerImage" class="form-control form-control-sm" id="organizerImage">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="webinarBanner" class="header-label">بنر وبینار (2800*100)</label>
                                            <input type="file" name="webinarBanner" class="form-control form-control-sm" id="webinarBanner">
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
                                    <span class="card-label fw-bolder fs-3 mb-1">جستجو</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchTitle">عنوان</label>
                                            <input type="text" name="searchTitle" class="form-control form-control-sm" id="searchTitle" placeholder="عنوان">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchPaymentLink">لینک</label>
                                            <input type="text" name="searchPaymentLink" class="form-control form-control-sm" id="searchPaymentLink" placeholder="لینک">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchFirstMeeting">جلسه اول</label>
                                            <input type="text" name="searchFirstMeeting" class="form-control form-control-sm" id="searchFirstMeeting" placeholder="جلسه اول">
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="searchSecondMeeting">جلسه دوم</label>
                                            <input type="text" name="searchSecondMeeting" class="form-control form-control-sm" id="searchSecondMeeting" placeholder="جلسه دوم">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="button" id="search" class="btn btn-primary btn-sm col-12">جستجو</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست وبینار ها</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.webinars.list')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="showContainer">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> شرکت کنندگان در وبینار</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="container_show">

                </form>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click','.copyLink',function () {
            let url = $(this).data('url');
            navigator.clipboard.writeText(url);
            $(this).text("کپی شد!")
        });
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
        $(document).on('click','.delete',function(e) {
            if(confirm('از حذف این مورد مطمئن هستید ؟')) {
                window.location.href = $(this).attr('href');
            } else {
                e.preventDefault();
            }
        });
        $(document).on('click','.pagination a',function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getWebinars(page);
        });
        function getWebinars(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getWebinars') }}'+"?page="+page,
                type: "POST",
                data: { _token : '{{ csrf_token() }}', searchTitle : $('#searchTitle').val(), searchPaymentLink : $('#searchPaymentLink').val(), searchFirstMeeting : $('#searchFirstMeeting').val(), searchSecondMeeting : $('#searchSecondMeeting').val()},
                success: function(data){
                    $('#tableBox').html(data);
                }
            });
        }
        $(document).on('click','#search',function() {
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.getWebinars') }}',
                type: "POST",
                data: { _token : '{{ csrf_token() }}', searchTitle : $('#searchTitle').val(), searchPaymentLink : $('#searchPaymentLink').val(), searchFirstMeeting : $('#searchFirstMeeting').val(), searchSecondMeeting : $('#searchSecondMeeting').val()},
                success: function(data){
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });
        $(document).on('click','.show_',function(){
            var url = $(this).data('url');
            $.ajax({
                url: url,
                success: function(data) {
                    $('#container_show').html(data);
                    var showContainer = new bootstrap.Modal(document.getElementById('showContainer'), { keyboard: false });
                    showContainer.show();
                }
            });
        });
    </script>
@endsection
