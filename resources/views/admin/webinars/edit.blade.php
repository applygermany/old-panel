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
                        <li class="breadcrumb-item text-dark">
                            <a href="{{ route('admin.webinars') }}">وبینار ها</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">ویرایش وبینار</li>
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
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1">ویرایش وبینار</a>
                                </h3>
                            </div>
                            <div class="card-body py-3" id="collapseWebinar">
                                <form action="{{ route('admin.updateWebinar') }}" method="post" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <input name="id" type="hidden" value="{{ $webinar->id }}">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="title" class="header-label">عنوان</label>
                                            <input type="text" name="title" class="form-control form-control-sm @if($errors->has('title')) is-invalid @endif" id="title" placeholder="عنوان" value="{{ $webinar->title }}">
                                            @if($errors->has('title'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('title') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="time" class="header-label">زمان برگزاری</label>
                                            <input type="text" name="time" class="form-control form-control-sm @if($errors->has('time')) is-invalid @endif" id="time" placeholder="زمان برگزاری" value="{{ $webinar->time }}">
                                            @if($errors->has('time'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('time') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="paymentPrice" class="header-label">قیمت </label>
                                            <input type="text" name="paymentPrice" class="form-control form-control-sm @if($errors->has('paymentPrice')) is-invalid @endif" id="paymentPrice" placeholder="لینک پرداخت" value="{{ $webinar->price }}">
                                            @if($errors->has('paymentPrice'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('paymentPrice') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="slug">SLUG</label>
                                            <input type="text" name="slug" class="form-control form-control-sm @if($errors->has('slug')) is-invalid @endif" id="slug" placeholder="Slug" value="{{ $webinar->slug }}">
                                            @if($errors->has('slug'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('slug') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="headlines" class="header-label">سرفصل ها</label>
                                            <textarea name="headlines" class="editor @if($errors->has('headlines')) is-invalid @endif" id="headlines" placeholder="سرفصل ها">{{ $webinar->headlines }}</textarea>
                                            @if($errors->has('headlines'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('headlines') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="paymentText" class="header-label">متن پرداخت</label>
                                            <textarea name="paymentText" class="editor @if($errors->has('paymentText')) is-invalid @endif" id="paymentText" placeholder="متن پرداخت">{{ $webinar->payment_text }}</textarea>
                                            @if($errors->has('paymentText'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('paymentText') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="paymentLink" class="header-label">لینک پرداخت</label>
                                            <input type="text" name="paymentLink" class="form-control form-control-sm @if($errors->has('paymentLink')) is-invalid @endif" id="paymentLink" placeholder="لینک پرداخت" value="{{ $webinar->payment_link }}">
                                            @if($errors->has('paymentLink'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('paymentLink') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="organizerName" class="header-label">نام برگزار کننده</label>
                                            <input type="text" name="organizerName" class="form-control form-control-sm @if($errors->has('organizerName')) is-invalid @endif" id="organizerName" placeholder="نام برگزار کننده" value="{{ $webinar->organizer_name }}">
                                            @if($errors->has('organizerName'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('organizerName') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="organizerField" class="header-label">تخصص برگزار کننده</label>
                                            <input type="text" name="organizerField" class="form-control form-control-sm @if($errors->has('organizerField')) is-invalid @endif" id="organizerField" placeholder="تخصص برگزار کننده" value="{{ $webinar->organizer_field }}">
                                            @if($errors->has('organizerField'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('organizerField') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="firstMeeting" class="header-label">تاریخ برگزاری جلسه اول</label>
                                            <input type="text" name="firstMeeting" class="form-control form-control-sm @if($errors->has('firstMeeting')) is-invalid @endif" id="firstMeeting" placeholder="تاریخ برگزاری جلسه اول" value="{{ $webinar->first_meeting }}">
                                            @if($errors->has('firstMeeting'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('firstMeeting') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="firstMeetingStartTime" class="header-label">ساعت شروع جلسه اول</label>
                                            <input type="text" name="firstMeetingStartTime" class="form-control form-control-sm @if($errors->has('firstMeetingStartTime')) is-invalid @endif" id="firstMeetingStartTime" placeholder="ساعت شروع جلسه اول" value="{{ $webinar->first_meeting_start_time }}">
                                            @if($errors->has('firstMeetingStartTime'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('firstMeetingStartTime') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="firstMeetingEndTime" class="header-label">ساعت خاتمه جلسه اول</label>
                                            <input type="text" name="firstMeetingEndTime" class="form-control form-control-sm @if($errors->has('firstMeetingEndTime')) is-invalid @endif" id="firstMeetingEndTime" placeholder="ساعت خاتمه جلسه اول" value="{{ $webinar->first_meeting_end_time }}">
                                            @if($errors->has('firstMeetingEndTime'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('firstMeetingEndTime') }}</small></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="secondMeeting" class="header-label">تاریخ برگزاری جلسه دوم</label>
                                            <input type="text" name="secondMeeting" class="form-control form-control-sm @if($errors->has('secondMeeting')) is-invalid @endif" id="secondMeeting" placeholder="تاریخ برگزاری جلسه دوم" value="{{ $webinar->second_meeting }}">
                                            @if($errors->has('secondMeeting'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('secondMeeting') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="secondMeetingStartTime" class="header-label">ساعت شروع جلسه دوم</label>
                                            <input type="text" name="secondMeetingStartTime" class="form-control form-control-sm @if($errors->has('secondMeetingStartTime')) is-invalid @endif" id="secondMeetingStartTime" placeholder="ساعت شروع جلسه دوم" value="{{ $webinar->second_meeting_start_time }}">
                                            @if($errors->has('secondMeetingStartTime'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('secondMeetingStartTime') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="secondMeetingEndTime" class="header-label">ساعت خاتمه جلسه دوم</label>
                                            <input type="text" name="secondMeetingEndTime" class="form-control form-control-sm @if($errors->has('secondMeetingEndTime')) is-invalid @endif" id="secondMeetingEndTime" placeholder="ساعت خاتمه جلسه دوم" value="{{ $webinar->second_meeting_end_time }}">
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

                                    <div class="col-12 my-4">
                                        <button type="submit" class="btn btn-warning btn-sm col-12">ویرایش</button>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <img src="{{ route('imageWebinar',['id'=>$webinar->id,'ua'=>strtotime($webinar->updated_at)]) }}" class="col-12">
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <img src="{{ route('imageWebinarOrganizer',['id'=>$webinar->id,'ua'=>strtotime($webinar->updated_at)]) }}" class="col-12">
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
