@extends('admin.lyout')@section('title')    پنل مدیریت - نظر سنجی ها@endsection@section('css')@endsection@section('content')    <div class="content d-flex flex-column flex-column-fluid">        <div class="toolbar">            <div class="container-fluid d-flex flex-stack">                <div data-kt-place="true" data-kt-place-mode="prepend"                     data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"                     class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">نظر سنجی ها</h1>                    <span class="h-20px border-gray-200 border-start mx-4"></span>                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">                        <li class="breadcrumb-item text-muted">                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>                        </li>                        <li class="breadcrumb-item">                            <span class="bullet bg-gray-200 w-5px h-2px"></span>                        </li>                        <li class="breadcrumb-item text-dark">نظر سنجی ها</li>                    </ul>                </div>            </div>        </div>        <div class="fieldt d-flex flex-column-fluid">            <div class="container">                <div class="row g-5 g-xl-8">                    <div class="col-12">                        <div class="card card-xl-stretch mb-8">                            <div class="card-header border-0">                                <h3 class="card-title align-items-start flex-column">                                    <span class="card-label fw-bolder fs-3 mb-1">اطلاعات</span>                                </h3>                            </div>                            <div class="card-body py-3">                                <table style="width: 100%">                                    <thead>                                    <tr>                                        <th style="text-align: right">                                            عنوان سوال                                        </th>                                        <th style="text-align: center">                                            پاسخ                                        </th>                                    </tr>                                    </thead>                                    <tbody>                                    @foreach($answers as $value)                                        <tr>                                            <td style="text-align: right">                                                {{$value->title}}                                            </td>                                            <td style="text-align: center">                                                {{$value->answer}}                                            </td>                                        </tr>                                    @endforeach                                    </tbody>                                </table>                            </div>                        </div>                    </div>                    <div class="col-12">                        <div class="card card-xl-stretch mb-8">                            <div class="card-header border-0">                                <h3 class="card-title align-items-start flex-column">                                    <span class="card-label fw-bolder fs-3 mb-1">زمینه های مورد تمایل برای همکاری</span>                                </h3>                            </div>                            <div class="card-body py-3">                                <ul>                                    @foreach($types as $value)                                        <li>{{$value}}</li>                                    @endforeach                                </ul>                            </div>                        </div>                    </div>                    <div class="col-12">                        <div class="card card-xl-stretch mb-8">                            <div class="card-header border-0">                                <h3 class="card-title align-items-start flex-column">                                    <span class="card-label fw-bolder fs-3 mb-1">                                        در چند جمله نظرتون راجع به همکاری با اپلای جرمنی را بیان کنید. این نظــر .در صفحه اصلی منتشر                            خواهد شد                                    </span>                                </h3>                            </div>                            <div class="card-body py-3">                                {{$vote->comment}}                            </div>                        </div>                    </div>                    <div class="col-12">                        <div class="card card-xl-stretch mb-8">                            <div class="card-header border-0">                                <h3 class="card-title align-items-start flex-column">                                    <span class="card-label fw-bolder fs-3 mb-1">                                        اگر انتقاد یا پیشنهادی داریــد آن را به ما بگویید. ما حتما پیشنهـــادات شما را بررسی                            می‌کنیم. (اختیــاری)                                    </span>                                </h3>                            </div>                            <div class="card-body py-3">                                {{$vote->recommend}}                            </div>                        </div>                    </div>                </div>            </div>        </div>    </div>    <div class="modal fade" tabindex="-1" id="editAccepted">        <div class="modal-dialog modal-xl">            <div class="modal-content">                <div class="modal-header">                    <h5 class="modal-title">ویرایش پذیرفته شده</h5>                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"                         aria-label="Close">                        <span class="fa fa-window-close fa-2x text-danger"></span>                    </div>                </div>                <form class="modal-body" id="editForm">                </form>                <div class="modal-footer">                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>                    <button type="button" class="btn btn-warning" id="updateAccepted">بروزرسانی</button>                </div>            </div>        </div>    </div>@endsection@section('script')    <script>    </script>@endsection