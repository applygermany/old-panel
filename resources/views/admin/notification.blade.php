@extends('admin.lyout')

@section('title')
    پنل مدیریت - ارسال نوتیفیکیشن
@endsection

@section('css')
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid ">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                    data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">ارسال نوتیفیکیشن</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">ارسال نوتیفیکیشن</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid @if (!isset(auth()->user()->admin_permissions->notification)) d-none @endif">
            <div class="container">
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1">
                                        ارسال نوتیفیکیشن
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <form action="{{ route('admin.sendNotification') }}" method="post" class="row">
                                    @csrf
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="title" class="header-label">عنوان</label>
                                            <input type="text" name="title"
                                                class="form-control form-control-sm @if ($errors->has('title')) is-invalid @endif"
                                                id="title">
                                            @if ($errors->has('title'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('title') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="sendType" class="header-label">نوع ارسال</label>
                                            <select name="sendType" id="sendType" class="form-control form-select-sm"
                                                data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                <option value="hasContract">کسانی که قرارداد دارند </option>
                                                <option value="users">کل کاربران </option>
                                                <option value="user"> کاربر </option>
                                                <option value="normalUsers">کاربران عادی</option>
                                                <option value="baseUsers">کاربران پایه</option>
                                                <option value="specialUsers">کاربران ویژه</option>
                                                <option value="hasTelSupport">کاربرانی که تایم مشاوره دارند</option>
                                                <option value="userComments">نظرات کاربران</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 users" style="display: none">
                                        <div class="form-group float-label">
                                            <label for="searchUser" class="header-label">کاربر</label>
                                            <select name="searchUser" id="searchUser" class="form-control form-select-sm"
                                                data-control="select2">
                                                <option value="">انتخاب کنید</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->firstname }}
                                                        {{ $user->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group float-label">
                                            <label for="text" class="header-label">متن</label>
                                            <textarea name="text" class="form-control @if ($errors->has('text')) is-invalid @endif" id="text"
                                                placeholder="متن"></textarea>
                                            @if ($errors->has('text'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('text') }}</small>
                                                </div>
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
    <script>
        $('#sendType').change(function() {
            if ($(this).val() === 'user') {
                $(".users").attr('style', '')
            } else {
                $(".users").attr('style', 'display:none')
            }
        })
    </script>
@endsection
