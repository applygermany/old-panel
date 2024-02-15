@extends('admin.lyout')
@section('title')
    پنل مدیریت - نمایش انگیزه نامه 
@endsection

@section('css')
<style>
    .card-title{
        display: flex;
        height: fit-content;
        width: 100%;
        justify-content: space-between;
    }
</style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">نمایش انگیزه نامه</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted"> <a href="{{ route('admin.dashboard') }}">داشبورد</a> </li>
                        <li class="breadcrumb-item"> <span class="bullet bg-gray-200 w-5px h-2px"></span> </li>
                        <li class="breadcrumb-item text-muted"> <a href="{{ route('admin.motivations') }}">انگیزه نامه ها</a> </li>
                        <li class="breadcrumb-item"> <span class="bullet bg-gray-200 w-5px h-2px"></span> </li>
                        <li class="breadcrumb-item text-dark">نمایش انگیزه نامه</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="d-flex flex-column flex-xl-row">
                    <div class="flex-column flex-lg-row-auto w-100 w-xl-400px mb-10">
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-body pt-15">
                                <div class="d-flex flex-center flex-column mb-5">
                                    <div class="symbol symbol-100px symbol-circle mb-7">
                                        <img src="{{ route('imageUser',['id'=>$motivation->user->id,'ua'=>strtotime($motivation->user->updated_at)]) }}" alt="image">
                                    </div>
                                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">{{ $motivation->user->fistname }} {{ $motivation->user->lastname }}</a>
                                    <div class="fs-5 fw-bold text-gray-400 mb-6">{{ $motivation->user->mobile }}</div>
                                    <div class="d-flex flex-wrap flex-center">
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span class="w-75px">{{ $motivation->universities()->count() }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">دانشگاه ها</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-stack fs-4 py-3">
                                    <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details" role="button" aria-expوed="false" aria-controls="kt_customer_view_details">
                                        <span class="ms-2">جزییات</span>
                                    </div>
                                    <a href="{{ route('admin.downloadMotivationExcel',['id'=>$motivation->id]) }}" class="btn btn-info btn-sm">خروجی اکسل</a>
                                    <a href="{{ route('admin.showMotivationPreview',['id'=>$motivation->id]) }}" class="btn btn-info btn-sm">نمایش</a>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div id="kt_customer_view_details" class="collapse show">
                                    <div class="py-5 fs-6">
                                        <div class="fw-bolder mt-5">شماره سفارش</div>
                                        <div class="text-gray-600">{{ $motivation->id }}</div>
                                        <div class="fw-bolder mt-5">نام</div>
                                        <div class="text-gray-600">{{ $motivation->name }}</div>
                                        <div class="fw-bolder mt-5">نام خانوادگی</div>
                                        <div class="text-gray-600">{{ $motivation->family }}</div>
                                        <div class="fw-bolder mt-5">تاریخ تولد</div>
                                        <div class="text-gray-600">{{ $motivation->birth_date }}</div>
                                        <div class="fw-bolder mt-5">محل تولد</div>
                                        <div class="text-gray-600">{{ $motivation->birth_place }}</div>
                                        <div class="fw-bolder mt-5">شماره تماس</div>
                                        <div class="text-gray-600">{{ $motivation->phone }}</div>
                                        <div class="fw-bolder mt-5">ایمیل</div>
                                        <div class="text-gray-600">{{ $motivation->email }}</div>
                                        <div class="fw-bolder mt-5">آدرس</div>
                                        <div class="text-gray-600">{{ $motivation->address }}</div>
                                        <br>
                                        <hr/>
                                        <div class="fw-bolder mt-5">نگارنده را ثبت نمایید</div>
                                        <br/>
                                        <div>
                                            <form action="{{route('admin.addWriterToMotivation', ['id'=>$motivation->id])}}"
                                                  method="post" class="row">
                                                @csrf
                                                <div class="col-12">
                                                    <div class="form-group float-label">
                                                        <label for="writer" class="header-label">نگارنده</label>
                                                        <select name="writer" class="form-control form-control-sm"
                                                                id="writer">
                                                            <option>انتخاب کنید</option>
                                                            @foreach($writers as $writer)
                                                                <option {{$motivation->writer_id === $writer->id ? 'selected' : ''}} value="{{$writer->id}}">{{$writer->firstname}} {{$writer->lastname}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-success btn-sm col-12">ثبت
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-lg-row-fluid ms-lg-15">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#details">جزئیات</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#universities">دانشگاه ها</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#extra">توضیحات بیشتر</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#edit_motivation">ویرایش</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#admin_upload">آپلود انگیزه نامه</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#user_upload">آپلود از کاربر</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#writer_upload">آپلود از نگارنده</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#user_resume">رزومه کاربر</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="details" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>جزئیات</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    برای :
                                                    @if($motivation->to == 1)
                                                        سفارت
                                                    @else
                                                        دانشگاه
                                                    @endif
                                                </div>
                                                <div class="col-6 mb-2">
                                                    کشور :
                                                    @if($motivation->country == 1)
                                                        ایران
                                                    @else
                                                        کشور های دیگر
                                                    @endif
                                                </div>
                                                <hr>
                                                <div class="col-12 my-2">
                                                    درباره : <br>
                                                    {{ $motivation->about }}
                                                </div>
                                                <hr>
                                                <div class="col-12 my-2">
                                                    رزومه : <br>
                                                    {{ $motivation->resume }}
                                                </div>
                                                <hr>
                                                <div class="col-12 my-2">
                                                    چرا آلمان : <br>
                                                    {{ $motivation->why_germany }}
                                                </div>
                                                <hr>
                                                <div class="col-12 my-2">
                                                    برنامه پس از فارغ التحصیلی : <br>
                                                    {{ $motivation->after_graduation }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="universities" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>دانشگاه ها</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.motivations.universitiesList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="edit_motivation" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>ویرایش</h2>
                                            <a href="javascript:{}" data-url="{{ route('admin.editMotivation',['id'=>$motivation->id]) }}" class="edit btn btn-info btn-sm">درخواست ادیت</a>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.motivations.editSection')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="admin_upload" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>آپلود انگیزه نامه</h2>
                                            
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.motivations.adminUpload')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="extra" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>توضیحات</h2>
                                           
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.motivations.extra')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="user_upload" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>آپلود از کاربر</h2>
                                           
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.motivations.userUpload')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="writer_upload" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>آپلود از نگارنده</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.motivations.writerUpload')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="user_resume" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>رزومه کاربر</h2>

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.motivations.userResume')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editMotivation">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش انگیزه نامه </h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateMotivation">بروزرسانی</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="acceptFileModal">
        <div class="modal-dialog modal-l">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تایید فایل </h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <div class="modal-body">
                    آیا مایل به تایید فایل می باشید؟
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <a class="btn btn-warning" id="acceptFile">تایید</a>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
<script src="{{ url('assets/plugins/tinymce/tinymce.min.js') }}"></script>

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

     function initTextarea(){
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
       
    }
    $(document).on('click', '.edit', function() {
        var url = $(this).data('url');
        $.ajax({
            url: url,
         
            data: {step: 1},
   
            success: function(data) {
                $('#editForm').html(data);
                var editMotivation = new bootstrap.Modal(document.getElementById('editMotivation'), {
                    keyboard: false
                });
                editMotivation.show();
                initTextarea()
            }
        });
    });
    $(document).on('click', '.acceptFileModal', function() {
        var url = $(this).data('url');
        var acceptFile = document.getElementById('acceptFile')
        acceptFile.href = url
        var acceptFileModal = new bootstrap.Modal(document.getElementById('acceptFileModal'), {
            keyboard: false
        });
        acceptFileModal.show();
    });
    $(document).on('click', '#updateMotivation', function() {
        var invalidFeedBacks = $(".invalid-feedback").map(function() {
            this.remove();
        }).get();
        $(".is-invalid").removeClass("is-invalid");
        var dis = $(this);
        data = new FormData($('#editForm')[0])
        data.set("text", tinyMCE.activeEditor.getContent() ?? "");

        dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        $.ajax({
            url: "{{ route('admin.updateMotivation') }}",
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                dis.html('بروزرسانی');

                Lobibox.notify(data.type, {
                    title: "",
                    msg: data.msg,
                    icon: 'fa fa-' + data.type,
                    position: 'bottom left',
                    sound: false,
                    mouse_over: "pause"
                });


            }
        });
    });
</script>
@endsection
