@extends('admin.lyout')

@section('title')
    پنل مدیریت - دانشگاه ها
@endsection

@section('css')

@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">دانشگاه ها</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">دانشگاه ها</li>
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
                                    <a href="javascript:void(0)" class="card-label fw-bolder fs-3 mb-1"
                                       data-bs-toggle="collapse" data-bs-target="#collapseUniversity" aria-expanded="false" aria-controls="collapseExample">
                                        دانشگاه جدید
                                    </a>
                                </h3>
                            </div>
                            <div class="card-body collapse py-3" id="collapseUniversity">
                                <form action="{{ route('admin.saveUniversity') }}" method="post" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="title" class="header-label">عنوان</label>
                                            <input type="text" name="title" class="form-control form-control-sm @if($errors->has('title')) is-invalid @endif" id="title" placeholder="عنوان" value="{{ old('title') }}">
                                            @if($errors->has('title'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('title') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="city" class="header-label">شهر</label>
                                            <input type="text" name="city" class="form-control form-control-sm @if($errors->has('city')) is-invalid @endif" id="city" placeholder="شهر" value="{{ old('city') }}">
                                            @if($errors->has('city'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('city') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="state" class="header-label">استان</label>
                                            <input type="text" name="state" class="form-control form-control-sm @if($errors->has('state')) is-invalid @endif" id="state" placeholder="استان" value="{{ old('state') }}">
                                            @if($errors->has('state'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('state') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="geographicalLocation" class="header-label">مکان جغرافیایی</label>
                                            <input type="text" name="geographicalLocation" class="form-control form-control-sm @if($errors->has('geographicalLocation')) is-invalid @endif" id="geographicalLocation" placeholder="متن مکان جغرافیایی" value="{{ old('geographicalLocation') }}">
                                            @if($errors->has('geographicalLocation'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('geographicalLocation') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="cityCrowd" class="header-label">جمعیت شهر</label>
                                            <input type="text" name="cityCrowd" class="form-control form-control-sm @if($errors->has('cityCrowd')) is-invalid @endif" id="cityCrowd" placeholder="جمعیت شهر" value="{{ old('cityCrowd') }}">
                                            @if($errors->has('cityCrowd'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('cityCrowd') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="state" class="header-label">هزینه زندگی</label>
                                            <input type="text" name="costLiving" class="form-control form-control-sm @if($errors->has('costLiving')) is-invalid @endif" id="costLiving" placeholder="هزینه زندگی" value="{{ old('costLiving') }}">
                                            @if($errors->has('costLiving'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('costLiving') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="image" class="header-label">تصویر</label>
                                            <input type="file" name="image" class="form-control form-control-sm @if($errors->has('image')) is-invalid @endif" id="image" value="{{ old('image') }}">
                                            @if($errors->has('image'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('image') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="logo" class="header-label">لوگو</label>
                                            <input type="file" name="logo" class="form-control form-control-sm @if($errors->has('logo')) is-invalid @endif" id="logo" value="{{ old('logo') }}">
                                            @if($errors->has('logo'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('logo') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group float-label">
                                            <label for="logo_acceptance" class="header-label">لوگوی پذیرفته شدگان</label>
                                            <input type="file" name="logo_acceptance" class="form-control form-control-sm @if($errors->has('logo_acceptance')) is-invalid @endif" id="logo_acceptance" value="{{ old('logo_acceptance') }}">
                                            @if($errors->has('logo_acceptance'))
                                                <div class="invalid-feedback"><small>{{ $errors->first('logo_acceptance') }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-success btn-sm col-12">ثبت</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">جستجو</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="searchTitle" class="header-label">عنوان</label>
                                            <input type="text" name="searchTitle" class="form-control form-control-sm" id="searchTitle" placeholder="عنوان">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="searchCity" class="header-label">شهر</label>
                                            <input type="text" name="searchCity" class="form-control form-control-sm" id="searchCity" placeholder="شهر">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="searchState" class="header-label">استان</label>
                                            <input type="text" name="searchState" class="form-control form-control-sm" id="searchState" placeholder="استان">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="searchCost" class="header-label">هزینه زندگی</label>
                                            <select class="form-control form-select-sm" name="searchCost" id="searchCost">
                                                <option value="-1">انتخاب کنید</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group float-label">
                                            <label for="searchGeo" class="header-label">مکان جغرافیایی</label>
                                            <select class="form-control form-select-sm" name="searchGeo" id="searchGeo">
                                                <option value="-1">انتخاب کنید</option>
                                                <option value="West">West</option>
                                                <option value="East">East</option>
                                                <option value="North">North</option>
                                                <option value="South">South</option>
                                                <option value="Center">Center</option>
                                                <option value="South East">South East</option>
                                                <option value="South West">South West</option>
                                                <option value="North West">North West</option>
                                                <option value="North East">North East</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="button" id="search" class="btn btn-primary btn-sm col-12">جستجو</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-8">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">لیست دانشگاه ها</span>
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <div id="tableBox">
                                    @include('admin.universities.list')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editUniversity">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش دانشگاه</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <form class="modal-body" id="editForm">

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-warning" id="updateUniversity">بروزرسانی</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click','.delete',function(e){
            if(confirm('از حذف این مورد مطمئن هستید ؟')) {
                window.location.href = $(this).attr('href');
            } else {
                e.preventDefault();
            }
        });
        $(document).on('click','.pagination a',function(e){
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getUniversities(page);
        });
        function getUniversities(page) {
            $('#tableBox').html('<div class="d-flex justify-content-center"><div class="spinner-border"></div></div>');
            $.ajax({
                url: '{{ route('admin.getUniversities') }}'+"?page="+page,
                type: "POST",
                data: { _token : '{{ csrf_token() }}',                    
                searchCost : $('#searchCost').val(),
                searchGeo : $('#searchGeo').val(),    searchCost : $('#searchCost').val(),
                            searchGeo : $('#searchGeo').val(),searchTitle : $('#searchTitle').val(), searchCity : $('#searchCity').val(), searchState : $('#searchState').val()},
                success: function(data){
                    $('#tableBox').html(data);
                }
            });
        }
        $(document).on('click','#search',function() {
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.getUniversities') }}',
                type: "POST",
                data: { _token : '{{ csrf_token() }}',                   
                searchCost : $('#searchCost').val(),
                searchGeo : $('#searchGeo').val(),     searchCost : $('#searchCost').val(),
                            searchGeo : $('#searchGeo').val(),searchTitle : $('#searchTitle').val(), searchCity : $('#searchCity').val(), searchState : $('#searchState').val()},
                success: function(data){
                    dis.html('جستجو');
                    $('#tableBox').html(data);
                }
            });
        });
        $(document).on('click','.edit',function(){
            var url = $(this).data('url');
            $.ajax({
                url: url,
                success: function(data) {
                    $('#editForm').html(data);
                    var editUniversity = new bootstrap.Modal(document.getElementById('editUniversity'), { keyboard: false });
                    editUniversity.show();
                }
            });
        });
        $(document).on('click','#updateUniversity',function(){
            var invalidFeedBacks = $(".invalid-feedback").map(function() {
                this.remove();
            }).get();
            $(".is-invalid").removeClass("is-invalid");
            var dis = $(this);
            dis.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: '{{ route('admin.updateUniversity') }}',
                type: "POST",
                data: new FormData($('#editForm')[0]),
                processData: false,
                contentType: false,
                success: function(data) {
                    dis.html('بروزرسانی');
                    if(data == 1)
                    {
                        $.ajax({
                            url: '{{ route('admin.getUniversities') }}',
                            type: "POST",
                            data: { _token : '{{ csrf_token() }}', searchTitle : $('#searchTitle').val(), 
                            searchCity : $('#searchCity').val(),
                            searchCost : $('#searchCost').val(),
                            searchGeo : $('#searchGeo').val(),
                             searchState : $('#searchState').val()},
                            success: function(data){
                                $('#tableBox').html(data);
                            }
                        });
                        Lobibox.notify('success', {
                            title: " عملیات موفق : ",
                            msg: "اطلاعات با موفقیت ویرایش شد",
                            icon: 'fa fa-success',
                            cityition: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                    else if(data == 2)
                    {
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "خطا در بروزرسانی اطلاعات",
                            icon: 'fa fa-warning',
                            cityition: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                    else {
                        $.each( data['errors'], function( key, value ) {
                            var el = $('#'+key);
                            el.addClass('is-invalid');
                            el.parent().append('<div class="invalid-feedback"><strong>'+value+'</strong></div>');
                        });
                        Lobibox.notify('error', {
                            title: " عملیات نا موفق : ",
                            msg: "خطا در ویرایش اطلاعات",
                            icon: 'fa fa-warning',
                            cityition: 'bottom left',
                            sound: false,
                            mouse_over: "pause"
                        });
                    }
                }
            });
        });
    </script>
@endsection