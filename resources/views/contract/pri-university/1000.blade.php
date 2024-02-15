@extends('contract.master-layout-pdf')@section('content')    <div class="pdfbody">        <htmlpageheader name="page-header">            <table id="header">                <tr>                    <td colspan="1"><img width="250" src="{{url('src/images/logo.svg')}}"></td>                    <td colspan="3" style="text-align: right">                        <p class="code-title-class">کد قرارداد</p>                        <br/>                        <p class="code-class">{{$user->contract_code}}</p>                    </td>                </tr>            </table>        </htmlpageheader>        <main>            <h1>قرارداد خدمات مشاوره اعزام دانشجو به کشور آلمان</h1>            <p>                این قرارداد بین موسسه غیر تجاری نوید فردای سروش (اپلای جرمنی) با شماره ثبت ۵۴۰۰۶ که از این پس طرف اول                قرارداد نامیده میشود و                <span>آقای / خانم : </span>                <span>{{$user->firstname}} {{$user->lastname}}</span>                <span>فرزند : </span>                {{--                <span>{{$user->father_name}}</span>--}}                <span>-----------------------</span>                <span>به شماره شناسنامه : </span>                {{--                <span>{{$acceptance->mellicode}}</span>--}}                <span>-----------------------</span>                <span>صادره از : </span>                {{--                <span> {{$acceptance->city}}</span>--}}                <span>---------------------</span>                <span>به نشانی : </span>                <span>-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</span>            </p>            <p>                که از این پس طرف دوم قرارداد نامیده میشود، منعقد میگردد و مفاد آن به استناد ماده ده قانون مدنی بین طرفین                و یا قائم آن لازم الاتباع است .            </p>            <div class="part">                <h2>ماده 1: موضوع قرارداد</h2>                <p>                    موضوع قرارداد عبارت از ارائه خدمات مربوط به مشاوره اعزام دانشجو به کشور آلمان می باشد که ارائه خدمات تحصیلی                    نظیر مشاوره، اخذ پذیرش مورد درخواست متقاضی و کمک جهت اخذ روادید تحصیلی می باشد.                </p>            </div>            <div class="part">                <h2>ماده 2: مدت قرارداد</h2>                <p>                    مدت اعتبار قرارداد از تاریخ امضاء قرارداد تا پایان یافتن تعهدات طرفین می باشد.                </p>            </div>            <div class="part">                <h2>ماده 3: مبلغ قرارداد</h2>                <p>                    حق الزحمه تیم اپلای جرمنی برای انجام تعهدات ذکر شده در ماده 5 معادل ۱۳۰۰ یورو میباشد که در چهارچوب                    شرایط مندرج در ماده ۴ تسویه خواهد شد.                </p>            </div>            <div class="part">                <h2>ماده 4: شرایط پرداخت مبلغ قرارداد</h2>                <p>۱-۴ مبلغ ۲۵۰ یورو پس از نگارش یا ویرایش رزومه و انگیزه نامه توسط طرف اول قرارداد.</p>                <p>۲-۴ مابقی مبلغ ذکر شده به عنوان حق الزحمه در ماده 3 به صورت یکجا، پس از اخذ اولین پذیرش تسویه                    خواهد شد.</p>                <p>۳-۴ امکان تسویه این مبلغ هم به صورت ارزی و هم به صورت معادل روز ریالی از طریق درگاه بانکی موجود در وبسایت اپلای جرمنی و                    یا واریز مستقیم به حساب و یا تحویل نقدی وجه وجود دارد.</p>            </div>            <div class="part">                <h2>ماده 5: تعهدات و مسئولیت‌ های طرف اول</h2>                <p>۱-۵ مشاوره و ارائه اطلاعات کامل، صحیح و مستند، به متقاضی در مورد شرایط ادامه تحصیل در دانشگاههای                    معتبر کشور آلمان و مورد تایید وزارت علوم، تحقیقات و فناوری و وزارت بهداشت ، درمان و آموزش پزشکی                    (حسب مورد)</p>                <p>۲-۵ ارائه اطلاعات واقعی و به هنگام به متقاضی در مورد مقررات و ضوابط دانشگاه مورد تقاضا چگونگی                    شرایط زندگی از جمله هزینه زندگی، سکونت و سایر اطلاعاتی که به طور متعارف در جهت تصمیم گیری متقاضی                    مؤثر می باشد</p>                <p> ۳-۵ بررسی صحت و تناسب مدارک مورد نیاز ارائه شده متقاضی.</p>                <p>                    (تبصره 1 : مسئولیت قانونی صحت مدارک مذکور با خود متقاضي مي‌باشد.)                </p>                <p>۴-۵ اقدام جهت اخذ پذيرش تحصيلي در یکی از مقاطع دانشگاهی کالج، کارشناسی و یا کارشناسی ارشد از                    دانشگاهای معتبر آلمان تا سقف 8 دانشگاه بعد از تایید طرف دوم قرارداد</p>                <p> ۵-۵ انجام مکاتبات با دانشگاه ها جهت تایید مدارک سفارت</p>                <p> ۶-۵ اصلاح یا نگارش انگیزه نامه و رزومه طرف دوم</p>                <p>  ۷-۵ تکمیل مدارک و فرم های مرتبط و ارایه اطلاعات کامل، صحیح و مستند در رابطه با مصاحیه سفارت براساس اطلاعات و مدارک ارائه شده توسط طرف دوم</p>                <p> ۸-۵ درخواست خوابگاه در صورت نیاز طرف دوم</p>                <p>۹-۵ تمدید پذیرش های اخذ شده در صورت نیاز طرف دوم تا نهایت یک ترم تحصیلی</p>                <p> ۱۰-۵ ثبت نام دانشگاه (درصورت آنلاین بودن پروسه ثبت نام)</p>                <p>۱۱-۵طرف اول پس از اخذ روادید و پایان اعتبار قرارداد، هیچگونه مسئولیتی در رابطه با ترک تحصیل،                    اخراج از دانشگاه، پناهندگی و یا هرگونه اعمال مجرمانه متقاضی نخواهد داشت.</p>                <p>(تبصره 1 : طرف اول موظف است حداقل به مدت يک سال پس از شروع به تحصیل متقاضی خدمات مشاوره ای را در                    کشور محل تحصیل به متقاضی ارائه نماید.)</p>                <p>۱۲-۵ طرف اول هیچگونه تعهدی بابت پرداخت هزینه های پست، ترجمه و تایید مدارک، درخواست ویزا،                    اپلیکیشن فی دانشگاه ها و هزینه یونی اسیست ندارد و پرداخت کلیه هزینه های مذکور به عهده طرف دوم                    قرارداد میباشد.</p>            </div>            <div class="part">                <h2>                    ماده 6: تعهدات و مسئولیت های طرف دوم                </h2>                <p>۱-۶ متقاضی با توجه به مفاد قرارداد منعقده موظف است کليه مدارک تایید شده تحصیلی مورد نیاز برای                    اخذ پذیرش از دانشگاه و اخذ روادید تحصیلی را در زمان مقرر به طرف اول قرارداد تسلیم نماید. مسئولیت                    هرگونه تاخیر در تحویل مدارک به عهده وی می باشد.</p>                <p>۲-۶متقاضي میبایست دارای گذرنامه معتبر جمهوری اسلامی ایران بوده و به هیچ دلیل ممنوع الخروج از                    کشور نباشد.</p>                <p>۳-۶ متقاضی موظف است بر مبنای ماده 4 این قرارداد حق الزحمه طرف اول را ظرف نهایتا ۱۰ روز پس از                    موعد مقرر پرداخت نماید.</p>                <p> ۴-۶ متقاضی موظف است در صورت انصراف از تحصیل یا عدم اجرای مفاد قرارداد و تمایل به فسخ قرارداد،                    موضوع را در اسرع وقت به صورت کتبی به طرف اول اعلام نماید. در غیر این صورت هر گونه مسئولیت ناشی                    از آن به عهده متقاضی خواهد بود.</p>                <p> (تبصره 1: بند فوق تنها قبل از تسلیم مدارک به طرف اول صادق است و امکان فسخ قرارداد پس از آن تنها                    با رعایت بند ۲ ماده 8 وجود دارد.)</p>                <p> ۵-۶ متقاضی موظف است از هرگونه اقدام موازی درخصوص موارد موضوع قرارداد (ماده یک) جهت مهاجرت به آلمان یا هر کشور دیگر توسط خود یا هر                    موسسه دیگری خودداری کند. در غیر این صورت امکان فسخ یک طرفه قرارداد توسط طرف اول قرارداد وجود                    دارد و طرف دوم موظف به پرداخت حق الزحمه طرف اول مطابق با ماده ۴ و در چهارچوب ماده ۵ خواهد بود.</p>                <p> ۶-۶ متقاضی موظف است پس از اخذ روادید تحصیلی، تصویر روادید تحصیلی خود را در اختیار طرف اول                    قرارداد قرار دهد.</p>                <p>                    (تبصره ۱: در صورت عدم تمایل طرف دوم، پس از اطلاع شفاهی به طرف اول، تصاویر به صورت شطرنجی و بدون درج                    نام متقاضی منتشر خواهد شد)                </p>                <p> ۷-۶ متقاضی با امضا این قرارداد موافقت خود را با اقدام برای دانشگاه های خصوصی کشور المان پس از تایید و انتخاب در سایت اپلای جرمنی اعلام میدارد.</p>            </div>            <br/><br/>            <div class="part">                <h2>ماده 7: اتمام قرارداد</h2>                <p>                    در صورتی که طرف اول و دوم به تعهدات خود، بر حسب موارد مندرج در ماده ۵ و ۶ عمل نمایند، قرارداد حاضر                    خاتمه یافته تلقی خواهد شد.                </p>            </div>            <div class="part">                <h2>                    ماده 8: فسخ قرارداد                </h2>                <p>۱-۸ در صورتی که طرف اول به تمامی تعهدات خود مطابق با ماده ۵ عمل کرده، ولی به هر علتی که منتسب به                    انجام تعهدات طرف اول نباشد، برای متقاضی روادید تحصیلی صادر نگردد، طرف اول هیچگونه مسئولیتی در                    این قبال ندارد.</p>                <p> ۲-۸ در صورتی که متقاضی به هر دلیلی که منتسب به وی می باشد پس از تسلیم مدارک از ادامه قرارداد                    منصرف شود ولی طرف اول به تمام تعهدات خود عمل کند، طرف دوم موظف است در اسرع وقت به هر وسيله ممكن                    مراتب انصراف و فسخ قرارداد را به صورت کتبی به طرف اول اعلام نماید. در این حالت طرف دوم میبایست                    پس از کسر ده درصد (10%) از مبلغ حق الزحمه، الباقی را در موعد مقرر پرداخت نماید.</p>                <p> ۳-۸ چنانچه طرف اول در هر زمان و در هر مرحله از اجراي تعهداتش بدون عذر موجه و قانونی نسبت به                    انجام آن قصور و تخلف نماید، متقاضی حق دارد قرارداد را به طور يکحانبه و بدون نیاز به هرگونه                    تشریفاتی فسخ نماید.</p>            </div>            <div class="part">                <h2>ماده 9: موارد اضطراری</h2>                <p>                    در مواردی که به علت بروز حوادث قهریه که غیر قابل پیش بینی و غیر قابل انتساب به طرف اول بوده و                    جلوگیری و یا رفع آن از عهده طرف اول خارج بوده، انجام تمام و یا بخشی از تعهدات موضوع قرارداد غیرممکن                    گردد، طرف اول مکلف است مراتب را در اسرع وقت با ارائه اسناد و مدارک مثبته به اطلاع متقاضی رسانده و                    درخواست تمدید مدت قرارداد را نماید، در مدت وجود اضطرار متوقف کننده، هیچ یک از طرفین حق ادعای خسارت از                    طرف دیگر را نخواهند داشت.                </p>            </div>            <div class="part">                <h2>ماده 10: حل اختلافات</h2>                <p>                    طرفین کوشش خواهند نمود، کليه اختلافات حاصل از اجرا یا تفسیر قرارداد را به صورت مذاکره حل و فصل                    نمایند، در غیر این صورت موضوع از طرق مراجعه به مراجع صالح قضایی پیگیری خواهد شد.                </p>            </div>            <div class="part">                <h2>ماده 11: نسخه های قرارداد</h2>                <p>                    این قرارداد در ۱۱ ماده و در دو نسخه تنظیم و به امضاء رسیده که بین طرفین مبادله گردیده است و کليه                    نسخه های آن اعتبار واحد دارند.                </p>            </div>            <table class="sign">                <tr style="text-align: center">                    <td colspan="1" style="width: 22%; text-align: center">                        <p class="text-span">مدیریت اپلای جرمنی</p><br/>                        <p class="text-span">سروش فدایی منش</p>        <br/>                        <p class="text-span">امضا</p>                       <br/>                        <img src="{{url('src/images/mohr.png')}}" width="100">                    </td>                    <td style="width: 38%">                        <img width="80" src="{{url('src/images/sign.png')}}" alt="">                    </td>                    <td colspan="2" style="width: 40%; text-align: center">                        <p class="text-span">متقاضی</p> <br/>                        <p class="text-span">نام و نام خانوادگی</p> <br/>                        <p class="text-span">{{$user->firstname}} {{$user->lastname}}</p> <br/>                        <p class="text-span">امضا</p> <br/>                        <p class="text-span">تاریخ</p>                    </td>                </tr>            </table>        </main>        <htmlpagefooter name="page-footer">            <div>                <table class="footer">                    <tr>                        <td style="width: 33.33%; text-align: left">                            <table style="text-align: left; width: 100%">                                <tr>                                    <td style="text-align: left"><p class="footer-p">021-91099888</p></td>                                    <td><img class="footer-icon" src="{{url('src/images/icons/phone-call.svg')}}"                                             alt=""/></td>                                </tr>                            </table>                        </td>                        <td style="width: 33.33%; text-align: left">                            <table style="text-align: left; width: 100%">                                <tr>                                    <td style="text-align: left"><p class="footer-p">info@ApplyGermany.net</p></td>                                    <td><img class="footer-icon" src="{{url('src/images/icons/mail.svg')}}" alt=""></td>                                </tr>                            </table>                        </td>                        <td style="width: 33.33%; text-align: left">                            <table style="text-align: left; width: 100%">                                <tr>                                    <td style="text-align: left"><p class="footer-p">ApplyGermany.Net</p></td>                                    <td><img class="footer-icon" src="{{url('src/images/icons/cloud.svg')}}" alt="">                                    </td>                                </tr>                            </table>                        </td>                    </tr>                </table>            </div>        </htmlpagefooter>    </div>@endsection