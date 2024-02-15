@extends('invoice.master-layout-pdf')
@section('content')

    <style>
        body {
            font-size: 11px;
        }

        h4 {
            background: #3F4254;
            color: #fff;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-top: 5px;
            margin-bottom: 3px;
        }

        .table-1 {
            width: 100%;
        }

        .table-1 td {
            font-family: 'iransans';

            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        .footer-p {
            font-size: 11px;
        }
    </style>

    <div class="pdfbody">
        <htmlpageheader name="page-header">
            <table id="header">
                <tr>
                    <td colspan="1"><img width="140" src="src/images/logo.svg"></td>
                    <td colspan="2" style="text-align: left">
                        <p class="code-title-class" style="font-size: 16px; font-weight: bold">رسید </p>
                    </td>
                    <td colspan="1" style="text-align: right">
                        <p class="code-title-class" style="font-size: 14px">شماره رسید:
                            <span>{{$invoice->code}}</span></p>
                    </td>
                </tr>
            </table>
        </htmlpageheader>

        <main>
            <div style="background: #eeeeef; padding: 5px; border-radius: 5px">
                <table class="table-1">
                    <tr>
                        <td colspan="1">تاریخ صدور</td>
                        <td colspan="3" style="text-align: left">{{$invoice->jalali_created}}</td>
                    </tr>
                    <tr>
                        <td colspan="1">تاریخ پرداخت</td>
                        <td colspan="3" style="text-align: left">{{$invoice->payment_at}}</td>
                    </tr>
                    <tr>
                        <td colspan="1">وضعیت پرداخت</td>
                        <td colspan="3" style="text-align: left">پرداخت شده</td>
                    </tr>
                    <tr>
                        <td colspan="1">عنوان</td>
                        <td colspan="3" style="text-align: left">{{$invoice->invoice}}</td>
                    </tr>
                </table>
            </div>

            <h4>مشخصات پرداخت کننده</h4>
            <div style="background: #eeeeef; padding: 5px; border-radius: 5px">
                <table class="table-1">
                    <tr>
                        <td colspan="1">نام و نام خانوادگی</td>
                        <td colspan="3"
                            style="text-align: left">{{$invoice->user->firstname}} {{$invoice->user->lastname}}</td>
                    </tr>
                    <tr>
                        <td colspan="1">شماره موبایل</td>
                        <td colspan="3" style="text-align: left">{{$invoice->user->mobile}}</td>
                    </tr>
                    <tr>
                        <td colspan="1">ایمیل</td>
                        <td colspan="3" style="text-align: left">{{$invoice->user->email}}</td>
                    </tr>
                    <tr>
                        <td colspan="1">کد قرارداد</td>
                        <td colspan="3" style="text-align: left">{{$invoice->user->contract_code}}</td>
                    </tr>
                </table>
            </div>

            @if($invoice->bank_account_id !== null && $invoice->payment_method !== 'cash')
                <h4>مشخصات حساب بانکی</h4>
                <div style="background: #eeeeef; padding: 5px; border-radius: 5px">
                    <table class="table-1">
                        <tr>
                            <td colspan="1">بانک</td>
                            <td colspan="1"
                                style="text-align: left">{{$invoice->bank->bank_name}}
                            </td>

                            @if($invoice->bank->account_name !== '')
                                <td colspan="1">نام صاحب حساب</td>
                                <td colspan="1" style="text-align: left">{{$invoice->bank->account_name}}</td>
                            @endif
                        </tr>
                        @if($invoice->bank->card_number !== '0')
                            <tr>
                                <td colspan="1">شماره کارت</td>
                                <td colspan="3" style="text-align: left">{{$invoice->bank->card_number}}</td>
                            </tr>
                        @endif
                        {{--                        @if($invoice->bank->account_number !== '0')--}}
                        {{--                            <tr>--}}
                        {{--                                <td colspan="1">شماره حساب</td>--}}
                        {{--                                <td colspan="3" style="text-align: left">{{$invoice->bank->account_number}}</td>--}}
                        {{--                            </tr>--}}
                        {{--                        @endif--}}
                        @if($invoice->bank->shaba_number !== '0')
                            <tr>
                                <td colspan="1">شماره شبا</td>
                                <td colspan="3" style="text-align: left">{{$invoice->bank->shaba_number}}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            @endif

            <h4>مشخصات مالی</h4>
            <div style="background: #eeeeef; padding: 5px; border-radius: 5px">
                <table class="table-1">
                    <tr>
                        <td colspan="1">مبلغ</td>
                        <td colspan="3"
                            style="text-align: left">{{$invoice->euro_amount === '0' ? number_format((float)$invoice->ir_amount) : number_format((float)$invoice->euro_amount) }}
                            <span>{{$invoice->currency_title}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">تخفیف معرف</td>
                        <td colspan="3" style="text-align: left">{{number_format((float)$invoice->balance_amount)}}
                            <span>{{$invoice->currency_title}}</span></td>
                    </tr>
                    <tr>
                        <td colspan="1">تخفیف</td>
                        <td colspan="3" style="text-align: left">{{number_format((float)$invoice->discount_amount)}}
                            <span>{{$invoice->discount_type === 'percent' ? '%' : $invoice->currency_title}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">شرح تخفیف</td>
                        <td colspan="3" style="text-align: left">{{$invoice->discount_description}}</td>
                    </tr>
                    @php
                        $totalUniPrice=0
                    @endphp
                    @if(isset($extra_universities) and sizeof($extra_universities)>0)
                        <tr>
                            <td colspan="1">
                                تعداد دانشگاه های اضافه
                            </td>
                            <td colspan="3" style="text-align: left">
                                {{sizeof($extra_universities)}}
                            </td>
                        <tr>
                            <td colspan="1">
                                هزینه اضافه شده (دانشگاه)
                            </td>
                            <td colspan="3" style="text-align: left">
                                @php
                                    $totalUniPrice=0
                                @endphp
                                @foreach($extra_universities as $extra)
                                    @php
                                        $totalUniPrice+=$extra->extra_price_euro
                                    @endphp
                                @endforeach
                                {{number_format($totalUniPrice)}}
                                یورو
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="1">جمع کل</td>
                        <td colspan="3" style="text-align: left">{{number_format($invoice->final_amount+$totalUniPrice)}}
                            <span>{{$invoice->currency_title}}</span>
                        </td>
                    </tr>
                </table>
            </div>


            <table class="sign" style="margin-top: 10px">
                <tr style="text-align: center; font-size: 11px">
                    <td colspan="1" style="width: 30%; text-align: center">
                        <p class="text-span" style="text-align: center">مدیریت اپلای جرمنی</p><br/>
                        <p class="text-span" style="text-align: center">سروش فدایی منش</p>        <br/>
                        <p class="text-span" style="text-align: center">امضا</p>                       <br/>
                    </td>
                    <td style="width: 38%">
                        <img src="src/images/mohr.png" style="width: 80px"/>
                        <img src="src/images/sign.png" width="80"/>
                    </td>
                    <td colspan="2" style="width: 35%; text-align: center">

                    </td>
                </tr>
            </table>
        </main>

        <htmlpagefooter name="page-footer">
            <div>
                <table class="footer">
                    <tr>
                        <td style="width: 33.33%; text-align: left">
                            <table style="text-align: left; width: 100%">
                                <tr>
                                    <td style="text-align: left"><p class="footer-p">021-91099888</p></td>
                                    <td><img class="footer-icon" src="src/images/icons/phone-call.svg"
                                             alt=""/></td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 33.33%; text-align: left">
                            <table style="text-align: left; width: 100%">
                                <tr>
                                    <td style="text-align: left"><p class="footer-p">info@ApplyGermany.net</p></td>
                                    <td><img class="footer-icon" src="src/images/icons/mail.svg" alt=""></td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 33.33%; text-align: left">
                            <table style="text-align: left; width: 100%">
                                <tr>
                                    <td style="text-align: left"><p class="footer-p">ApplyGermany.Net</p></td>
                                    <td><img class="footer-icon" src="src/images/icons/cloud.svg" alt="">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <p>آدرس: تهران، ضلع غربی میدان نوبنیاد، پلاک 4، برج نوبنیاد، طبقه 3، اپلای جرمنی &#160;&#160; |
                    &#160; کد پستی: &#160;1958963412</p>
            </div>
        </htmlpagefooter>

    </div>
@endsection