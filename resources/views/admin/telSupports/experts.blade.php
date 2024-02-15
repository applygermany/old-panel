@extends('admin.lyout')

@section('title')
    پنل مدیریت - کارشناسان
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
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">قالب های رزومه</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">کارشناسان</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="cityt d-flex flex-column-fluid">
            <div class="container">
                <div class="post d-flex flex-column-fluid">
                    <div class="container">
                        <div class="row g-5 g-xl-8">
                            <div class="col-12">
                                <div class="card card-xl-stretch mb-8">
                                    <div class="card-header border-0">
                                        <h3 class="card-title align-items-start flex-column">
                                            <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1">
                                               لیست
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="card-body py-3">
                                        @include('admin.telSupports.partials.expert-list')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection