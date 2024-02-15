@extends('admin.lyout')

@section('title')
    پنل مدیریت - داشبورد
@endsection

@section('css')

@endsection

@section('content')
    <script>
        function createCookie(name, value, days) {
            console.log(days);
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                var expires = "; expires=" + date.toGMTString();
            } else var expires = "";
            document.cookie = name + "=" + value + expires + ";";
        }

        @if(session()->get('token'))
        createCookie("auth_token", "Bearer {{session()->get('token')}}", 5);
        @endif
    </script>

    <div class="content d-flex flex-column flex-column-fluid" id="dashboard">
        <div class="toolbar">
            <div class="container-fluid d-flex flex-stack">
                <div>
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">داشبورد<span
                                class="h-20px border-gray-200 border-start ms-3 mx-2"></span></h1>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid">
            <div class="container">

                <div id="chart">
                    @include('admin.partials.dashboard-chart', ['userChart'=>$userChart,
                                                           'transactionChart'=>$transactionChart,
                                                           'acquaintedChart'=>$acquaintedChart,
                                                           'contractChart'=>$contractChart])
                </div>

                <div class="card-p mt-10 mb-10  position-relative">
                    <div class="row g-0">
                        <div class="col-12 col-md bg-light-info px-6 py-8 rounded-2 me-7 mb-7">
                            <i class="d-block my-2 fa fa-route fa-3x text-info"></i>
                            @foreach($acquainteds as $acquainted => $value)
                                <div href="#" class="text-info fw-bold fs-6">{{$acquainted}}
                                    : {{ number_format($value) }} </div>
                            @endforeach

                        </div>
                        <div class="col-12 col-md bg-light-primary px-6 py-8 rounded-2 mb-7">
                            <i class="d-block my-2 fa fa-coins fa-3x text-primary"></i>
                            @foreach($transactionTypes as $transactionType => $value)
                                <div href="#" class="text-primary fw-bold fs-6">{{$transactionType}}
                                    : {{ number_format($value) }} ریال</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="col-12 col-md bg-light-warning px-6 py-8 rounded-2 me-7 mb-7">
                            <i class="d-block my-2 fa fa-money-bill-wave fa-3x text-warning"></i>
                            <a href="#" class="text-warning fw-bold fs-6">کل درآمد
                                : {{ number_format($factors) }} ریال</a>
                        </div>
                        <div class="col-12 col-md bg-light-primary px-6 py-8 rounded-2 mb-7">
                            <i class="d-block my-2 fa fa-user fa-3x text-primary"></i>
                            <a href="#" class="text-primary fw-bold fs-6">کل کاربران
                                : {{ $allUsers }} نفر<br>کاربران ویژه : {{ $specialUsers }} نفر<br>
                                کاربران پایه: {{$baseUsers}} <br> کاربران عادی : {{$normalUsers}}</a>
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="col-12 col-md bg-light-danger px-6 py-8 rounded-2 me-7">
                            <i class="d-block my-2 fa fa-credit-card fa-3x text-danger"></i>
                            <a href="#" class="text-danger fw-bold fs-6 mt-2">کل تراکنش ها
                                : {{ $allTransactions }}<br>تراکنش موفق : {{ $successTransactions }}</a>
                        </div>
                        <div class="col-12 col-md bg-light-success px-6 py-8 rounded-2">
                            <i class="d-block my-2 fa fa-university fa-3x text-success"></i>
                            <a href="#" class="text-success fw-bold fs-6 mt-2">دانشگاه ها
                                : {{ $universities }}</a>
                        </div>
                    </div>
                </div>

                <div class="row g-5 gx-xxl-8">
                    <div class="col-12">
                        <div class="card card-xxl-stretch mb-5 mb-xxl-8">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">گزارش اپلای ها</span>

                                </h3>
                                <div class="card-toolbar">
                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bolder px-4 me-1"
                                               data-bs-toggle="tab" href="#monthAcceptance">ماه</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bolder px-4 me-1"
                                               data-bs-toggle="tab" href="#weekAcceptance">هفته</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bolder px-4"
                                               data-bs-toggle="tab" href="#dayAcceptance">دیروز</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary active fw-bolder px-4"
                                               data-bs-toggle="tab" href="#todayAcceptance">امروز</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body py-3">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="todayAcceptance">
                                        <div class="table-responsive">
                                            <span class="text-muted mt-1 mb-3 fw-bold fs-7">{{ count($acceptances->today) }} اپلای امروز</span>
                                            <table class="table table-row-dashed mt-3 table-row-gray-200 align-middle gs-0 gy-4">
                                                <thead>
                                                <tr class="text-center">
                                                    <th>#</th>
                                                    <th>نام کاربر</th>
                                                    <th>شماره تماس</th>
                                                    <th>پکیج</th>
                                                    <th>مقطع درخواستی</th>
                                                    <th>تاریخ ثبت</th>
                                                    <th>نمایش</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($acceptances->today as $acceptance)
                                                    <tr class="text-center">
                                                        <td>{{ $acceptance->id }}</td>
                                                        <td>{{ $acceptance->firstname }} {{$acceptance->lastname}}</td>
                                                        <td>{{ $acceptance->phone }}</td>
                                                        <td>{{ $acceptance->user->type === 2 ? 'طلایی' : 'پایه' }}</td>
                                                        <td>{{$acceptance->admittance}}</td>
                                                        <td>
                                                                <?php
                                                                $date = explode(' ', $acceptance->created_at);
                                                                $date = explode('-', $date[0]);
                                                                $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                                                                echo $date;
                                                                ?>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:{}"
                                                               onclick="showAcceptanceModal({{ $acceptance->id }})"
                                                               class="btn btn-info btn-sm">نمایش جزئیات</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="dayAcceptance">
                                        <div class="table-responsive">
                                            <span class="text-muted mt-1 mb-3 fw-bold fs-7">{{ count($acceptances->day) }} اپلای در روز گذشته</span>
                                            <table class="table table-row-dashed mt-3 table-row-gray-200 align-middle gs-0 gy-4">
                                                <thead>
                                                <tr class="text-center">
                                                    <th>#</th>
                                                    <th>نام کاربر</th>
                                                    <th>شماره تماس</th>
                                                    <th>پکیج</th>
                                                    <th>مقطع درخواستی</th>
                                                    <th>تاریخ ثبت</th>
                                                    <th>نمایش</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($acceptances->day as $acceptance)
                                                    <tr class="text-center">
                                                        <td>{{ $acceptance->id }}</td>
                                                        <td>{{ $acceptance->firstname }} {{$acceptance->lastname}}</td>
                                                        <td>{{ $acceptance->phone }}</td>
                                                        <td>{{ $acceptance->user->type === 2 ? 'طلایی' : 'پایه' }}</td>
                                                        <td>{{$acceptance->admittance}}</td>
                                                        <td>
                                                                <?php
                                                                $date = explode(' ', $acceptance->created_at);
                                                                $date = explode('-', $date[0]);
                                                                $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                                                                echo $date;
                                                                ?>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:{}"
                                                               onclick="showAcceptanceModal({{ $acceptance->id }})"
                                                               class="btn btn-info btn-sm">نمایش جزئیات</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="weekAcceptance">
                                        <div class="table-responsive">
                                            <span class="text-muted mt-1 mb-3 fw-bold fs-7">{{ count($acceptances->week) }} اپلای در 7 روز گذشته</span>
                                            <table class="table table-row-dashed mt-3 table-row-gray-200 align-middle gs-0 gy-4">
                                                <thead>
                                                <tr class="text-center">
                                                    <th>#</th>
                                                    <th>نام کاربر</th>
                                                    <th>شماره تماس</th>
                                                    <th>پکیج</th>
                                                    <th>مقطع درخواستی</th>
                                                    <th>تاریخ ثبت</th>
                                                    <th>نمایش</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($acceptances->week as $acceptance)
                                                    <tr class="text-center">
                                                        <td>{{ $acceptance->id }}</td>
                                                        <td>{{ $acceptance->firstname }} {{$acceptance->lastname}}</td>
                                                        <td>{{ $acceptance->phone }}</td>
                                                        <td>{{ $acceptance->user->type === 2 ? 'طلایی' : 'پایه' }}</td>
                                                        <td>{{$acceptance->admittance}}</td>
                                                        <td>
                                                                <?php
                                                                $date = explode(' ', $acceptance->created_at);
                                                                $date = explode('-', $date[0]);
                                                                $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                                                                echo $date;
                                                                ?>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:{}"
                                                               onclick="showAcceptanceModal({{ $acceptance->id }})"
                                                               class="btn btn-info btn-sm">نمایش جزئیات</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="monthAcceptance">
                                        <div class="table-responsive">
                                            <span class="text-muted mt-1  fw-bold fs-7">{{ count($acceptances->month) }} اپلای در 30 روز گذشته</span>

                                            <table class="table table-row-dashed mt-3 table-row-gray-200 align-middle gs-0 gy-4">
                                                <thead>
                                                <tr class="text-center">
                                                    <th>#</th>
                                                    <th>نام کاربر</th>
                                                    <th>شماره تماس</th>
                                                    <th>پکیج</th>
                                                    <th>مقطع درخواستی</th>
                                                    <th>تاریخ ثبت</th>
                                                    <th>نمایش</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($acceptances->month as $acceptance)
                                                    <tr class="text-center">
                                                        <td>{{ $acceptance->id }}</td>
                                                        <td>{{ $acceptance->firstname }} {{$acceptance->lastname}}</td>
                                                        <td>{{ $acceptance->phone }}</td>
                                                        <td>{{ $acceptance->user->type === 2 ? 'طلایی' : 'پایه' }}</td>
                                                        <td>{{$acceptance->admittance}}</td>
                                                        <td>
                                                                <?php
                                                                $date = explode(' ', $acceptance->created_at);
                                                                $date = explode('-', $date[0]);
                                                                $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
                                                                echo $date;
                                                                ?>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                    onclick="showAcceptanceModal({{ $acceptance->id }})"
                                                                    class="btn btn-info btn-sm">نمایش جزئیات
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
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
    <div class="modal fade" tabindex="-1" id="showAcceptance">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">نمایش درخواست اپلای</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="fa fa-window-close fa-2x text-danger"></span>
                    </div>
                </div>

                <div class="modal-body">


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showAcceptanceModal(id) {
            $.get("{{route("admin.getUserAcceptance")}}", {id: id}, function (data) {
                $("#showAcceptance .modal-body").html(data)
                $("#showAcceptance").modal("show")
            })
        }
    </script>

@endsection


@section('script')

@endsection