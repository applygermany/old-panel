<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
     data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <a href="index.html">
            <img alt="Logo" src="{{ url('assets/media/logos/logo.png') }}" class="h-25px logo"/>
        </a>
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
             style="margin-right: 20px"
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
             data-kt-toggle-name="aside-minimize">
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                     height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"/>
                        <path
                                d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                                fill="#000000" fill-rule="nonzero"
                                transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)"/>
                        <path
                                d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                                fill="#000000" fill-rule="nonzero" opacity="0.5"
                                transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)"/>
                    </g>
                </svg>
            </span>
        </div>
    </div>
    <div class="aside-menu flex-column-fluid">
        <div class="hover-scroll-overlay-y my-2 py-5 py-lg-8" id="kt_aside_menu_wrapper" data-kt-scroll="true"
             data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
             data-kt-scroll-offset="0">
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                 id="#kt_aside_menu" data-kt-menu="true">
                <div class="menu-item @if ((auth()->user()->admin_permissions->dashboard ?? 0) == 0) d-none @endif"
                ">
                <a class="menu-link @if (request()->is('admin/dashboard')) active @endif"
                   href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon">
                            <i class="bi bi-house fs-3"></i>
                        </span>
                    <span class="menu-title">داشبورد</span>
                </a>
            </div>


            <div class="menu-item  @if ((auth()->user()->admin_permissions->admins ?? 0) == 0 && (auth()->user()->admin_permissions->users ?? 0) == 0  && (auth()->user()->admin_permissions->users_information ?? 0) == 0) d-none @endif">
                <div class="menu-content pt-8 pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">مدیریت کاربران</span>
                </div>

                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if ((auth()->user()->admin_permissions->admins ?? 0) == 0) d-none @endif">
                        <span class="menu-link @if (request()->is('admin/admins*')) active hover show @endif">
                            <span class="menu-icon">
                                <i class="fa fa-file-word  fs-3"></i>
                            </span>
                            <span class="menu-title">مدیران</span>
                            <span class="menu-arrow"></span>
                        </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a href="{{ route('admin.admins') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                <span class="menu-title"> مدیران</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a href="{{ route('admin.adminsTeam') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                <span class="menu-title">هم تیمی </span>
                            </a>
                        </div>
                    </div>
                </div>

                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (!isset(auth()->user()->admin_permissions->users)) d-none @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/users')) active @endif"
                           href="{{ route('admin.users') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-users fs-3"></i>
                                </span>
                            <span class="menu-title">کاربران </span>
                        </a>
                    </div>
                </div>

                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (!isset(auth()->user()->admin_permissions->users_information)) d-none @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/users/information')) active @endif"
                           href="{{ route('admin.usersInformation') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-users fs-3"></i>
                                </span>
                            <span class="menu-title">اطلاعات کاربران </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="menu-item @if (!isset(auth()->user()->admin_permissions->orders)) d-none @endif">
                <div class="menu-content pt-8 pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">سفارشات</span>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/resume*')) active hover show @endif">
                        <span class="menu-link @if (request()->is('admin/resume*')) active hover show @endif">
                            <span class="menu-icon">
                                <i class="fa fa-file-word  fs-3"></i>
                            </span>
                            <span class="menu-title">رزومه</span>
                            <span class="menu-arrow"></span>
                        </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a href="{{ route('admin.resumes') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                <span class="menu-title"> سفارشات</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a href="{{ route('admin.resumeTemplates') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                <span class="menu-title">قالب های رزومه </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion ">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/motivations')) active @endif"
                           href="{{ route('admin.motivations') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-lightbulb fs-3"></i>
                                </span>
                            <span class="menu-title">انگیزه نامه </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="menu-item @if (!isset(auth()->user()->admin_permissions->telSupports)) d-none @endif">
                <div class="menu-content pt-8 pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">مشاوره تلفنی</span>
                </div>

                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion ">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/telSupports/experts')) active @endif"
                           href="{{ route('admin.telSupportExpert') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-lightbulb fs-3"></i>
                                </span>
                            <span class="menu-title">رزرو </span>
                        </a>
                    </div>
                </div>
            </div>

{{--            <div class="menu-item @if (!isset(auth()->user()->admin_permissions->reports) and !isset(auth()->user()->admin_permissions->report)) d-none @endif">--}}
{{--                <div class="menu-content pt-8 pb-2">--}}
{{--                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">گزارشات</span>--}}
{{--                </div>--}}

{{--                <div data-kt-menu-trigger="click"--}}
{{--                     class="menu-item menu-accordion ">--}}

{{--                    <div data-kt-menu-trigger="click"--}}
{{--                         class="menu-item menu-accordion ">--}}
{{--                        <div class="menu-item">--}}
{{--                            <a class="menu-link @if (request()->is('admin/foundation/invites')) active @endif"--}}
{{--                               href="{{ route('admin.invites') }}">--}}
{{--                                <span class="menu-icon">--}}
{{--                                    <i class="fa fa-lightbulb fs-3"></i>--}}
{{--                                </span>--}}
{{--                                <span class="menu-title">کدهای دعوت</span>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div data-kt-menu-trigger="click"--}}
{{--                         class="menu-item menu-accordion ">--}}
{{--                        <div class="menu-item">--}}
{{--                            <a class="menu-link @if (request()->is('admin/reports/wordExperience')) active @endif"--}}
{{--                               href="{{ route('admin.workExperience') }}">--}}
{{--                                <span class="menu-icon">--}}
{{--                                    <i class="fa fa-lightbulb fs-3"></i>--}}
{{--                                </span>--}}
{{--                                <span class="menu-title">فروش موفق</span>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div data-kt-menu-trigger="click"--}}
{{--                         class="menu-item menu-accordion ">--}}
{{--                        <div class="menu-item">--}}
{{--                            <a class="menu-link @if (request()->is('admin/reports/contracts')) active @endif"--}}
{{--                               href="{{ route('admin.contracts') }}">--}}
{{--                                <span class="menu-icon">--}}
{{--                                    <i class="fa fa-lightbulb fs-3"></i>--}}
{{--                                </span>--}}
{{--                                <span class="menu-title">قرارداد</span>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="menu-item @if (!isset(auth()->user()->admin_permissions->reports) and !isset(auth()->user()->admin_permissions->report)) d-none @endif">
                <div class="menu-content pt-8 pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">گزارشات</span>
                </div>

                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion ">


                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion ">
                        <div class="menu-item">
                            <a class="menu-link @if (request()->is('admin/foundation/invites')) active @endif"
                               href="{{ route('admin.invites') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-lightbulb fs-3"></i>
                                </span>
                                <span class="menu-title">کدهای دعوت</span>
                            </a>
                        </div>
                    </div>
                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion ">
                        <div class="menu-item">
                            <a class="menu-link @if (request()->is('admin/reports/wordExperience')) active @endif"
                               href="{{ route('admin.workExperience') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-lightbulb fs-3"></i>
                                </span>
                                <span class="menu-title">فروش موفق</span>
                            </a>
                        </div>
                    </div>
                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion ">
                        <div class="menu-item">
                            <a class="menu-link @if (request()->is('admin/reports/contracts')) active @endif"
                               href="{{ route('admin.contracts') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-lightbulb fs-3"></i>
                                </span>
                                <span class="menu-title">قرارداد</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-item">
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion ">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/foundation/invites')) active @endif"
                           href="{{ route('admin.user.exel') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-lightbulb fs-3"></i>
                                </span>
                            <span class="menu-title">گزارش گیری کاربران</span>
                        </a>
                    </div>
                </div>
                <div class="menu-item">
                    <a class="menu-link @if (request()->is('admin/reports/telSupports')) active @endif"
                       href="{{ route('admin.telSupportsReport') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-lightbulb fs-3"></i>
                                </span>
                        <span class="menu-title">مشاوره های تلفنی </span>
                    </a>
                </div>
                <div class="menu-item @if (!isset(auth()->user()->admin_permissions->telsupport_result)) d-none @endif">
                    <a class="menu-link @if (request()->is('admin/reports/telSupports/result')) active @endif"
                       href="{{ route('admin.telSupportResult') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-lightbulb fs-3"></i>
                                </span>
                        <span class="menu-title">نتایج مشاوره </span>
                    </a>
                </div>
            </div>


            <div class="menu-item @if (!isset(auth()->user()->admin_permissions->applies)) d-none @endif">
                <div class="menu-content pt-8 pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">مدیریت اپلای</span>
                </div>


                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion ">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/applyLevels')) active @endif"
                           href="{{ route('admin.applyLevels') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-circle fs-3"></i>
                                </span>
                            <span class="menu-title">مراحل اپلای</span>
                        </a>
                    </div>
                </div>


                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion ">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/applyPhases')) active @endif"
                           href="{{ route('admin.applyPhases') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-align-right fs-3"></i>
                                </span>
                            <span class="menu-title">فازهای اپلای</span>
                        </a>
                    </div>
                </div>


            </div>


            <div class="menu-item @if (!isset(auth()->user()->admin_permissions->webinars)) d-none @endif">
                <div class="menu-content pt-8 pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">مدیریت وبینار</span>
                </div>


                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/webinars*')) hover show @endif">

                    <span class="menu-link @if (request()->is('admin/webinars*')) active hover show @endif">
                        <span class="menu-icon">
                            <i class="fa fa-film fs-3"></i>
                        </span>
                        <span class="menu-title">وبینار</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a href="{{ route('admin.webinars') }}" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">لیست وبینار ها </span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a href="{{ route('admin.webinarsParticipation') }}" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">شرکت کنندگان </span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="menu-item @if (!isset(auth()->user()->admin_permissions->universities)) d-none @endif">
                <div class="menu-content pt-8 pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">مدیریت دانشگاه ها</span>
                </div>
            </div>
            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion @if (!isset(auth()->user()->admin_permissions->universities)) d-none @endif">
                <div class="menu-item">
                    <a class="menu-link @if (request()->is('admin/universities') || request()->is('admin/universities/*')) active @endif"
                       href="{{ route('admin.universities') }}">
                            <span class="menu-icon">
                                <i class="fa fa-university fs-3"></i>
                            </span>
                        <span class="menu-title">لیست دانشگاه ها</span>
                    </a>
                </div>
            </div>


            <div class="menu-item  @if (!isset(auth()->user()->admin_permissions->financial)) d-none @endif">
                <div class="menu-content pt-8 pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">امور مالی</span>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/financials/*')) hover show @endif">

                    <div class="menu-item">
                        <a href="{{ route('admin.invoicesCreate') }}"
                           class="menu-link @if (request()->is('admin/financials/invoices/create')) active @endif">
                               <span class="menu-icon">
                                    <i class="fa fa-percent fs-3"></i>
                                </span>
                            <span class="menu-title">صدور فاکتور</span>
                        </a>
                    </div>

                    <div class="menu-item  @if ((auth()->user()->admin_permissions->financial_confirm ?? 0) == 0) d-none @endif">
                        <a href="{{ route('admin.invoices', ['type'=>'confirmed']) }}"
                           class="menu-link @if (request()->is('admin/financials/invoices/confirmed')) active @endif">
                               <span class="menu-icon">
                                    <i class="fa fa-percent fs-3"></i>
                                </span>
                            <span class="menu-title">تایید فاکتور</span>
                        </a>
                    </div>

                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion @if ((auth()->user()->admin_permissions->financial ?? 0) == 0) d-none @endif">
                        <span class="menu-link @if (request()->is('admin/invoices*')) active hover show @endif">
                            <span class="menu-icon">
                                <i class="fa fa-file-word  fs-3"></i>
                            </span>
                            <span class="menu-title">کل فاکتور ها</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a href="{{ route('admin.invoices', ['type'=>'pre-invoice']) }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title"> پیش فاکتور ها</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{ route('admin.invoices', ['type'=>'receipt']) }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">رسید ها </span>
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="menu-item ">
                        <a class="menu-link @if (request()->is('admin/offs')) active @endif"
                           href="{{ route('admin.offs') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-percent fs-3"></i>
                                </span>
                            <span class="menu-title">تخفیفات</span>
                        </a>
                    </div>

                    <div class="menu-item ">
                        <a class="menu-link @if (request()->is('admin/offs/inviter')) active @endif"
                           href="{{ route('admin.off.inviterCodes') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-percent fs-3"></i>
                                </span>
                            <span class="menu-title">تخفیف کد معرف</span>
                        </a>
                    </div>

                    <div class="menu-item ">
                        <a class="menu-link @if (request()->is('admin/pricing')) active @endif"
                           href="{{ route('admin.pricing') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-coins fs-3"></i>
                                </span>
                            <span class="menu-title">تعرفه ها</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.bankAccounts') }}"
                           class="menu-link @if (request()->is('admin/financials/bank-accounts')) active @endif">
                                 <span class="menu-icon">
                                    <i class="fa fa-percent fs-3"></i>
                                </span>
                            <span class="menu-title">حساب بانکی</span>
                        </a>
                    </div>

                </div>
            </div>


            <div class="menu-item @if (!isset(auth()->user()->admin_permissions->settings)) d-none @endif">
                <div class="menu-content pt-8 pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">تنظیمات</span>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/version/*')) hover show @endif
                        @if (!isset(auth()->user()->admin_permissions->notification)) d-none @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/version')) active @endif"
                           href="{{ route('admin.version') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-bell fs-3"></i>
                                </span>
                            <span class="menu-title">ورژن</span>
                        </a>
                    </div>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/foundation/*')) hover show @endif
                        @if (!isset(auth()->user()->admin_permissions->notification)) d-none @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/foundation/notification')) active @endif"
                           href="{{ route('admin.notification') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-bell fs-3"></i>
                                </span>
                            <span class="menu-title">ارسال نوتیفیکیشن</span>
                        </a>
                    </div>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/foundation/*')) hover show @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/foundation/telSupportTags')) active @endif"
                           href="{{ route('admin.telSupportTags') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-headphones fs-3"></i>
                                </span>
                            <span class="menu-title">تگ های مشاوره تلفنی</span>
                        </a>
                    </div>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/votes')) hover show @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/votes')) active @endif"
                           href="{{ route('admin.votes')}}">
                                <span class="menu-icon">
                                    <i class="fa fa-headphones fs-3"></i>
                                </span>
                            <span class="menu-title">نظر سنجی ها</span>
                        </a>
                    </div>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/foundation/*')) hover show @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/foundation/comments')) active @endif"
                           href="{{ route('admin.comment') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-comment fs-3"></i>
                                </span>
                            <span class="menu-title">کامنت تجربیات کاربران</span>
                        </a>
                    </div>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/foundation/*')) hover show @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/foundation/accepteds')) active @endif"
                           href="{{ route('admin.accepteds') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-graduation-cap fs-3"></i>
                                </span>
                            <span class="menu-title">پذیرفته شدگان</span>
                        </a>
                    </div>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/foundation/*')) hover show @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/foundation/faq')) active @endif"
                           href="{{ route('admin.faq') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-question fs-3"></i>
                                </span>
                            <span class="menu-title">سوالات متداول</span>
                        </a>
                    </div>
                </div>


                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/foundation/*')) hover show @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/foundation/settings')) active @endif"
                           href="{{ route('admin.settings') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-phone fs-3"></i>
                                </span>
                            <span class="menu-title">تماس باما</span>
                        </a>
                    </div>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/foundation/*')) hover show @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/foundation/teams')) active @endif"
                           href="{{ route('admin.teams') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-users-cog fs-3"></i>
                                </span>
                            <span class="menu-title">تیم ما </span>
                        </a>
                    </div>
                </div>

                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/foundation/collaborations') || request()->is('admin/foundation/cfaq')) hover show @endif
                 @if (!isset(auth()->user()->admin_permissions->universities)) d-none @endif">

                        <span class="menu-link @if (request()->is('admin/foundation/collaborations') || request()->is('admin/foundation/cfaq')) active hover show @endif">
                            <span class="menu-icon">
                                <i class="fa fa-handshake fs-3"></i>
                            </span>
                            <span class="menu-title">درخواست همکاری</span>
                            <span class="menu-arrow"></span>
                        </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a href="{{ route('admin.collaborations') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                <span class="menu-title"> درخواست ها </span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a href="{{ route('admin.cfaq') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                <span class="menu-title">موقعیت های شغلی</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion @if (request()->is('admin/import/*')) hover show @endif">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->is('admin/settings/import')) active @endif"
                           href="{{ route('admin.imports') }}">
                                <span class="menu-icon">
                                    <i class="fa fa-phone fs-3"></i>
                                </span>
                            <span class="menu-title">بارگذاری</span>
                        </a>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
</div>
