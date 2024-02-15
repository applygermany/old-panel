@extends('admin.lyout')

@section('title')
پنل مدیریت - مبالغ
@endsection

@section('css')

@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">مبالغ</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">مبالغ</li>
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
                                        مبالغ را انتخاب کنید
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <form action="{{ route('admin.updatePrice') }}" method="post" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="euro_price" class="header-label">قیمت یورو(تومان)</label>
                                            <input type="text" name="euro_price" class="form-control form-control-sm @if($errors->has('euro_price')) is-invalid @endif" id="euro_price" value="{{ $pricing->euro_price }}">
                                            @if($errors->has('euro_price'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('euro_price') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="resume_price" class="header-label">رزومه </label>
                                            <input type="text" name="resume_price" class="form-control form-control-sm @if($errors->has('resume_price')) is-invalid @endif" id="resume_price" value="{{ $pricing->resume_price }}">
                                            @if($errors->has('resume_price'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('resume_price') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="resume_price" class="header-label">رزومه دو زبانه</label>
                                            <input type="text" name="resume_bi_price" class="form-control form-control-sm @if($errors->has('resume_bi_price')) is-invalid @endif" id="resume_bi_price" value="{{ $pricing->resume_bi_price }}">
                                            @if($errors->has('resume_bi_price'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('resume_price') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                    <div class="form-group float-label">
                                        <label for="motivation_price" class="header-label"> انگیزه نامه </label>
                                        <input type="text" name="motivation_price" class="form-control form-control-sm @if($errors->has('motivation_price')) is-invalid @endif" id="motivation_price" value="{{ $pricing->motivation_price }}">
                                        @if($errors->has('motivation_price'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('motivation_price') }}</small></div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group float-label">
                                        <label for="invite_action" class="header-label">تخفیف کد دعوت</label>
                                        <input type="text" name="invite_action" class="form-control form-control-sm @if($errors->has('invite_action')) is-invalid @endif" id="invite_action" value="{{ $pricing->invite_action }}">
                                        @if($errors->has('invite_action'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('invite_action') }}</small></div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group float-label">
                                        <label for="package_price" class="header-label">پکیج (یورو)</label>
                                        <input type="text" name="package_price" class="form-control form-control-sm @if($errors->has('package_price')) is-invalid @endif" id="package_price" value="{{ $pricing->package_price }}">
                                        @if($errors->has('package_price'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('package_price') }}</small></div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group float-label">
                                        <label for="package_price" class="header-label">پکیج دوم (یورو)</label>
                                        <input type="text" name="package_2_price" class="form-control form-control-sm @if($errors->has('package_2_price')) is-invalid @endif" id="package_2_price" value="{{ $pricing->package_2_price }}">
                                        @if($errors->has('package_2_price'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('package_2_price') }}</small></div>
                                        @endif
                                    </div>
                                </div>
                                 <div class="col-12 col-md-4">
                                    <div class="form-group float-label">
                                        <label for="tel_maximum_price" class="header-label">قیمت مشاوره تلفنی</label>
                                        <input type="text" name="tel_maximum_price" class="form-control form-control-sm @if($errors->has('tel_maximum_price')) is-invalid @endif" id="tel_maximum_price" value="{{ $pricing->tel_maximum_price }}">
                                        @if($errors->has('tel_maximum_price'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('tel_maximum_price') }}</small></div>
                                        @endif
                                    </div>
                                </div>
                                 <div class="col-12 col-md-8">
                                    <div class="form-group float-label">
                                        <label for="extra_university_price" class="header-label">قیمت اضافه کردن دانشگاه</label>
                                        <input type="text" name="extra_university_price" class="form-control form-control-sm @if($errors->has('extra_university_price')) is-invalid @endif" id="extra_university_price" value="{{ $pricing->extra_university_price }}">
                                        @if($errors->has('extra_university_price'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('extra_university_price') }}</small></div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group float-label">
                                        <label for="add_college_price" class="header-label">قیمت افرودن دانشگاه بیش از محدودیت (یورو)</label>
                                        <input type="number" name="add_college_price" class="form-control form-control-sm @if($errors->has('add_college_price')) is-invalid @endif" id="add_college_price" value="{{ $pricing->add_college_price }}">
                                        @if($errors->has('add_college_price'))
                                            <div class="invalid-feedback"><small>{{ $errors->first('add_college_price') }}</small></div>
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
