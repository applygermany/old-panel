@extends('admin.lyout') @section('title') پنل مدیریت - پروفایل ادمین @endsection @section('css') @endsection @section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">پروفایل ادمین</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted"> <a href="{{ route('admin.dashboard') }}">داشبورد</a> </li>
                        <li class="breadcrumb-item"> <span class="bullet bg-gray-200 w-5px h-2px"></span> </li>
                        <li class="breadcrumb-item text-muted"> <a href="{{ route('admin.admins') }}">ادمین ها</a> </li>
                        <li class="breadcrumb-item"> <span class="bullet bg-gray-200 w-5px h-2px"></span> </li>
                        <li class="breadcrumb-item text-dark">پروفایل ادمین</li>
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
                                        <img src="{{ route('imageUser',['id'=>$user->id,'ua'=>strtotime($user->updated_at)]) }}" alt="image">
                                    </div>
                                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">{{ $user->firstname }} {{ $user->lastname }}</a>
                                    <div class="fs-5 fw-bold text-gray-400 mb-6">{{ $user->mobile }}</div>
                                </div>
                                <div class="d-flex flex-stack fs-4 py-3">
                                    <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details" role="button" aria-expوed="false" aria-controls="kt_customer_view_details">
                                        <span class="ms-2">جزییات</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div id="kt_customer_view_details" class="collapse show">
                                    <div class="py-5 fs-6">
                                        <div class="fw-bolder mt-5">شناسه</div>
                                        <div class="text-gray-600">{{ $user->id }}</div>
                                        <div class="fw-bolder mt-5">ایمیل</div>
                                        <div class="text-gray-600">{{ $user->email }}</div>
                                        <div class="fw-bolder mt-5">تاریخ عضویت</div>
                                        <div class="text-gray-600">
                                            <?php
                                            $date = explode(' ',$user->created_at);
                                            $date = explode('-',$date[0]);
                                            $date = \App\Providers\JDF::gregorian_to_jalali($date[0],$date[1],$date[2],'/');
                                            echo $date;
                                            ?>
                                        </div>
                                        @if($user->supervisor)
                                            <div class="fw-bolder mt-5">کارشناس مربوطه</div>
                                            <div class="text-gray-600">
                                                <a href="#">{{ $user->supervisor->supervisor->firstname }} {{ $user->supervisor->supervisor->lastname }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-lg-row-fluid ms-lg-15">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#activeTel">مشاوره های فعال</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#pastTel">تاریخچه مشاوره</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab" href="#comments">نظرات کاربر</a>
                            </li>
                           
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane show active fade " onclick="changeTab('active-tel-supports')" id="activeTel" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>مشاوره های فعال</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="activeTelBox">
                                            @include('admin.admins.activeTelList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" onclick="changeTab('last-tel-supports')" id="pastTel" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>مشاوره های قبلی</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="pastTelBox">
                                            @include('admin.admins.pastTelList')
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="tab-pane fade" onclick="changeTab('comment')" id="comments" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>کامنت کاربران</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer" id="commentsBox">
                                            @include('admin.admins.commentList')
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
@endsection

@section('script')
    <script type="text/javascript">
        function changeTab(tab){
            alert(tab)
        }
    </script>
@endsection