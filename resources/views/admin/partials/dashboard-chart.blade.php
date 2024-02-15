<div class="row gy-5 g-xl-8">
    <div class="col-12">
        <div class="row">
            <div class="col-md-9">
                <div class="d-flex">
                    <a href="javascript:{}" onclick="renderChart(1)" class="btn btn-info btn-sm">کاربران </a>
                    <a href="javascript:{}" onclick="renderChart(4)" class="btn btn-info btn-sm">قراردادها </a>
                    <a href="javascript:{}" onclick="renderChart(2)" class="btn btn-info btn-sm">درامد </a>
                    <a href="javascript:{}" onclick="renderChart(3)" class="btn btn-info btn-sm">نحوه
                        آشنایی </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex">
                    <select class="form-control" onchange="changeChart(this.value)">>
                        <option selected disabled>انتخاب کنید</option>
                        <option value="">12 ماه گذشته</option>
                        <option value="فروردین">فروردین</option>
                        <option value="اردیبهشت">اردیبهشت</option>
                        <option value="خرداد">خرداد</option>
                        <option value="تیر">تیر</option>
                        <option value="مرداد">مرداد</option>
                        <option value="شهریور">شهریور</option>
                        <option value="مهر">مهر</option>
                        <option value="آبان">آبان</option>
                        <option value="آذر">آذر</option>
                        <option value="دی">دی</option>
                        <option value="بهمن">بهمن</option>
                        <option value="اسفند">اسفند</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card card-xxl-stretch">
            <div class="card-header border-0 bg-danger py-5">
                <h3 class="card-title fw-bolder text-white chart_title">اپلای های 12 ماه گذشته</h3>
            </div>
            <div class="card-body p-0">

                <div class="mixed-widget-2-chart card-rounded-bottom bg-danger" id="apexchart"
                     data-kt-color="danger" style="height: 200px">

                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
    <script src="{{ url('assets/plugins/apexcharts/apexcharts.js') }}"></script>
    <script>
        var options_transactions = {
            series: [{
                name: 'کل درآمد',
                data: [@foreach($transactionChart as $key => $ar) {{  $ar[1] +  $ar[2] + $ar[3] +  $ar[4] +  $ar[5] +  $ar[6]  }}, @endforeach]
            }, {
                name: 'فاکتور',
                data: [@foreach($transactionChart as $key => $ar) {{ $ar[1] }}, @endforeach]
            }, {
                name: 'رزومه',
                data: [@foreach($transactionChart as $key => $ar) {{ $ar[2] }}, @endforeach]
            }
                , {
                    name: 'انگیزه نامه',
                    data: [@foreach($transactionChart as $key => $ar) {{ $ar[3] }}, @endforeach]
                }
                , {
                    name: 'پکیج',
                    data: [@foreach($transactionChart as $key => $ar) {{ $ar[4] }}, @endforeach]
                }
                , {
                    name: 'وبینار',
                    data: [@foreach($transactionChart as $key => $ar) {{ $ar[5] }}, @endforeach]
                }
                , {
                    name: 'مشاوره تلفنی',
                    data: [@foreach($transactionChart as $key => $ar) {{ $ar[6] }}, @endforeach]
                }],
            chart: {
                toolbar: {
                    show: false
                },
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                show: false
            },
            stroke: {
                curve: 'smooth'
            },

            xaxis: {
                categories: [@foreach($contractChart as $key => $ar) "{{  $month ? ++$key : $key }}", @endforeach]
            },
            yaxis: {
                show: false
            },
            colors: ['black', 'red', 'green', "purple", "yellow", "red", "blue"],
            legend: {
                show: false
            },
            fill: {
                type: 'solid',
                opacity: 0.0
            }
        };
        var options_acquainteds = {
            series: [{
                name: 'کل',
                data: [@foreach($acquaintedChart as $key => $ar) {{  $ar[0] +  $ar[1] + $ar[2] +  $ar[3] +  $ar[4] +  $ar[5] +  $ar[6]   }}, @endforeach]
            }, {
                name: 'تلگرام',
                data: [@foreach($acquaintedChart as $key => $ar) {{ $ar[0] }}, @endforeach]
            }
                , {
                    name: 'اینستاگرام',
                    data: [@foreach($acquaintedChart as $key => $ar) {{ $ar[1] }}, @endforeach]
                }
                , {
                    name: 'یوتیوب',
                    data: [@foreach($acquaintedChart as $key => $ar) {{ $ar[2] }}, @endforeach]
                }
                , {
                    name: 'تبلیغات',
                    data: [@foreach($acquaintedChart as $key => $ar) {{ $ar[3] }}, @endforeach]
                }
                , {
                    name: 'معرفی آشنایان',
                    data: [@foreach($acquaintedChart as $key => $ar) {{ $ar[4] }}, @endforeach]
                }, {
                    name: 'موتورهای جستجو',
                    data: [@foreach($acquaintedChart as $key => $ar) {{ $ar[5] }}, @endforeach]
                }, {
                    name: 'سایر',
                    data: [@foreach($acquaintedChart as $key => $ar) {{ $ar[6] }}, @endforeach]
                }],
            chart: {
                toolbar: {
                    show: false
                },
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                show: false
            },
            stroke: {
                curve: 'smooth'
            },

            xaxis: {
                categories: [@foreach($contractChart as $key => $ar) "{{  $month ? ++$key : $key }}", @endforeach]
            },
            yaxis: {
                show: false
            },
            colors: ['black', 'red', 'green', "purple", "yellow", "red", "blue", "white"],
            legend: {
                show: false
            },
            fill: {
                type: 'solid',
                opacity: 0.0
            }
        };
        var chart = null;
        var options_users = {
            series: [{
                name: 'کل کاربران',
                data: [@foreach($userChart as $key => $ar) {{ $ar["special_user"] + $ar["normal_user"] + $ar['base_user'] }}, @endforeach]
            }, {
                name: 'کاربران ویژه',
                data: [@foreach($userChart as $key => $ar) {{ $ar["special_user"] }}, @endforeach]
            }, {
                name: 'کاربران پایه',
                data: [@foreach($userChart as $key => $ar) {{ $ar["base_user"] }}, @endforeach]
            }, {
                name: 'کاربران عادی',
                data: [@foreach($userChart as $key => $ar) {{ $ar["normal_user"] }}, @endforeach]
            }],
            chart: {
                toolbar: {
                    show: false
                },
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                show: false
            },
            stroke: {
                curve: 'smooth'
            },

            xaxis: {
                categories: [@foreach($contractChart as $key => $ar) "{{  $month ? ++$key : $key }}", @endforeach]
            },
            yaxis: {
                show: false
            },
            colors: ['#000000', '#FF0000', '#FF958E', '#FFB577'],
            legend: {
                show: false
            },
            fill: {
                type: 'solid',
                opacity: 0.0
            }
        };
        var options_contracts = {
            series: [{
                name: 'کل قراردادها',
                data: [@foreach($contractChart as $key => $ar) {{ $ar["special_contracts"] + $ar["base_contracts"] }}, @endforeach]
            }, {
                name: 'کاربران ویژه',
                data: [@foreach($contractChart as $key => $ar) {{ $ar["special_contracts"] }}, @endforeach]
            }, {
                name: 'کاربران پایه',
                data: [@foreach($contractChart as $key => $ar) {{ $ar["base_contracts"] }}, @endforeach]
            }],
            chart: {
                toolbar: {
                    show: false
                },
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                show: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                categories: [@foreach($contractChart as $key => $ar) "{{  $month ? ++$key : $key }}", @endforeach]
            },
            yaxis: {
                show: false
            },
            colors: ['#000000', '#FF0000', '#FF958E'],
            legend: {
                show: false
            },
            fill: {
                type: 'solid',
                opacity: 0.0
            }
        };
        renderChart(1);

        function renderChart(id) {
            let option = {};
            let month = '{{$month}}'
            if (id == 1) {
                option = options_users
                if (month)
                    $(".chart_title").html("اپلای های ماه " + month)
                else
                    $(".chart_title").html("اپلای های 12 ماه گذشته")
            } else if (id == 2) {
                option = options_transactions
                if (month)
                    $(".chart_title").html("درآمد در ماه " + month)
                else
                    $(".chart_title").html("درآمد در 12 ماه گذشته")
            } else if (id == 3) {
                option = options_acquainteds
                if (month)
                    $(".chart_title").html("نحوه آشنایی در ماه " + month)
                else
                    $(".chart_title").html("نحوه آشنایی در 12 ماه گذشته")
            } else if (id == 4) {
                option = options_contracts
                if (month)
                    $(".chart_title").html("قراردادها در ماه " + month)
                else
                    $(".chart_title").html("قراردادها در 12 ماه گذشته")
            }
            if (chart != null) {
                chart.destroy();
            }
            chart = new ApexCharts(document.querySelector("#apexchart"), option);


            chart.render();

        }

        function changeChart(value) {
            var url = '{{url('admin/dashboard')}}';
            window.location.href = url + '/' + value
        }
    </script>
@endsection
