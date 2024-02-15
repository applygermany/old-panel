@extends('admin.lyout')
@section('title')
    پنل مدیریت - نمایش رزومه
@endsection

@section('css')
@endsection

@section('content')
    <style>
        .card-title {
            display: flex;
            height: fit-content;
            width: 100%;
            justify-content: space-between;
        }
    </style>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                     data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                     class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">نمایش رزومه</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}">داشبورد</a></li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.resumes') }}">رزومه ها</a></li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-dark">نمایش رزومه</li>
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
                                        <img src="{{ route('imageResume',['id'=>$resume->id,'ua'=>strtotime($resume->user->updated_at)]) }}"
                                             alt="image">
                                    </div>
                                    <a href="#"
                                       class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">{{ $resume->user->name }}</a>
                                    <div class="fs-5 fw-bold text-gray-400 mb-6">{{ $resume->user->mobile }}</div>
                                    <div class="d-flex flex-wrap flex-center">
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span class="w-75px">{{ $resume->educationRecords()->count() }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">سوابق تحصیلی</div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span class="w-50px">{{ $resume->languages()->count() }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">دانش زبانی</div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span class="w-50px">{{ $resume->works()->count() }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">سوابق کاری</div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span class="w-50px">{{ $resume->softwareKnowledges()->count() }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">دانش نرم افزاری</div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span class="w-50px">{{ $resume->courses()->count() }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">دوره ها و مدارک</div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span class="w-50px">{{ $resume->researchs()->count() }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">سوابق پژوهشی ، افتخارات</div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                            <div class="fs-4 fw-bolder text-gray-700 text-center">
                                                <span class="w-50px">{{ $resume->hobbies()->count() }}</span>
                                            </div>
                                            <div class="fw-bold text-gray-400">تفریحات</div>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-title d-flex flex-stack fs-4 py-3">

                                    <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse"
                                         href="#kt_customer_view_details" role="button" aria-expوed="false"
                                         aria-controls="kt_customer_view_details">
                                        <span class="ms-2">جزییات</span>

                                    </div>
                                    <div class=" d-flex">
                                        <a onclick="step = 7" href="javascript:{}"
                                           data-url="{{ route('admin.editResume',['id'=>$resume->id]) }}"
                                           class="edit btn btn-primary btn-sm">ویرایش</a>
                                        <a href="{{ route('admin.previewResume',['id'=>$resume->id]) }}"
                                           class="btn btn-success btn-sm">نمایش</a>
                                        <a href="{{ route('admin.downloadResumeExcel',['id'=>$resume->id]) }}"
                                           class="btn btn-info btn-sm">خروجی اکسل</a>
                                    </div>


                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div id="kt_customer_view_details" class="collapse show">
                                    <div class="py-5 fs-6">
                                        <div class="fw-bolder mt-5">شماره سفارش</div>
                                        <div class="text-gray-600">{{ $resume->id }}</div>
                                        <div class="fw-bolder mt-5">نام قالب</div>
                                        <div class="text-gray-600">{{ $resume->theme }}</div>
                                        <div class="fw-bolder mt-5">کد رنگ</div>
                                        <div class="text-gray-600 d-flex" style="display: flex;">
                                            <div style="width: 25px;height: 25px;background-color: {{$resume->color}}"></div>
                                            <span>{{ $resume->color }}</span>
                                        </div>
                                        <div class="fw-bolder mt-5">نام</div>
                                        <div class="text-gray-600">{{ $resume->name }}</div>
                                        <div class="fw-bolder mt-5">نام خانوادگی</div>
                                        <div class="text-gray-600">{{ $resume->family }}</div>
                                        <div class="fw-bolder mt-5">زبان</div>
                                        <div class="text-gray-600">{{ $resume->language }}</div>
                                        <div class="fw-bolder mt-5">تاریخ تولد</div>
                                        <div class="text-gray-600">{{ $resume->birth_date }}</div>
                                        <div class="fw-bolder mt-5">محل تولد</div>
                                        <div class="text-gray-600">{{ $resume->birth_place }}</div>
                                        <div class="fw-bolder mt-5">شماره تماس</div>
                                        <div class="text-gray-600">{{ $resume->phone }}</div>
                                        <div class="fw-bolder mt-5">ایمیل</div>
                                        <div class="text-gray-600">{{ $resume->email }}</div>
                                        <div class="fw-bolder mt-5">آدرس</div>
                                        <div class="text-gray-600">{{ $resume->address }}</div>
                                        <div class="fw-bolder mt-5">شبکه های اجتماعی</div>
                                        <div class="text-gray-600">{{ $resume->socialmedia_links }}</div>
                                        <br>
                                        <hr/>
                                        <div class="fw-bolder mt-5">نگارنده را ثبت نمایید</div>
                                        <br/>
                                        <div>
                                            <form action="{{route('admin.addWriterToResume', ['id'=>$resume->id])}}"
                                                  method="post" class="row">
                                                @csrf
                                                <div class="col-12">
                                                    <div class="form-group float-label">
                                                        <label for="writer" class="header-label">نگارنده</label>
                                                        <select name="writer" class="form-control form-control-sm"
                                                                id="writer">
                                                            <option>انتخاب کنید</option>
                                                            @foreach($writers as $writer)
                                                                <option {{$resume->writer_id === $writer->id ? 'selected' : ''}} value="{{$writer->id}}">{{$writer->firstname}} {{$writer->lastname}}</option>
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
                                <a onclick="step = 0" class="nav-link text-active-primary pb-4 active"
                                   data-bs-toggle="tab" href="#educationRecords">سوابق تحصیلی</a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = 1" class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                   href="#languages">دانش زبانی</a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = 2" class="nav-link text-active-primary pb-4"
                                   data-kt-countup-tabs="true" data-bs-toggle="tab" href="#works">سوابق کاری</a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = 3" class="nav-link text-active-primary pb-4"
                                   data-kt-countup-tabs="true" data-bs-toggle="tab" href="#softwareKnowledges">دانش نرم
                                    افزاری</a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = 4" class="nav-link text-active-primary pb-4"
                                   data-kt-countup-tabs="true" data-bs-toggle="tab" href="#courses">دوره ها و مدارک</a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = 5" class="nav-link text-active-primary pb-4"
                                   data-kt-countup-tabs="true" data-bs-toggle="tab" href="#researchs">سوابق پژوهشی ، افتخارات</a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = 6" class="nav-link text-active-primary pb-4"
                                   data-kt-countup-tabs="true" data-bs-toggle="tab" href="#hobbies">تفریحات</a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = -2" class="nav-link text-active-primary pb-4"
                                   data-kt-countup-tabs="true" data-bs-toggle="tab" href="#extra">توضیحات بیشتر</a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = 7" class="nav-link text-active-primary pb-4"
                                   data-kt-countup-tabs="true" data-bs-toggle="tab" href="#admin_upload">آپلود
                                    رزومه </a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = 8" class="nav-link text-active-primary pb-4"
                                   data-kt-countup-tabs="true" data-bs-toggle="tab" href="#user_upload">آپلود از
                                    کاربر </a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = 8" class="nav-link text-active-primary pb-4"
                                   data-kt-countup-tabs="true" data-bs-toggle="tab" href="#writer_upload">آپلود از
                                    نگارنده </a>
                            </li>
                            <li class="nav-item">
                                <a onclick="step = 9" class="nav-link text-active-primary pb-4"
                                   data-kt-countup-tabs="true" data-bs-toggle="tab" href="#edit_resume">ویرایش</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="educationRecords" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>سوابق تحصیلی</h2>
                                            <a href="javascript:{}"
                                               data-url="{{ route('admin.editResume',['id'=>$resume->id]) }}"
                                               class="edit btn btn-info btn-sm">ویرایش</a>

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.resumes.educationRecordsList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="languages" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>دانش زبانی</h2>
                                            <a href="javascript:{}"
                                               data-url="{{ route('admin.editResume',['id'=>$resume->id]) }}"
                                               class="edit btn btn-info btn-sm">ویرایش</a>

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.resumes.languagesList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="works" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>سوابق کاری</h2>
                                            <a href="javascript:{}"
                                               data-url="{{ route('admin.editResume',['id'=>$resume->id]) }}"
                                               class="edit btn btn-info btn-sm">ویرایش</a>

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.resumes.worksList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="softwareKnowledges" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>دانش نرم افزاری</h2>
                                            <a href="javascript:{}"
                                               data-url="{{ route('admin.editResume',['id'=>$resume->id]) }}"
                                               class="edit btn btn-info btn-sm">ویرایش</a>

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.resumes.softwareKnowledgesList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="courses" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>دوره ها و مدارک</h2>
                                            <a href="javascript:{}"
                                               data-url="{{ route('admin.editResume',['id'=>$resume->id]) }}"
                                               class="edit btn btn-info btn-sm">ویرایش</a>

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.resumes.coursesList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="researchs" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>سوابق پژوهشی ، افتخارات</h2>
                                            <a href="javascript:{}"
                                               data-url="{{ route('admin.editResume',['id'=>$resume->id]) }}"
                                               class="edit btn btn-info btn-sm">ویرایش</a>

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.resumes.researchsList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="hobbies" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>تفریحات</h2>
                                            <a href="javascript:{}"
                                               data-url="{{ route('admin.editResume',['id'=>$resume->id]) }}"
                                               class="edit btn btn-info btn-sm">ویرایش</a>

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.resumes.hobbiesList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="admin_upload" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>آپلود رزومه</h2>

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.resumes.adminUpload')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="user_upload" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>آپلود کاربر</h2>

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.resumes.userUpload')
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
                                            @include('admin.resumes.writerUpload')
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
                                            @include('admin.resumes.extra')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="edit_resume" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>ویرایش</h2>
                                            <a href="javascript:{}"
                                               data-url="{{ route('admin.editResume',['id'=>$resume->id]) }}"
                                               class="edit btn btn-info btn-sm">درخواست ادیت</a>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            @include('admin.resumes.editSection')
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
    <div class="modal fade" tabindex="-1" id="editResume">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش رزومه </h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateResume">بروزرسانی</button>
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

    <div class="modal fade" tabindex="-1" id="deleteFileModal">
        <div class="modal-dialog modal-l">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">حذف فایل </h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <div class="modal-body">
                    آیا مایل به حذف فایل می باشید؟
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <a class="btn btn-warning" id="deleteFile">تایید</a>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ url('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>

        @if(session()->get('error'))
        $(document).ready(function () {
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
        $(document).ready(function () {
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

        function initTextarea() {
            var editor_config = {
                path_absolute: "/",
                selector: "textarea.editor",
                language: 'fa_IR',
                directionality: 'rtl',
                plugins: ["advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table directionality",
                    "template paste textpattern"],
                toolbar1: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
                toolbar2: "print preview media image | forecolor backcolor | blockquote",
                image_class_list: [
                    {title: 'None', value: ''},
                    {title: 'Img Responsive', value: 'img-responsive'},
                    {title: 'Img Rounded Responsive', value: 'img-responsive img-rounded'}
                ],
                relative_urls: false,
                file_browser_callback: function (field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

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
            $(document).on('click', '.delete', function (e) {
                if (confirm('از حذف این مورد مطمئن هستید ؟')) {
                    window.location.href = $(this).attr('href');
                } else {
                    e.preventDefault();
                }
            });
        }

        var step = 0;
        $(document).on('click', '.edit', function () {
            var url = $(this).data('url');
            $.ajax({
                url: url,

                data: {step: step},

                success: function (data) {
                    $('#editForm').html(data);
                    var editResume = new bootstrap.Modal(document.getElementById('editResume'), {
                        keyboard: false
                    });
                    editResume.show();
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
        $(document).on('click', '.deleteFileModal', function() {
            var url = $(this).data('url');
            var deleteFile = document.getElementById('deleteFile')
            deleteFile.href = url
            var deleteFileModal = new bootstrap.Modal(document.getElementById('deleteFileModal'), {
                keyboard: false
            });
            deleteFileModal.show();
        });
        $(document).on('click', '#updateResume', function () {
            var invalidFeedBacks = $(".invalid-feedback").map(function () {
                this.remove();
            }).get();
            $(".is-invalid").removeClass("is-invalid");
            var dis = $(this);
            data = new FormData($('#editForm')[0])
            data.set("text", tinyMCE.activeEditor.getContent() ?? "");

            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: "{{ route('admin.updateResume') }}",
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                success: function (data) {
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
