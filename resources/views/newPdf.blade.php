<!DOCTYPE html>
<html lang="fa" dir="rtl" class="h-full">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8"/>
    <style>

        body {
            font-family: 'iransans';
            background: #2b2b2b;
        }

        .pdfbody {
            font-family: 'iransans';

            margin: 4% !important;
        }
        .main{
            max-width: 600px;
            margin: 0 auto;
            display: block;
            background: #fff;
        }
        #contract main span {
            line-height: 1.8rem;
            font-size: .4rem !important;
            color: black;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background: transparent;
        }

        .table th,
        .table td{
            font-family: 'iransans';

            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: middle;
            horiz-align: right;
            text-align: right;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .table .table {
            background-color: #fff;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-borderless th,
        .table-borderless td,
        .table-borderless thead th,
        .table-borderless tbody + tbody {
            border: 0;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-primary,
        .table-primary > th,
        .table-primary > td {
            background-color: #b8daff;
        }

        .table-hover .table-primary:hover {
            background-color: #9fcdff;
        }

        .table-hover .table-primary:hover > td,
        .table-hover .table-primary:hover > th {
            background-color: #9fcdff;
        }

        .table-secondary,
        .table-secondary > th,
        .table-secondary > td {
            background-color: #d6d8db;
        }

        .table-hover .table-secondary:hover {
            background-color: #c8cbcf;
        }

        .table-hover .table-secondary:hover > td,
        .table-hover .table-secondary:hover > th {
            background-color: #c8cbcf;
        }

        .table-success,
        .table-success > th,
        .table-success > td {
            background-color: #c3e6cb;
        }

        .table-hover .table-success:hover {
            background-color: #b1dfbb;
        }

        .table-hover .table-success:hover > td,
        .table-hover .table-success:hover > th {
            background-color: #b1dfbb;
        }

        .table-info,
        .table-info > th,
        .table-info > td {
            background-color: #bee5eb;
        }

        .table-hover .table-info:hover {
            background-color: #abdde5;
        }

        .table-hover .table-info:hover > td,
        .table-hover .table-info:hover > th {
            background-color: #abdde5;
        }

        .table-warning,
        .table-warning > th,
        .table-warning > td {
            background-color: #ffeeba;
        }

        .table-hover .table-warning:hover {
            background-color: #ffe8a1;
        }

        .table-hover .table-warning:hover > td,
        .table-hover .table-warning:hover > th {
            background-color: #ffe8a1;
        }

        .table-danger,
        .table-danger > th,
        .table-danger > td {
            background-color: #f5c6cb;
        }

        .table-hover .table-danger:hover {
            background-color: #f1b0b7;
        }

        .table-hover .table-danger:hover > td,
        .table-hover .table-danger:hover > th {
            background-color: #f1b0b7;
        }

        .table-light,
        .table-light > th,
        .table-light > td {
            background-color: #fdfdfe;
        }

        .table-hover .table-light:hover {
            background-color: #ececf6;
        }

        .table-hover .table-light:hover > td,
        .table-hover .table-light:hover > th {
            background-color: #ececf6;
        }

        .table-dark,
        .table-dark > th,
        .table-dark > td {
            background-color: #c6c8ca;
        }

        .table-hover .table-dark:hover {
            background-color: #b9bbbe;
        }

        .table-hover .table-dark:hover > td,
        .table-hover .table-dark:hover > th {
            background-color: #b9bbbe;
        }

        .table-active,
        .table-active > th,
        .table-active > td {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover > td,
        .table-hover .table-active:hover > th {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table .thead-dark th {
            color: #fff;
            background-color: #212529;
            border-color: #32383e;
        }

        .table .thead-light th {
            color: #495057;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .table-dark {
            color: #fff;
            background-color: #212529;
        }

        .table-dark th,
        .table-dark td,
        .table-dark thead th {
            border-color: #32383e;
        }

        .table-dark.table-bordered {
            border: 0;
        }

        .table-dark.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .table-dark.table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.075);
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }

        .table-responsive > .table-bordered {
            border: 0;
        }

        .table {
            border-collapse: collapse !important;
        }

        .table td,
        .table th {
            background-color: #fff !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6 !important;
        }

        .border-top-0 {
            border-top: 0 !important;
        }

        .font-weight-light {
            font-weight: 300 !important;
        }

        .font-weight-normal {
            font-weight: 400 !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .no-gutters > .col,
        .no-gutters > [class*="col-"] {
            padding-right: 0;
            padding-left: 0;
        }

        .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col,
        .col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm,
        .col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md,
        .col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg,
        .col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl,
        .col-xl-auto {
            position: relative;
            width: 100%;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .row{
            width: 100%;
            display: flex;
            flex-direction: row-reverse;
        }

        .col {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }

        .col-auto {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            width: auto;
            max-width: none;
        }

        .col-1 {
            -ms-flex: 0 0 8.333333%;
            flex: 0 0 8.333333%;
            max-width: 8.333333%;
        }

        .col-2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }

        .col-3 {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }

        .col-4 {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .col-5 {
            -ms-flex: 0 0 41.666667%;
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }

        .col-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-7 {
            -ms-flex: 0 0 58.333333%;
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }

        .col-8 {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }

        .col-9 {
            -ms-flex: 0 0 75%;
            flex: 0 0 75%;
            max-width: 75%;
        }

        .col-10 {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }

        .col-11 {
            -ms-flex: 0 0 91.666667%;
            flex: 0 0 91.666667%;
            max-width: 91.666667%;
        }

        .col-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }

        .col-md {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }

        .col-md-auto {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            width: auto;
            max-width: none;
        }

        .col-md-1 {
            -ms-flex: 0 0 8.333333%;
            flex: 0 0 8.333333%;
            max-width: 8.333333%;
        }

        .col-md-2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }

        .col-md-3 {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }

        .col-md-4 {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .col-md-5 {
            -ms-flex: 0 0 41.666667%;
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }

        .col-md-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-md-7 {
            -ms-flex: 0 0 58.333333%;
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }

        .col-md-8 {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }

        .col-md-9 {
            -ms-flex: 0 0 75%;
            flex: 0 0 75%;
            max-width: 75%;
        }

        .col-md-10 {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }

        .col-md-11 {
            -ms-flex: 0 0 91.666667%;
            flex: 0 0 91.666667%;
            max-width: 91.666667%;
        }

        .col-md-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }

        .img-thumbnail {
            padding: 0.25rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            max-width: 100%;
            height: auto;
        }

        .text-center {
            text-align: center !important;
        }

        .mb-4,
        .my-4 {
            margin-bottom: 1.5rem !important;
        }

        .ml-5,
        .mx-5 {
            margin-left: 2rem !important;
        }

        .ml-2,
        .mx-2 {
            margin-left: 0.5rem !important;
        }

        .w-100 {
            width: 100% !important;
        }

        body {
            direction: ltr !important;
            font-size: 10px !important;
            margin: 15px 0 15px 0 !important;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
        }

        @page {
            margin: 15px 0 15px 0 !important;
        }

        .border-bottom {
            border-bottom: 1px solid black !important;
        }

        @page {
            header: page-header;
            footer: page-footer;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

    </style>
    <style>
        @page {
            margin: 110pt 50pt 90pt 50pt !important;
        }

        body {
            font-family: 'iransans' !important;
            font-feature-settings: "ss02";
        }

        h1, h2, h3, h4, h5, h6, input, textarea, button, select {
            font-family: iransansx !important;
            font-feature-settings: "ss02";
        }

        h1 {
            font-weight: bold;
        }



        #header {
            padding: 2rem 0 2rem 0;
            width: 100%;
            margin: auto;
            direction: ltr;
            display: grid;
            grid-template-columns: auto auto auto auto;
            grid-gap: 10px;
            border-bottom: 2px solid #C5973A;
        }

        .code-class {
            font-family: 'iransans';
            font-size: 14px;
            text-align: right;
        }

        .code-title-class {
            font-family: 'iransans';
            font-size: 10px;
            text-align: right;
            padding: 10px;
        }

        @supports (font-variation-settings: normal) {
            body {
                font-family: 'iransansxv' !important;
                font-feature-settings: "ss02";
            }

            h1, h2, h3, h4, h5, h6, input, textarea {
                font-family: 'iransansxv' !important;
                font-feature-settings: "ss02";
            }

            .title-font {
                font-family: 'morabbavf' !important;
                font-feature-settings: "ss02";
            }
        }

        .latin-font {
            font-family: 'baloo' !important;
        }

        html {
            font-size: 25px;
            background: var(--background-color);
        }

        html body {
            margin: 0;
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            html {
                overflow-x: hidden !important;
            }
        }

        #contract {
            width: 100%;
            margin: auto;
        }

        #contract header {
            width: 100%;
            text-align: left;
            border-bottom: 2px solid #C5973A;
        }

        #contract header img {
            width: 30%;
        }

        #contract main h1 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 2rem;
        }

        #contract main h2 {
            font-size: 15px;
            margin: 0;
            font-weight: bold;
        }

        /*#contract main p {*/
        /*    line-height: 2.5rem;*/
        /*    font-size: 1.2rem;*/
        /*    color: black;*/
        /*    text-align: justify;*/
        /*    margin: 0;*/
        /*}*/
        #contract main p {
            /* line-height: 2.5rem; */
            font-size: .5rem;
            color: black;
            text-align: justify;
            margin: 0;
        }
        #contract main span {
            line-height: 1.8rem;
            font-size: 1.2rem;
            color: black;
        }

        #contract main input {
            border: 0;
            padding: 0;
        }

        #contract main textarea {
            border: 0;
            padding: 0;
            width: 100%;
            resize: none;
        }

        #contract main input:focus, #contract main textarea:focus {
            outline: 0;
        }

        #contract main div.part {
            margin-top: 1.5rem;
        }

        #contract main ul {
            list-style-type: none;
            padding-right: 0;
            margin: 0;
        }

        #contract main ul li {
            line-height: 2.5rem;
            font-size: 1.2rem;
            color: black;
            text-align: justify;
            margin: 0;
        }

        .sign {
            margin: 6rem auto 0 auto;
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            width: 80%;
        }

        .right {
            display: flex;
            flex-direction: row;
        }

        .text {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .text-span {
            font-size: 1.2rem;
            text-align: center;
            padding: 10px
        }

        .right img {
            width: 70%;
        }

        .left {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .left span {
            font-size: 1.2rem;
        }

        .footer {
            width: 100%;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #C5973A;
        }

        .footer-div {
            display: flex;
            align-items: center;
            flex-direction: row;
        }

        .footer-p {
            font-size: 1.2rem;
            direction: ltr;
        }

        .footer-icon {
            background-color: #C5973A;
            border-radius: 10px;
            padding: 0.15rem;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
        }

    </style>
</head>

<body>

<main class="main" id="contract">
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
        .univerSitiesBadge{
            font-size: 7pt;
            background: #2b2b2b;
            color: #fff;
            margin: 0 5px;
            text-align: center;
            display: inline-block;
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
</main>
</body>
</html>
