@extends('admin.lyout')

@section('title')
    پنل مدیریت - ویرایش اخذ پذیرش
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                     data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                     class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">کاربران</h1>
                    <span class="h-20px border-gray-200 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}">داشبورد</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.users') }}">کاربران</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.userProfile', ['id'=>$acceptance->user->id]) }}">{{$acceptance->user->firstname}} {{$acceptance->user->lastname}}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">ویرایش اخذ پذیرش</li>
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
                                    ویرایش اخذ پذیرش
                                </h3>
                            </div>
                            <div class="card-body py-3">
                                <form action="{{ route('admin.saveUserAcceptance', ['id'=>$acceptance->id]) }}"
                                      method="post" class="row">
                                    @csrf

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="firstname" class="header-label">نام</label>
                                                <input type="text" name="firstname" class="form-control form-control-sm @if($errors->has('firstname')) is-invalid @endif" id="firstname" placeholder="نام" value="{{ $acceptance->firstname }}">
                                                @if($errors->has('firstname'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('firstname') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="lastname" class="header-label">نام خانوادگی</label>
                                                <input type="text" name="lastname" class="form-control form-control-sm @if($errors->has('lastname')) is-invalid @endif" id="lastname" placeholder="نام خانوادگی" value="{{ $acceptance->lastname }}">
                                                @if($errors->has('lastname'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('lastname') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="mobile" class="header-label">موبایل</label>
                                                <input type="text" name="mobile" class="form-control form-control-sm @if($errors->has('mobile')) is-invalid @endif" id="mobile" placeholder="موبایل" value="{{ $acceptance->phone }}">
                                                @if($errors->has('mobile'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('mobile') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="birth_date" class="header-label">تاریخ تولد</label>
                                                <input type="text" name="birth_date" class="form-control form-control-sm @if($errors->has('birth_date')) is-invalid @endif" id="birth_date" placeholder="تاریخ تولد" value="{{ $acceptance->birth_date }}">
                                                @if($errors->has('birth_date'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('birth_date') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="embassy_appointment" class="header-label">درخواست وقت سفارت</label>
                                                <input type="text" name="embassy_appointment" class="form-control form-control-sm @if($errors->has('embassy_appointment')) is-invalid @endif" id="embassy_appointment" placeholder="درخواست وقت سفارت" value="{{ $acceptance->embassy_appointment }}">
                                                @if($errors->has('embassy_appointment'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('embassy_appointment') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="embassy_date" class="header-label">تاریخ وقت سفارت</label>
                                                <input type="text" name="embassy_date" class="form-control form-control-sm @if($errors->has('embassy_date')) is-invalid @endif" id="embassy_date" placeholder="تاریخ وقت سفارت" value="{{ $acceptance->embassy_date }}">
                                                @if($errors->has('embassy_date'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('embassy_date') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="admittance" class="header-label">تقاضای اخذ پذیریش مدرک</label>
                                                <input type="text" name="admittance" class="form-control form-control-sm @if($errors->has('admittance')) is-invalid @endif" id="admittance" placeholder="تقاضای اخذ پذیریش مدرک" value="{{ $acceptance->admittance }}">
                                                @if($errors->has('admittance'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('admittance') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="diploma_grade_average" class="header-label">معدل دیپلم</label>
                                                <input type="text" name="diploma_grade_average" class="form-control form-control-sm @if($errors->has('diploma_grade_average')) is-invalid @endif" id="diploma_grade_average" placeholder="معدل دیپلم" value="{{ $acceptance->diploma_grade_average }}">
                                                @if($errors->has('diploma_grade_average'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('diploma_grade_average') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="pre_university_grade_average" class="header-label">معدل پیش دانشگاهی</label>
                                                <input type="text" name="pre_university_grade_average" class="form-control form-control-sm @if($errors->has('pre_university_grade_average')) is-invalid @endif" id="pre_university_grade_average" placeholder="معدل پیش دانشگاهی" value="{{ $acceptance->pre_university_grade_average }}">
                                                @if($errors->has('pre_university_grade_average'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('pre_university_grade_average') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="field_grade" class="header-label">رشته دیپلم</label>
                                                <input type="text" name="field_grade" class="form-control form-control-sm @if($errors->has('field_grade')) is-invalid @endif" id="field_grade" placeholder="رشته دیپلم" value="{{ $acceptance->field_grade }}">
                                                @if($errors->has('field_grade'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('field_grade') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="is_license_semesters" class="header-label">در مقطع لیسانس تحصیل کرده اید</label>
                                                <input type="text" name="is_license_semesters" class="form-control form-control-sm @if($errors->has('is_license_semesters')) is-invalid @endif" id="is_license_semesters" placeholder="در مقطع لیسانس تحصیل کرده اید" value="{{ $acceptance->is_license_semesters }}">
                                                @if($errors->has('is_license_semesters'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('is_license_semesters') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="field_license" class="header-label">رشته لیسانس</label>
                                                <input type="text" name="field_license" class="form-control form-control-sm @if($errors->has('field_license')) is-invalid @endif" id="field_license" placeholder="رشته لیسانس" value="{{ $acceptance->field_license }}">
                                                @if($errors->has('field_license'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('field_license') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="university_license" class="header-label">نام دانشگاه</label>
                                                <input type="text" name="university_license" class="form-control form-control-sm @if($errors->has('university_license')) is-invalid @endif" id="university_license" placeholder="نام دانشگاه" value="{{ $acceptance->university_license }}">
                                                @if($errors->has('university_license'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('university_license') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="license_graduated" class="header-label">لیسانس فارغ تحصیل شده اید</label>
                                                <input type="text" name="license_graduated" class="form-control form-control-sm @if($errors->has('license_graduated')) is-invalid @endif" id="license_graduated" placeholder="لیسانس فارغ تحصیل شده اید" value="{{ $acceptance->license_graduated }}">
                                                @if($errors->has('license_graduated'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('license_graduated') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="average_license" class="header-label">معدل لیسانسد</label>
                                                <input type="text" name="average_license" class="form-control form-control-sm @if($errors->has('average_license')) is-invalid @endif" id="average_license" placeholder="معدل لیسانس" value="{{ $acceptance->average_license }}">
                                                @if($errors->has('average_license'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('average_license') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="year_license" class="header-label">سال فارغ تحصیلی</label>
                                                <input type="text" name="year_license" class="form-control form-control-sm @if($errors->has('year_license')) is-invalid @endif" id="year_license" placeholder="سال فارغ تحصیلی" value="{{ $acceptance->year_license }}">
                                                @if($errors->has('year_license'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('year_license') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="total_number_passes" class="header-label">تعداد واحد پاس شده</label>
                                                <input type="text" name="total_number_passes" class="form-control form-control-sm @if($errors->has('total_number_passes')) is-invalid @endif" id="total_number_passes" placeholder="تعداد واحد پاس شده" value="{{ $acceptance->total_number_passes }}">
                                                @if($errors->has('total_number_passes'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('total_number_passes') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="Pass_30_units" class="header-label">30 واحد پاس شده</label>
                                                <input type="text" name="Pass_30_units" class="form-control form-control-sm @if($errors->has('Pass_30_units')) is-invalid @endif" id="Pass_30_units" placeholder="30 واحد پاس شده" value="{{ $acceptance->Pass_30_units }}">
                                                @if($errors->has('Pass_30_units'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('Pass_30_units') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="senior_educate" class="header-label">ارشد</label>
                                                <input type="text" name="senior_educate" class="form-control form-control-sm @if($errors->has('senior_educate')) is-invalid @endif" id="senior_educate" placeholder="ارشد" value="{{ $acceptance->senior_educate }}">
                                                @if($errors->has('senior_educate'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('senior_educate') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="field_senior" class="header-label">رشته ارشد</label>
                                                <input type="text" name="field_senior" class="form-control form-control-sm @if($errors->has('field_senior')) is-invalid @endif" id="field_senior" placeholder="رشته ارشد" value="{{ $acceptance->field_senior }}">
                                                @if($errors->has('field_senior'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('field_senior') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="university_senior" class="header-label">نام دانشگاه ارشد</label>
                                                <input type="text" name="university_senior" class="form-control form-control-sm @if($errors->has('university_senior')) is-invalid @endif" id="university_senior" placeholder="نام دانشگاه ارشد" value="{{ $acceptance->university_senior }}">
                                                @if($errors->has('university_senior'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('university_senior') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="average_senior" class="header-label">معدل مقطع ارشد</label>
                                                <input type="text" name="average_senior" class="form-control form-control-sm @if($errors->has('average_senior')) is-invalid @endif" id="average_senior" placeholder="معدل مقطع ارشد" value="{{ $acceptance->average_senior }}">
                                                @if($errors->has('average_senior'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('average_senior') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="year_senior" class="header-label">سال فارغ تحصیلی ارشد</label>
                                                <input type="text" name="year_senior" class="form-control form-control-sm @if($errors->has('year_senior')) is-invalid @endif" id="year_senior" placeholder="سال فارغ تحصیلی ارشد" value="{{ $acceptance->year_senior }}">
                                                @if($errors->has('year_senior'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('year_senior') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="another_educate" class="header-label">تحصیلات دیگه</label>
                                                <input type="text" name="another_educate" class="form-control form-control-sm @if($errors->has('another_educate')) is-invalid @endif" id="another_educate" placeholder="تحصیلات دیگه" value="{{ $acceptance->another_educate }}">
                                                @if($errors->has('another_educate'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('another_educate') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="military_service" class="header-label">وضعیت خدمت</label>
                                                <input type="text" name="military_service" class="form-control form-control-sm @if($errors->has('military_service')) is-invalid @endif" id="military_service" placeholder="وضعیت خدمت" value="{{ $acceptance->military_service }}">
                                                @if($errors->has('military_service'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('military_service') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="language_favor" class="header-label">علاقمند به زبان</label>
                                                <input type="text" name="language_favor" class="form-control form-control-sm @if($errors->has('language_favor')) is-invalid @endif" id="language_favor" placeholder="علاقمند به زبان" value="{{ $acceptance->language_favor }}">
                                                @if($errors->has('language_favor'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('language_favor') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="license_language" class="header-label">مدرک زبان</label>
                                                <input type="text" name="license_language" class="form-control form-control-sm @if($errors->has('license_language')) is-invalid @endif" id="license_language" placeholder="مدرک زبان" value="{{ $acceptance->license_language }}">
                                                @if($errors->has('license_language'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('license_language') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="what_grade_language" class="header-label">نوع مدرک زبان</label>
                                                <input type="text" name="what_grade_language" class="form-control form-control-sm @if($errors->has('what_grade_language')) is-invalid @endif" id="what_grade_language" placeholder="نوع مدرک زبان" value="{{ $acceptance->what_grade_language }}">
                                                @if($errors->has('what_grade_language'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('what_grade_language') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="what_intent_grade_language" class="header-label">قصد گرفتن چه مدرک زبان</label>
                                                <input type="text" name="what_intent_grade_language" class="form-control form-control-sm @if($errors->has('what_intent_grade_language')) is-invalid @endif" id="what_intent_grade_language" placeholder="قصد گرفتن چه مدرک زبان" value="{{ $acceptance->what_intent_grade_language }}">
                                                @if($errors->has('what_intent_grade_language'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('what_intent_grade_language') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="date_intent_grade_language" class="header-label">قصد گرفتن مدرک زبان در تاریخ</label>
                                                <input type="text" name="date_intent_grade_language" class="form-control form-control-sm @if($errors->has('date_intent_grade_language')) is-invalid @endif" id="date_intent_grade_language" placeholder="قصد گرفتن مدرک زبان در تاریخ" value="{{ $acceptance->date_intent_grade_language }}">
                                                @if($errors->has('date_intent_grade_language'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('date_intent_grade_language') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="doc_translate" class="header-label">مدارک ترجمه رسمی ؟</label>
                                                <input type="text" name="doc_translate" class="form-control form-control-sm @if($errors->has('doc_translate')) is-invalid @endif" id="doc_translate" placeholder="مدارک ترجمه رسمی ؟" value="{{ $acceptance->doc_translate }}">
                                                @if($errors->has('doc_translate'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('doc_translate') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="doc_embassy" class="header-label">مدارک تایید سفارت</label>
                                                <input type="text" name="doc_translate" class="form-control form-control-sm @if($errors->has('doc_embassy')) is-invalid @endif" id="doc_embassy" placeholder="مدارک تایید سفارت" value="{{ $acceptance->doc_embassy }}">
                                                @if($errors->has('doc_embassy'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('doc_embassy') }}</small></div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-group float-label">
                                                <label for="description" class="header-label">توضیح اضافی</label>
                                                <input type="text" name="description" class="form-control form-control-sm @if($errors->has('description')) is-invalid @endif" id="description" placeholder="توضیح اضافی" value="{{ $acceptance->description }}">
                                                @if($errors->has('description'))
                                                    <div class="invalid-feedback"><small>{{ $errors->first('description') }}</small></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success btn-sm col-12">ویرایش</button>
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

@section('scripts')
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
    </script>
@endsection