@extends('admin.lyout')

@section('title')
پنل مدیریت - ویرایش فاز
@endsection

@section('css')

@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">ویرایش فاز</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.applyPhases') }}">فازهای اپلای</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">ویرایش فاز</li>
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
                                        ویرایش فاز
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <form action="{{ route('admin.updateApplyPhase') }}" method="post" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $applyPhase->id }}">
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="phase" class="header-label">فاز</label>
                                            <input type="text" name="phase" disabled class="form-control form-control-sm" id="phase" value="{{ $applyPhase->id }}">
                                            
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="form-group float-label">
                                            <label for="title" class="header-label">عنوان</label>
                                            <input type="text" name="title" class="form-control form-control-sm @if($errors->has('title')) is-invalid @endif" id="title" value="{{ $applyPhase->title }}">
                                            @if($errors->has('title'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('title') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-6">
                                        <div class="form-group float-label">
                                            <label for="description" class="header-label">توضیح</label>
                                            <input type="text" name="description" class="form-control form-control-sm @if($errors->has('description')) is-invalid @endif" id="description" placeholder="توضیح" value="{{ $applyPhase->description }}">
                                            @if($errors->has('description'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('description') }}</small></div>
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
