@extends('admin.lyout')

@section('title')
    پنل مدیریت - ورژن
@endsection

@section('css')

@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid " >
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">ورژن</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">ورژن</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid @if (!isset(auth()->user()->admin_permissions->version)) d-none @endif">
            <div class="container">
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1">
                                       ویرایش  ورژن
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <form action="{{ route('admin.editVersion') }}" method="post" class="row">
                                    @csrf
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="title" class="header-label">ورژن پنل کارشناس</label>
                                            <input type="text" name="support_version" class="form-control form-control-sm @if($errors->has('support_version')) is-invalid @endif" id="support_version"
                                            value="{{$support_version}}">
                                            @if($errors->has('support_version'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('support_version') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="title" class="header-label">ورژن پنل کاربر</label>
                                            <input type="text" name="user_version" class="form-control form-control-sm @if($errors->has('user_version')) is-invalid @endif" id="user_version"
                                                   value="{{$user_version}}">
                                            @if($errors->has('user_version'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('user_version') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="title" class="header-label">ورژن پنل نگارنده</label>
                                            <input type="text" name="writer_version" class="form-control form-control-sm @if($errors->has('writer_version')) is-invalid @endif" id="writer_version"
                                                   value="{{$writer_version}}">
                                            @if($errors->has('writer_version'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('writer_version') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-danger btn-sm col-12">ارسال</button>
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

@endsection