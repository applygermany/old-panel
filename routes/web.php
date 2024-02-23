<?php

use App\Providers\JDF;
use App\Providers\SMS;
use App\Models\Upload;
use setasign\Fpdi\Fpdi;
use App\Models\UserSupervisor;
use App\Mail\MailVerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


ini_set('display_errors', E_ERROR);
error_reporting(E_ERROR);
date_default_timezone_set("Asia/Tehran");
//Arians Routes
Route::get('test/arian/calcAllPrices',[\App\Http\Controllers\ArianController::class,'calcAllPrices']);
Route::get('test/arian/reminderEmail',[\App\Http\Controllers\ArianController::class,'reminderEmail']);
Route::get('test/arian/generatePurePdf',[\App\Http\Controllers\ArianController::class,'generatePurePdf']);
Route::get('test/arian',[\App\Http\Controllers\ArianController::class,'test']);
Route::get('test/arian/getUsersExel',[\App\Http\Controllers\ArianController::class,'getUsersExel']);
Route::get('test/arian/doTheDebug/{id}',[\App\Http\Controllers\ArianController::class,'doTheDebug']);
Route::get('test/arian/checkSms',[\App\Http\Controllers\ArianController::class,'checkSms']);
Route::get('test/arian/checkEmail/{name}/{lastname}/{sendToEmail}',[\App\Http\Controllers\ArianController::class,'checkEmail']);
Route::get('test/arian/removeSameRowFromTblResumeEducationRecords/{id}/{hash}',[\App\Http\Controllers\ArianController::class,'removeSameRowFromTblResumeEducationRecords']);
//Arians Routes
Route::get('test/exports/generateInvoiceCode', [\App\Http\Controllers\EhsanController::class, 'generateInvoiceCode']);
Route::get('test/exports/contracts', [\App\Http\Controllers\EhsanController::class, 'exportData']);
Route::get('test/users/convert', [\App\Http\Controllers\EhsanController::class, 'convertUser']);
Route::get('test/users/exports', [\App\Http\Controllers\EhsanController::class, 'exportUser']);
Route::get('test/users/export2', [\App\Http\Controllers\EhsanController::class, 'exportUser2']);
Route::get('test/users/no-comment', [\App\Http\Controllers\EhsanController::class, 'noComment']);
Route::get('test/import/database', [\App\Http\Controllers\EhsanController::class, 'importDatabase']);
Route::get('test/didar/import/user', [\App\Http\Controllers\EhsanController::class, 'importUsersToDidar']);
Route::get('test/webinar/email', [\App\Http\Controllers\EhsanController::class, 'sendWebinarEmail']);
Route::get('test/contracts/code', [\App\Http\Controllers\EhsanController::class, 'generateContractCode']);
Route::get('test/send/email', [\App\Http\Controllers\EhsanController::class, 'sendEmail']);
Route::get('test/add/team', [\App\Http\Controllers\EhsanController::class, 'addTeam']);
Route::get('test/invoice/description', [\App\Http\Controllers\EhsanController::class, 'invoiceDescription']);
Route::get('test/users/normal', [\App\Http\Controllers\EhsanController::class, 'userNormal']);
Route::get('test/users/daily', [\App\Http\Controllers\EhsanController::class, 'userDaily']);
Route::get('test/users/telSupport', [\App\Http\Controllers\EhsanController::class, 'userTelSupport']);
Route::get('test/invoices/bank', [\App\Http\Controllers\EhsanController::class, 'bankInvoices']);
Route::get('test/contracts/sign', [\App\Http\Controllers\EhsanController::class, 'contractsSign']);
Route::get('test/telSupport/null', [\App\Http\Controllers\EhsanController::class, 'nullTelSupport']);
Route::get('test/telSupport/null2', [\App\Http\Controllers\EhsanController::class, 'nullTelSupport2']);
Route::get('test/teamAssign', [\App\Http\Controllers\EhsanController::class, 'teamAssign']);
Route::get('test/updateMaxUniversity', [\App\Http\Controllers\EhsanController::class, 'updateMaxUniversity']);


Route::get('/convert_users', function () {
    return 'already converted';
    $users = DB::table('wp_users')->get();
    foreach ($users as $user) {
        $first_name = DB::table('wp_usermeta')->where('user_id', $user->ID)->where('meta_key', 'first_name')->get()->first->meta_value;
        $last_name = DB::table('wp_usermeta')->where('user_id', $user->ID)->where('meta_key', 'last_name')->get()->first->meta_value;
        $acceptance = DB::table('wp_german_acceptance')->where('user_id', $user->ID)->count();
        $insert = DB::table('users')->insert([
            'user_id' => $user->ID,
            'email' => $user->user_email,
            'firstname' => $first_name->meta_value,
            'lastname' => $last_name->meta_value,
            'password' => '',
            'verified' => 1,
            'type' => ($acceptance > 0 ? 2 : 1),
        ]);
        var_dump($insert);
        echo "<br>";
        echo "<br>";
    }
});

Route::get('/add_users', function () {
    return;
    $users = \App\Models\User::all();
    foreach ($users as $user) {
        if ($user->type == 2) {
            $support = new UserSupervisor();
            $support->user_id = $user->id;
            $support->supervisor_id = 26;
            $support->save();
        }
    }
});
Route::get('/add_phones', function () {
    return;
    $acceptances = \App\Models\Acceptance::all();
    $i = 0;
    foreach ($acceptances as $acceptance) {
        $user = \App\Models\User::find($acceptance->user_id);
        if ($user) {
            if ($user->mobile) {
                $acceptance->phone = $user->mobile;
                $i++;
                echo $i . "<br>";
                if ($acceptance->save()) {
                    echo "done";
                    echo "<br>";
                }
            }
        }
    }
});
Route::get('/rename_files', function () {
    function move_file($path, $to)
    {
        if (copy($path, $to)) {
            unlink($path);
            return true;
        } else {
            return false;
        }
    }

    $files = array_diff(scandir(public_path('uploads/madarek/')), ['.', '..']);
    foreach ($files as $file) {
        $fileFound = Upload::find(str_replace('.pdf', '', $file));
        if (!$fileFound) {
            var_dump(move_file(public_path("uploads/madarek/$file"), public_path("uploads/madarek/german-az-iran/$file")));
            echo "<br>";
        }
    }
});
Route::get('/convert_acceptances', function () {
    return 'convert_done';
    function fa_to_en($number)
    {
        if (empty($number)) return '0';
        $en = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        $fa = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
        return str_replace($fa, $en, $number);
    }

    $tels = DB::table('wp_usermeta')->where('meta_key', 'tel')->get();
    $i = 0;
    foreach ($tels as $tel) {
        $user = DB::table('users')->where('user_id', $tel->user_id)->get()->first;
        $user = $user->id;
        $mobile = str_replace(" ", "", $tel->meta_value);
        if (strlen($mobile) >= 10) {
            if (strlen($mobile) > 10) {
                $mobile = substr($mobile, 1);
            }
            if (strlen($mobile) > 10) {
                $mobile = substr($mobile, 1);
            }
            if (strlen($mobile) > 10) {
                $mobile = substr($mobile, 1);
            }
            var_dump($mobile);
            echo "<br>";
            var_dump($user->id);
            try {
                $insert = DB::table('users')->where('id', $user->id)->update([
                    'mobile' => $mobile,
                ]);
            } catch (Exception $exception) {
                $insert = false;
            }
        }
        $i++;
        echo $i . '<br>';
        if ($insert) {
            var_dump(true);
            echo "<br>";
        }
    }
});
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
});
Route::get('/test2', [\App\Http\Services\V1\User\TelSupportService::class, 'TelSupportTimer']);
Route::get('/test_sms', function () {
    $sms = new \App\Providers\SMS();
});
Route::get('/send_mail/{string}/{id}', function ($string, $id) {
    return;
    $mail = new MailVerificationCode("tksignup", [$id]);
    $result = \App\Models\User::sendMail($mail, $string, 'Tackle');
    return $result;
});
Route::get('/contract_test/{id}', function ($id) {
    $user = \App\Models\User::find($id);
    $acceptance = $user->acceptances->first();
    function utf8_strlen($str)
    {
        return preg_match_all('/[\x00-\x7F\xC0-\xFD]/', $str, $dummy);
    }

///
///
    function fagd($str, $z = "", $method = 'normal')
    {

        $p_chars = [
            'آ' => ['ﺂ', 'ﺂ', 'آ'],
            'ا' => ['ﺎ', 'ﺎ', 'ا'],
            'ب' => ['ﺐ', 'ﺒ', 'ﺑ'],
            'پ' => ['ﭗ', 'ﭙ', 'ﭘ'],
            'ت' => ['ﺖ', 'ﺘ', 'ﺗ'],
            'ث' => ['ﺚ', 'ﺜ', 'ﺛ'],
            'ج' => ['ﺞ', 'ﺠ', 'ﺟ'],
            'چ' => ['ﭻ', 'ﭽ', 'ﭼ'],
            'ح' => ['ﺢ', 'ﺤ', 'ﺣ'],
            'خ' => ['ﺦ', 'ﺨ', 'ﺧ'],
            'د' => ['ﺪ', 'ﺪ', 'ﺩ'],
            'ذ' => ['ﺬ', 'ﺬ', 'ﺫ'],
            'ر' => ['ﺮ', 'ﺮ', 'ﺭ'],
            'ز' => ['ﺰ', 'ﺰ', 'ﺯ'],
            'ژ' => ['ﮋ', 'ﮋ', 'ﮊ'],
            'س' => ['ﺲ', 'ﺴ', 'ﺳ'],
            'ش' => ['ﺶ', 'ﺸ', 'ﺷ'],
            'ص' => ['ﺺ', 'ﺼ', 'ﺻ'],
            'ض' => ['ﺾ', 'ﻀ', 'ﺿ'],
            'ط' => ['ﻂ', 'ﻄ', 'ﻃ'],
            'ظ' => ['ﻆ', 'ﻈ', 'ﻇ'],
            'ع' => ['ﻊ', 'ﻌ', 'ﻋ'],
            'غ' => ['ﻎ', 'ﻐ', 'ﻏ'],
            'ف' => ['ﻒ', 'ﻔ', 'ﻓ'],
            'ق' => ['ﻖ', 'ﻘ', 'ﻗ'],
            'ک' => ['ﻚ', 'ﻜ', 'ﻛ'],
            'گ' => ['ﮓ', 'ﮕ', 'ﮔ'],
            'ل' => ['ﻞ', 'ﻠ', 'ﻟ'],
            'م' => ['ﻢ', 'ﻤ', 'ﻣ'],
            'ن' => ['ﻦ', 'ﻨ', 'ﻧ'],
            'و' => ['ﻮ', 'ﻮ', 'ﻭ'],
            'ی' => ['ﯽ', 'ﯿ', 'ﯾ'],
            'ك' => ['ﻚ', 'ﻜ', 'ﻛ'],
            'ي' => ['ﻲ', 'ﻴ', 'ﻳ'],
            'أ' => ['ﺄ', 'ﺄ', 'ﺃ'],
            'ؤ' => ['ﺆ', 'ﺆ', 'ﺅ'],
            'إ' => ['ﺈ', 'ﺈ', 'ﺇ'],
            'ئ' => ['ﺊ', 'ﺌ', 'ﺋ'],
            'ة' => ['ﺔ', 'ﺘ', 'ﺗ'],
        ];
        $nastaligh = [
            'ه' => ['ﮫ', 'ﮭ', 'ﮬ'],
        ];
        $normal = [
            'ه' => ['ﻪ', 'ﻬ', 'ﻫ'],
        ];
        $mp_chars = ['آ', 'ا', 'د', 'ذ', 'ر', 'ز', 'ژ', 'و', 'أ', 'إ', 'ؤ'];
        $ignorelist = [
            '',
            'ٌ',
            'ٍ',
            'ً',
            'ُ',
            'ِ',
            'َ',
            'ّ',
            'ٓ',
            'ٰ',
            'ٔ',
            'ﹶ',
            'ﹺ',
            'ﹸ',
            'ﹼ',
            'ﹾ',
            'ﹴ',
            'ﹰ',
            'ﱞ',
            'ﱟ',
            'ﱠ',
            'ﱡ',
            'ﱢ',
            'ﱣ',
        ];
        if ($method == 'nastaligh') {
            $p_chars = array_merge($p_chars, $nastaligh);
        } else {
            $p_chars = array_merge($p_chars, $normal);
        }
        $str_back = '';
        $output = '';
        $str_len = utf8_strlen($str);
        preg_match_all("/./u", $str, $ar);
        for ($i = 0; $i < $str_len; $i++) {
            $str1 = $ar[0][$i];
            if (in_array($ar[0][$i + 1], $ignorelist)) {
                $str_next = $ar[0][$i + 2];
                if ($i == 2) $str_back = $ar[0][$i - 2];
                if ($i != 2) $str_back = $ar[0][$i - 1];
            } elseif (!in_array($ar[0][$i], $ignorelist)) {
                $str_next = $ar[0][$i + 1];
                if ($i != 0) $str_back = $ar[0][$i - 1];
            } else {
                if (isset($ar[0][$i + 1]) && !empty($ar[0][$i + 1])) {
                    $str_next = $ar[0][$i + 1];
                } else {
                    $str_next = $ar[0][$i - 1];
                }
                if ($i != 0) $str_back = $ar[0][$i - 2];
            }
            if (!in_array($str1, $ignorelist)) {
                if (array_key_exists($str1, $p_chars)) {
                    if (!$str_back or $str_back == " " or !array_key_exists($str_back, $p_chars)) {
                        if (!array_key_exists($str_back, $p_chars) and !array_key_exists($str_next, $p_chars)) $output = $str1 . $output;
                        else $output = $p_chars[$str1][2] . $output;
                        continue;
                    } elseif (array_key_exists($str_next, $p_chars) and array_key_exists($str_back, $p_chars)) {
                        if (in_array($str_back, $mp_chars) and array_key_exists($str_next, $p_chars)) {
                            $output = $p_chars[$str1][2] . $output;
                        } else {
                            $output = $p_chars[$str1][1] . $output;
                        }
                        continue;
                    } elseif (array_key_exists($str_back, $p_chars) and !array_key_exists($str_next, $p_chars)) {
                        if (in_array($str_back, $mp_chars)) {
                            $output = $str1 . $output;
                        } else {
                            $output = $p_chars[$str1][0] . $output;
                        }
                        continue;
                    }
                } elseif ($z == "fa") {

                    $number = [
                        "٠",
                        "١",
                        "٢",
                        "٣",
                        "٤",
                        "٥",
                        "٦",
                        "٧",
                        "٨",
                        "٩",
                        "۴",
                        "۵",
                        "۶",
                        "0",
                        "1",
                        "2",
                        "3",
                        "4",
                        "5",
                        "6",
                        "7",
                        "8",
                        "9",
                    ];
                    switch ($str1) {
                        case ")" :
                            $str1 = "(";
                            break;
                        case "(" :
                            $str1 = ")";
                            break;
                        case "}" :
                            $str1 = "{";
                            break;
                        case "{" :
                            $str1 = "}";
                            break;
                        case "]" :
                            $str1 = "[";
                            break;
                        case "[" :
                            $str1 = "]";
                            break;
                        case ">" :
                            $str1 = "<";
                            break;
                        case "<" :
                            $str1 = ">";
                            break;
                    }
                    if (in_array($str1, $number)) {
                        $num .= $str1;
                        $str1 = "";
                    }
                    if (!in_array($str_next, $number)) {
                        $str1 .= $num;
                        $num = "";
                    }
                    $output = $str1 . $output;
                } else {
                    if (($str1 == "،") or ($str1 == "؟") or ($str1 == "ء") or (array_key_exists($str_next, $p_chars) and array_key_exists($str_back, $p_chars)) or
                        ($str1 == " " and array_key_exists($str_back, $p_chars)) or ($str1 == " " and array_key_exists($str_next, $p_chars))) {
                        if ($e_output) {
                            $output = $e_output . $output;
                            $e_output = "";
                        }
                        $output = $str1 . $output;
                    } else {
                        $e_output .= $str1;
                        if (array_key_exists($str_next, $p_chars) or $str_next == "") {
                            $output = $e_output . $output;
                            $e_output = "";
                        }
                    }
                }
            } else {
                $output = $str1 . $output;
            }
            $str_next = NULL;
            $str_back = NULL;
        }
        return $output;
    }

    $str = fagd('سلام');
    $pdf = Pdf::loadView('contract.800', compact('user', 'acceptance', 'str'))->setPaper('a4')->setOptions(['defaultFont' => 'baloo']);;
    return $pdf->download('invoice.pdf');
    return view('contract.800', compact('user', 'acceptance', 'str'));
});

//TelSupportTimer
Route::get('test2', [\App\Http\Services\V1\User\TelSupportService::class, 'TelSupportTimer']);
Route::get('image/logo', [\App\Http\Controllers\HomeController::class, 'imageLogo'])->name('imageLogo');
Route::get('image/favicon', [\App\Http\Controllers\HomeController::class, 'favicon'])->name('favicon');
Route::get('image/user/{id}/{ua}', [\App\Http\Controllers\HomeController::class, 'imageUser'])->name('imageUser');
Route::get('image/level/{id}/{ua}', [\App\Http\Controllers\HomeController::class, 'imageLevel'])->name('imageLevel');
Route::get('file/level/{id}/{pos}', [\App\Http\Controllers\HomeController::class, 'fileLevel'])->name('fileLevel');
Route::get('image/team/{id}/{ua}', [\App\Http\Controllers\HomeController::class, 'imageTeam'])->name('imageTeam');
Route::get('image/teamHeader/{ua}', [\App\Http\Controllers\HomeController::class, 'teamHeader'])->name('teamHeader');
Route::get('image/university/{id}/{ua}', [
    \App\Http\Controllers\HomeController::class,
    'imageUniversity',
])->name('imageUniversity');
Route::get('logo/university/{id}/{ua}', [
    \App\Http\Controllers\HomeController::class,
    'logoUniversity',
])->name('logoUniversity');
Route::get('logo/university/acceptance/{id}/{ua}', [
    \App\Http\Controllers\HomeController::class,
    'logoAcceptanceUniversity',
])->name('logoAcceptanceUniversity');
Route::get('resume/collaboration/{id}', [
    \App\Http\Controllers\HomeController::class,
    'resumeCollaboration',
])->name('resumeCollaboration');
Route::get('image/webinar/{id}/{ua}', [
    \App\Http\Controllers\HomeController::class,
    'imageWebinar',
])->name('imageWebinar');
Route::get('image/webinarBanner/{id}/{ua}', [
    \App\Http\Controllers\HomeController::class,
    'webinarBanner',
])->name('webinarBanner');
Route::get('image/webinarOrganizer/{id}/{ua}', [
    \App\Http\Controllers\HomeController::class,
    'imageWebinarOrganizer',
])->name('imageWebinarOrganizer');
Route::get('image/webinarReceipt/{id}', [
    \App\Http\Controllers\HomeController::class,
    'webinarReceipt',
])->name('webinarReceipt');
Route::get('image/accepted/visa/{id}.jpg', [
    \App\Http\Controllers\HomeController::class,
    'imageVisa',
])->name('imageVisa');
Route::get('image/accepted/acceptance/{id}/{pos}.jpg', [
    \App\Http\Controllers\HomeController::class,
    'imageAcceptance',
])->name('imageAcceptance');
Route::get('uploads/madrak/{id}', [\App\Http\Controllers\HomeController::class, 'madrak'])->name('madrak');
Route::get('uploads/userMadrak/{type}', [
    \App\Http\Controllers\HomeController::class,
    'userMadrak',
])->name('userMadrak');
Route::get('image/resume/{id}', [\App\Http\Controllers\HomeController::class, 'imageResume'])->name('imageResume');
Route::get('image/comment/{id}.png', [
    \App\Http\Controllers\HomeController::class,
    'adminCommentPhoto',
])->name('adminCommentPhoto');
Route::get('image/newAccepteds/{id}.png', [
    \App\Http\Controllers\HomeController::class,
    'newAcceptedsPhoto',
])->name('newAcceptedsPhoto');
Route::get('video/newAccepteds/{id}', [
    \App\Http\Controllers\HomeController::class,
    'newAcceptedsVideo',
])->name('newAcceptedsVideo');
Route::get('resumeUserFile/{id}', [
    \App\Http\Controllers\HomeController::class,
    'resumeUserFile',
])->name('resumeUserFile');
Route::get('resumeMainFile/{id}', [
    \App\Http\Controllers\HomeController::class,
    'resumeAdminFile',
])->name('resumeMainFile');
Route::get('motivationMainFile/{id}', [
    \App\Http\Controllers\HomeController::class,
    'motivationAdminFile',
])->name('motivationMainFile');
Route::get('writerFile/{id}', [
    \App\Http\Controllers\HomeController::class,
    'writerFile',
])->name('writerFile');
Route::get('image/motivation/{id}', [
    \App\Http\Controllers\HomeController::class,
    'imageMotivation',
])->name('imageMotivation');
Route::get('motivationUserFile/{id}', [
    \App\Http\Controllers\HomeController::class,
    'motivationUserFile',
])->name('motivationUserFile');
Route::get('templateImage/{id}', [\App\Http\Controllers\HomeController::class, 'templateImage'])->name('templateImage');
Route::get('adminAttachment/resume/{id}/{pos}', [
    \App\Http\Controllers\HomeController::class,
    'resumeAdminAttachment',
])->name('resumeAdminAttachment');
Route::get('adminAttachment/motivation/{id}/{pos}', [
    \App\Http\Controllers\HomeController::class,
    'motivationAdminAttachment',
])->name('motivationAdminAttachment');
Route::get('applyFile/{userId}/{id}', [\App\Http\Controllers\HomeController::class, 'applyFile'])->name('applyFile');
Route::get('checkDuties', [\App\Http\Controllers\HomeController::class, 'checkDuties'])->name('checkDuties');

Route:: group(['middleware' => ['CheckLogin']], function () {
    Route::get('admin/login', [
        \App\Http\Controllers\Admin\LoginController::class,
        'loginForm',
    ])->name('admin.loginForm');
    Route::post('admin/login/check', [
        \App\Http\Controllers\Admin\LoginController::class,
        'login',
    ])->name('admin.login');
});

Route:: group([
    'prefix' => 'admin',
    'middleware' => ['CheckAdmin', 'CheckAdminAccess'],
    'namespace' => 'Admin',
], function () {
    /************************************
     ************ Dashboard *************
     ************************************/

    Route::get('dashboard/{month?}', [
        \App\Http\Controllers\Admin\DashboardController::class,
        'dashboard',
    ])->name('admin.dashboard');
    Route::get('logout', [\App\Http\Controllers\Admin\DashboardController::class, 'logout'])->name('admin.logout');
    /************************************
     ************** Admins **************
     ************************************/
    Route::get('admins', [\App\Http\Controllers\Admin\AdminController::class, 'admins'])->name('admin.admins');
    Route::get('chatSection', [\App\Http\Controllers\Admin\DashboardController::class, 'chatSection'])->name('admin.chat');
    Route::get('admins/comments', [\App\Http\Controllers\Admin\AdminController::class, 'getAdminComments'])->name('admin.getAdminComments');
    Route::get('admins/comments/delete/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteComment'])->name('admin.deleteTelComment');
    Route::post('getAdmins', [
        \App\Http\Controllers\Admin\AdminController::class,
        'getAdmins',
    ])->name('admin.getAdmins');
    Route::post('admins/save', [
        \App\Http\Controllers\Admin\AdminController::class,
        'saveAdmin',
    ])->name('admin.saveAdmin');
    Route::get('admins/edit/{id}', [
        \App\Http\Controllers\Admin\AdminController::class,
        'editAdmin',
    ])->name('admin.editAdmin');
    Route::post('admins/update', [
        \App\Http\Controllers\Admin\AdminController::class,
        'updateAdmin',
    ])->name('admin.updateAdmin');
    Route::get('admins/activate/{id}', [
        \App\Http\Controllers\Admin\AdminController::class,
        'activateAdmin',
    ])->name('admin.activateAdmin');
    //Admin Profile
    Route::get('admins/profile/{id}', [
        \App\Http\Controllers\Admin\AdminController::class,
        'adminProfile',
    ])->name('admin.adminProfile');
    Route::get('admins/teams', [
        \App\Http\Controllers\Admin\AdminController::class,
        'adminsTeam',
    ])->name('admin.adminsTeam');
    Route::get('admins/teams/list/{id}', [
        \App\Http\Controllers\Admin\AdminController::class,
        'adminsTeamList',
    ])->name('admin.adminsTeamList');
    Route::get('admins/teams/add/{id}', [
        \App\Http\Controllers\Admin\AdminController::class,
        'adminsTeamAdd',
    ])->name('admin.adminsTeamAdd');
    Route::get('admins/teams/delete/{id}', [
        \App\Http\Controllers\Admin\AdminController::class,
        'adminsTeamDelete',
    ])->name('admin.adminsTeamDelete');
    Route::get('admins/teams/addSupport/{expertId}/{supportId}', [
        \App\Http\Controllers\Admin\AdminController::class,
        'adminsTeamAddSupport',
    ])->name('admin.adminsTeamAddSupport');
    /************************************
     ************** Users ***************
     ************************************/
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'users'])->name('admin.users');
    Route::get('users/information', [\App\Http\Controllers\Admin\UserController::class, 'usersInformation'])->name('admin.usersInformation');
    Route::get('users_information', [\App\Http\Controllers\Admin\UserController::class, 'usersInformation'])->name('admin.usersInformation');
    Route::post('getUsers', [\App\Http\Controllers\Admin\UserController::class, 'getUsers'])->name('admin.getUsers');
    Route::post('getUsersInformation', [\App\Http\Controllers\Admin\UserController::class, 'getUsersInformation'])->name('admin.getUsersInformation');
    Route::post('users/save', [\App\Http\Controllers\Admin\UserController::class, 'saveUser'])->name('admin.saveUser');
    Route::post('users/getUser', [\App\Http\Controllers\Admin\UserController::class, 'getUserInfo'])->name('admin.getUserInfo');
    Route::get('users/userExcelExports',[\App\Http\Controllers\Admin\UserController::class,'userExcelExports'])->name('admin.user.exel');
    Route::post('users/exportUsersExel',[\App\Http\Controllers\Admin\UserController::class,'exportUsersExcel'])->name('admin.export.user.exel');
    Route::get('users/edit/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'editUser',
    ])->name('admin.editUser');
    Route::post('users/update', [
        \App\Http\Controllers\Admin\UserController::class,
        'updateUser',
    ])->name('admin.updateUser');
    Route::get('users/activate/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'activateUser',
    ])->name('admin.activateUser');
    Route::get('users/delete/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'deleteUser',
    ])->name('admin.deleteUser');
    Route::post('users/exportUsers', [
        \App\Http\Controllers\Admin\UserController::class,
        'exportUsers',
    ])->name('admin.exportUsers');
    Route::post('users/exportParticipants', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'exportParticipants',
    ])->name('admin.exportParticipants');
    // Profile
    Route::get('users/profile/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'userProfile',
    ])->name('admin.userProfile');
    Route::get('users/profile/information/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'userProfileInformation',
    ])->name('admin.userProfileInformation');
    Route::get('users_information/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'userProfileInformation',
    ])->name('admin.userProfileInformation');
    Route::post('users/addUserUniversity', [
        \App\Http\Controllers\Admin\UserController::class,
        'addUserUniversity',
    ])->name('admin.addUserUniversity');
    Route::get('users/delete/userUniversity/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'deleteUserUniversity',
    ])->name('admin.deleteUserUniversity');
    Route::get('universities/levelStatus/delete/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'deleteUserUniversityLevelStatus',
    ])->name('admin.deleteUserUniversityLevelStatus');
    Route::get('users/delete/userTelSupport/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'deleteUserTelSupport',
    ])->name('admin.deleteUserTelSupport');
    Route::get('users/delete/telSupport/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'deleteTelSupport',
    ])->name('admin.deleteTelSupport');
    Route::get('users/delete/telSupportUser/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'deleteTelSupportUser',
    ])->name('admin.deleteTelSupportUser');
    Route::post('userAcceptances', [
        \App\Http\Controllers\Admin\UserController::class,
        'userAcceptances',
    ])->name('admin.userAcceptances');
    Route::get('getUserAcceptance', [
        \App\Http\Controllers\Admin\UserController::class,
        'getUserAcceptance',
    ])->name('admin.getUserAcceptance');
    Route::get('setUpdateAcceptance/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'setUpdateAcceptance',
    ])->name('admin.setUpdateAcceptance');
    Route::post('updateAcceptance/{id}', [
        \App\Http\Controllers\Admin\UserController::class,
        'saveUserAcceptance',
    ])->name('admin.saveUserAcceptance');
    Route::post('userUniversities', [
        \App\Http\Controllers\Admin\UserController::class,
        'userUniversities',
    ])->name('admin.userUniversities');
    Route::post('userTransactions', [
        \App\Http\Controllers\Admin\UserController::class,
        'userTransactions',
    ])->name('admin.userTransactions');
    Route::post('userTransactionsInformation', [
        \App\Http\Controllers\Admin\UserController::class,
        'userTransactionsInformation',
    ])->name('admin.userTransactionsInformation');
    Route::post('userUserTelSupports', [
        \App\Http\Controllers\Admin\UserController::class,
        'userUserTelSupports',
    ])->name('admin.userUserTelSupports');
    Route::post('userUploads', [
        \App\Http\Controllers\Admin\UserController::class,
        'userUploads',
    ])->name('admin.userUploads');
    /************************************
     ************* Uploads **************
     ************************************/
    Route::get('uploads', [\App\Http\Controllers\Admin\UploadController::class, 'uploads'])->name('admin.uploads');
    Route::post('getUploads', [
        \App\Http\Controllers\Admin\UploadController::class,
        'getUploads',
    ])->name('admin.getUploads');
    Route::get('uploads/delete/{id}', [
        \App\Http\Controllers\Admin\UploadController::class,
        'deleteUpload',
    ])->name('admin.deleteUpload');
    /************************************
     ****** ResumesAndMotivations *******
     ************************************/
    Route::get('resumes', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'resumes',
    ])->name('admin.resumes');
    Route::post('resumes/change/writer/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'addWriterToResume',
    ])->name('admin.addWriterToResume');
    Route::post('getResumes', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'getResumes',
    ])->name('admin.getResumes');
    Route::get('resumes/delete/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'deleteResume',
    ])->name('admin.deleteResume');
    Route::get('resumes/show/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'showResume',
    ])->name('admin.showResume');
    Route::get('resumes/acceptFile/{id}/{file}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'acceptResumeFile',
    ])->name('admin.acceptResumeFile');
    Route::get('resumes/deleteFile/{id}/{file}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'deleteResumeFile',
    ])->name('admin.deleteResumeFile');
    Route::get('resumes/edit/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'editResume',
    ])->name('admin.editResume');
    Route::post('resumes/update', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'updateResume',
    ])->name('admin.updateResume');
    Route::post('resumes/upload', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'uploadResumeFromAdmin',
    ])->name('admin.uploadResumeFromAdmin');
    Route::get('resumes/excel/download/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'downloadResumeExcel',
    ])->name('admin.downloadResumeExcel');
    Route::get('resumes/preview/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'previewResume',
    ])->name('admin.previewResume');
    Route::get('resumes/preview/download/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'downloadResumePreview',
    ])->name('admin.downloadResumePreview');
    Route::get('resumeTemplates', [
        \App\Http\Controllers\Admin\ResumeTemplateController::class,
        'resumeTemplates',
    ])->name('admin.resumeTemplates');
    Route::get('resumeTemplates/delete/{id}', [
        \App\Http\Controllers\Admin\ResumeTemplateController::class,
        'deleteResumeTemplate',
    ])->name('admin.deleteResumeTemplate');
    Route::get('resumeTemplates/edit/{id}', [
        \App\Http\Controllers\Admin\ResumeTemplateController::class,
        'editResumeTemplate',
    ])->name('admin.editResumeTemplate');
    Route::post('getResumeTemplates', [
        \App\Http\Controllers\Admin\ResumeTemplateController::class,
        'getResumeTemplates',
    ])->name('admin.getResumeTemplates');
    Route::post('saveResumeTemplate', [
        \App\Http\Controllers\Admin\ResumeTemplateController::class,
        'saveResumeTemplate',
    ])->name('admin.saveResumeTemplate');
    Route::post('updateResumeTemplates', [
        \App\Http\Controllers\Admin\ResumeTemplateController::class,
        'updateResumeTemplates',
    ])->name('admin.updateResumeTemplates');
    Route::get('resumeTemplateColors', [
        \App\Http\Controllers\Admin\ResumeTemplateColorController::class,
        'resumeTemplateColors',
    ])->name('admin.resumeTemplateColors');
    Route::post('getResumeTemplateColors', [
        \App\Http\Controllers\Admin\ResumeTemplateColorController::class,
        'getResumeTemplateColors',
    ])->name('admin.getResumeTemplateColors');
    Route::post('resumeTemplateColors/save', [
        \App\Http\Controllers\Admin\ResumeTemplateColorController::class,
        'saveColor',
    ])->name('admin.saveColor');
    Route::get('motivations', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'motivations',
    ])->name('admin.motivations');
    Route::post('motivations/change/writer/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'addWriterToMotivation',
    ])->name('admin.addWriterToMotivation');
    Route::post('getMotivations', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'getMotivations',
    ])->name('admin.getMotivations');
    Route::get('motivations/delete/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'deleteMotivation',
    ])->name('admin.deleteMotivation');
    Route::get('motivations/show/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'showMotivation',
    ])->name('admin.showMotivation');
    Route::get('motivations/edit/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'editMotivation',
    ])->name('admin.editMotivation');
    Route::post('motivations/update', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'updateMotivation',
    ])->name('admin.updateMotivation');
    Route::post('motivations/upload', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'uploadMotivationFromAdmin',
    ])->name('admin.uploadMotivationFromAdmin');
    Route::get('motivations/acceptFile/{id}/{file}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'acceptMotivationFile',
    ])->name('admin.acceptMotivationFile');
    Route::get('motivations/excel/download/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'downloadMotivationExcel',
    ])->name('admin.downloadMotivationExcel');
    Route::get('motivations/preview/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'showMotivationPreview',
    ])->name('admin.showMotivationPreview');
    Route::get('motivations/preview/download/{id}', [
        \App\Http\Controllers\Admin\ResumeAndMotivationController::class,
        'downloadMotivationPreview',
    ])->name('admin.downloadMotivationPreview');
    /************************************
     *********** ApplyLevels ************
     ************************************/
    Route::get('applyLevels', [
        \App\Http\Controllers\Admin\ApplyLevelController::class,
        'applyLevels',
    ])->name('admin.applyLevels');
    Route::post('getApplyLevels', [
        \App\Http\Controllers\Admin\ApplyLevelController::class,
        'getApplyLevels',
    ])->name('admin.getApplyLevels');
    Route::post('applyLevels/save', [
        \App\Http\Controllers\Admin\ApplyLevelController::class,
        'saveApplyLevel',
    ])->name('admin.saveApplyLevel');
    Route::get('applyLevels/edit/{id}', [
        \App\Http\Controllers\Admin\ApplyLevelController::class,
        'editApplyLevel',
    ])->name('admin.editApplyLevel');
    Route::post('applyLevels/update', [
        \App\Http\Controllers\Admin\ApplyLevelController::class,
        'updateApplyLevel',
    ])->name('admin.updateApplyLevel');
    Route::get('applyLevels/delete/{id}', [
        \App\Http\Controllers\Admin\ApplyLevelController::class,
        'deleteApplyLevel',
    ])->name('admin.deleteApplyLevel');
    /************************************
     *********** ApplyPhases ************
     ************************************/
    Route::get('applyPhases', [
        \App\Http\Controllers\Admin\ApplyPhaseController::class,
        'applyPhases',
    ])->name('admin.applyPhases');
    Route::get('applyPhases/edit/{id}', [
        \App\Http\Controllers\Admin\ApplyPhaseController::class,
        'editApplyPhase',
    ])->name('admin.editApplyPhase');
    Route::post('applyPhases/update', [
        \App\Http\Controllers\Admin\ApplyPhaseController::class,
        'updateApplyPhase',
    ])->name('admin.updateApplyPhase');
    Route::get('applyPhases/delete/{id}', [
        \App\Http\Controllers\Admin\ApplyPhaseController::class,
        'deleteApplyPhase',
    ])->name('admin.deleteApplyPhase');
    /************************************
     *********** Universities ***********
     ************************************/
    Route::get('universities', [
        \App\Http\Controllers\Admin\UniversityController::class,
        'universities',
    ])->name('admin.universities');
    Route::post('getUniversities', [
        \App\Http\Controllers\Admin\UniversityController::class,
        'getUniversities',
    ])->name('admin.getUniversities');
    Route::post('universities/save', [
        \App\Http\Controllers\Admin\UniversityController::class,
        'saveUniversity',
    ])->name('admin.saveUniversity');
    Route::get('universities/edit/{id}', [
        \App\Http\Controllers\Admin\UniversityController::class,
        'editUniversity',
    ])->name('admin.editUniversity');
    Route::post('universities/update', [
        \App\Http\Controllers\Admin\UniversityController::class,
        'updateUniversity',
    ])->name('admin.updateUniversity');
    Route::get('universities/delete/{id}', [
        \App\Http\Controllers\Admin\UniversityController::class,
        'deleteUniversity',
    ])->name('admin.deleteUniversityLevelStatus');
    /************************************
     *********** Financials *************
     ************************************/
    // Prices
    Route::get('pricing', [\App\Http\Controllers\Admin\FinanialController::class, 'pricing'])->name('admin.pricing');
    Route::post('pricing/update', [
        \App\Http\Controllers\Admin\FinanialController::class,
        'updatePrice',
    ])->name('admin.updatePrice');

    //Banks
    Route::get('financials/bank-accounts', [
        \App\Http\Controllers\Admin\BankAccountController::class,
        'bankAccounts',
    ])->name('admin.bankAccounts');
    Route::post('financials/bank-accounts/store', [
        \App\Http\Controllers\Admin\BankAccountController::class,
        'saveBankAccount',
    ])->name('admin.saveBankAccount');
    Route::get('financials/bank-accounts/edit/{id}', [
        \App\Http\Controllers\Admin\BankAccountController::class,
        'editBankAccount',
    ])->name('admin.editBankAccount');
    Route::post('financials/bank-accounts/update', [
        \App\Http\Controllers\Admin\BankAccountController::class,
        'updateBankAccount',
    ])->name('admin.updateBankAccount');

    // Invoices
    Route::get('financials/invoices/create', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'create',
    ])->name('admin.invoicesCreate');
    Route::get('financials/invoices/{type}', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'invoices',
    ])->name('admin.invoices');

    Route::post('financials/invoices/save', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'saveInvoice',
    ])->name('admin.saveInvoice');
    Route::post('financials/invoices/search', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'invoiceSearch',
    ])->name('admin.invoiceSearch');
    Route::get('financials/invoices/confirm/{id}', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'confirmInvoice',
    ])->name('admin.confirmInvoice');
    Route::get('financials/invoices/edit/user/{id}', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'editInvoiceUser',
    ])->name('admin.editInvoiceUser');
    Route::post('financials/invoices/edit/user/update', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'updateEditUser',
    ])->name('admin.updateEditUser');
    Route::get('financials/invoices/delete/{id}', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'deleteInvoice',
    ])->name('admin.deleteInvoice');
    Route::post('financials/invoices/payment-date', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'invoicePaymentDate',
    ])->name('admin.invoicePaymentDate');
    Route::get('financials/invoices/accept/manager/{id}', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'acceptInvoiceManager',
    ])->name('admin.acceptInvoiceManager');
    Route::post('financials/invoices/exports', [\App\Http\Controllers\Admin\InvoiceController::class, 'exportInvoice'])->name('admin.exportInvoice');
    Route::get('financials/invoices/show/{id}', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'showInvoice',
    ])->name('admin.showInvoice');
    Route::get('financials/invoices/edit/{id}', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'editInvoice',
    ])->name('admin.editInvoice');
    Route::post('financials/invoices/update', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'updateInvoiceManager',
    ])->name('admin.updateInvoiceManager');
    Route::get('financials/invoices/decline/{id}', [
        \App\Http\Controllers\Admin\InvoiceController::class,
        'declineInvoice',
    ])->name('admin.declineInvoice');


    // Transactions
    Route::get('financials/transactions', [
        \App\Http\Controllers\Admin\FinanialController::class,
        'transactions',
    ])->name('admin.transactions');
    Route::post('getTransactions', [
        \App\Http\Controllers\Admin\FinanialController::class,
        'getTransactions',
    ])->name('admin.getTransactions');
    Route::post('getTransactionsExport', [
        \App\Http\Controllers\Admin\FinanialController::class,
        'exportTransactions',
    ])->name('admin.exportTransactions');
    // Offs
    Route::get('offs', [\App\Http\Controllers\Admin\OffController::class, 'offs'])->name('admin.offs');
    Route::post('getOffs', [\App\Http\Controllers\Admin\OffController::class, 'getOffs'])->name('admin.getOffs');
    Route::post('offs/save', [\App\Http\Controllers\Admin\OffController::class, 'saveOff'])->name('admin.saveOff');
    Route::get('offs/edit/{id}', [\App\Http\Controllers\Admin\OffController::class, 'editOff'])->name('admin.editOff');
    Route::post('offs/update', [
        \App\Http\Controllers\Admin\OffController::class,
        'updateOff',
    ])->name('admin.updateOff');
    Route::get('offs/activate/{id}', [
        \App\Http\Controllers\Admin\OffController::class,
        'activateOff',
    ])->name('admin.activateOff');
    Route::get('offs/delete/{id}', [
        \App\Http\Controllers\Admin\OffController::class,
        'deleteOff',
    ])->name('admin.deleteOff');
    Route::post('offs/checkCode', [
        \App\Http\Controllers\Admin\OffController::class,
        'checkCode',
    ])->name('admin.checkCode');
    Route::post('offs/exports', [
        \App\Http\Controllers\Admin\OffController::class,
        'export',
    ])->name('admin.off.exports');
    Route::get('offs/exports/off/{id}', [
        \App\Http\Controllers\Admin\OffController::class,
        'exportOff',
    ])->name('admin.off.exportOff');

    ///Inviter
    Route::get('offs/inviter', [
        \App\Http\Controllers\Admin\OffController::class,
        'inviterCodes',
    ])->name('admin.off.inviterCodes');
    Route::post('offs/inviter/save', [\App\Http\Controllers\Admin\OffController::class, 'saveOffInviter'])->name('admin.saveOffInviter');
    Route::get('offs/activation/{id}', [
        \App\Http\Controllers\Admin\OffController::class,
        'activateInviterOff',
    ])->name('admin.activateInviterOff');
    Route::get('offs/inviter/delete/{id}', [
        \App\Http\Controllers\Admin\OffController::class,
        'deleteOffInviter',
    ])->name('admin.deleteOffInviter');
    Route::get('offs/exports/inviter/{id}', [
        \App\Http\Controllers\Admin\OffController::class,
        'exportOffInviter',
    ])->name('admin.off.exportOffInviter');
    Route::post('offs/inviter/search', [
        \App\Http\Controllers\Admin\OffController::class,
        'searchInviterCodes',
    ])->name('admin.getOffsInviter');
    Route::post('offs/exports/inviter', [
        \App\Http\Controllers\Admin\OffController::class,
        'exportInviter',
    ])->name('admin.off.exports.inviter');
    Route::post('offs/checkCodeInviter', [
        \App\Http\Controllers\Admin\OffController::class,
        'checkCodeInviter',
    ])->name('admin.checkCodeInviter');

    /************************************
     ************ VERSION ************
     ************************************/
    // Version
    Route::get('version', [\App\Http\Controllers\Admin\VersionController::class, 'version'])->name('admin.version');
    Route::post('version/edit', [\App\Http\Controllers\Admin\VersionController::class, 'editVersion'])->name('admin.editVersion');
    /************************************
     ************ Foundation ************
     ************************************/
    // Teams
    Route::get('foundation/teams', [\App\Http\Controllers\Admin\TeamController::class, 'teams'])->name('admin.teams');
    Route::get('getTeams', [\App\Http\Controllers\Admin\TeamController::class, 'getTeams'])->name('admin.getTeams');
    Route::post('updateTeamHeader', [
        \App\Http\Controllers\Admin\TeamController::class,
        'updateTeamHeader',
    ])->name('admin.updateTeamHeader');
    Route::post('foundation/teams/save', [
        \App\Http\Controllers\Admin\TeamController::class,
        'saveTeam',
    ])->name('admin.saveTeam');
    Route::get('foundation/teams/edit/{id}', [
        \App\Http\Controllers\Admin\TeamController::class,
        'editTeam',
    ])->name('admin.editTeam');
    Route::post('foundation/teams/sort', [
        \App\Http\Controllers\Admin\TeamController::class,
        'sortTeam',
    ])->name('admin.sortTeam');
    Route::post('foundation/teams/update', [
        \App\Http\Controllers\Admin\TeamController::class,
        'updateTeam',
    ])->name('admin.updateTeam');
    Route::get('foundation/teams/delete/{id}', [
        \App\Http\Controllers\Admin\TeamController::class,
        'deleteTeam',
    ])->name('admin.deleteTeam');
    // Faq
    Route::get('foundation/faq', [\App\Http\Controllers\Admin\FaqController::class, 'faq'])->name('admin.faq');
    Route::get('foundation/faq/edit/{id}', [
        \App\Http\Controllers\Admin\FaqController::class,
        'editFaq',
    ])->name('admin.editFaq');
    Route::post('foundation/faq/update', [
        \App\Http\Controllers\Admin\FaqController::class,
        'updateFaq',
    ])->name('admin.updateFaq');
    Route::post('foundation/faq/save', [
        \App\Http\Controllers\Admin\FaqController::class,
        'saveFaq',
    ])->name('admin.saveFaq');
    Route::get('foundation/faq/delete/{id}', [
        \App\Http\Controllers\Admin\FaqController::class,
        'deleteFaq',
    ])->name('admin.deleteFaq');
    Route::post('foundation/faq/sort', [
        \App\Http\Controllers\Admin\FaqController::class,
        'sortFaq',
    ])->name('admin.sortFaq');
    // Collaborations Faq
    Route::get('foundation/cfaq', [\App\Http\Controllers\Admin\CFaqController::class, 'cfaq'])->name('admin.cfaq');
    Route::get('foundation/cfaq/edit/{id}', [
        \App\Http\Controllers\Admin\CFaqController::class,
        'editCFaq',
    ])->name('admin.editCFaq');
    Route::post('foundation/cfaq/update', [
        \App\Http\Controllers\Admin\CFaqController::class,
        'updateCFaq',
    ])->name('admin.updateCFaq');
    Route::post('foundation/cfaq/save', [
        \App\Http\Controllers\Admin\CFaqController::class,
        'saveCFaq',
    ])->name('admin.saveCFaq');
    Route::get('foundation/cfaq/delete/{id}', [
        \App\Http\Controllers\Admin\CFaqController::class,
        'deleteCFaq',
    ])->name('admin.deleteCFaq');
    // Accepteds
    Route::get('foundation/accepteds', [
        \App\Http\Controllers\Admin\AcceptedController::class,
        'accepteds',
    ])->name('admin.accepteds');
    Route::post('getAccepteds', [
        \App\Http\Controllers\Admin\AcceptedController::class,
        'getAccepteds',
    ])->name('admin.getAccepteds');
    Route::get('foundation/accepteds/edit/{id}', [
        \App\Http\Controllers\Admin\AcceptedController::class,
        'editAccepted',
    ])->name('admin.editAccepted');
    Route::post('foundation/accepteds/update', [
        \App\Http\Controllers\Admin\AcceptedController::class,
        'updateAccepted',
    ])->name('admin.updateAccepted');
    Route::post('foundation/accepteds/save', [
        \App\Http\Controllers\Admin\AcceptedController::class,
        'saveAccepted',
    ])->name('admin.saveAccepted');
    Route::get('foundation/accepteds/delete/{id}', [
        \App\Http\Controllers\Admin\AcceptedController::class,
        'deleteAccepted',
    ])->name('admin.deleteAccepted');
    // Comments
    Route::get('foundation/comments', [
        \App\Http\Controllers\Admin\CommentController::class,
        'comment',
    ])->name("admin.comment");
    Route::get('foundation/comments/delete/{id}', [
        \App\Http\Controllers\Admin\CommentController::class,
        'deleteComment',
    ])->name("admin.deleteComment");
    Route::post('foundation/comments/save', [
        \App\Http\Controllers\Admin\CommentController::class,
        'saveComment',
    ])->name("admin.saveComment");
    Route::get('foundation/comments/edit/{id}', [
        \App\Http\Controllers\Admin\CommentController::class,
        'editComment',
    ])->name("admin.editComment");
    Route::post('foundation/comments/update', [
        \App\Http\Controllers\Admin\CommentController::class,
        'updateComment',
    ])->name("admin.updateComment");
    // Collaborations
    Route::get('foundation/collaborations', [
        \App\Http\Controllers\Admin\CollaborationController::class,
        'collaborations',
    ])->name('admin.collaborations');
    Route::post('getCollaborations', [
        \App\Http\Controllers\Admin\CollaborationController::class,
        'getCollaborations',
    ])->name('admin.getCollaborations');
    Route::get('foundation/collaborations/delete/{id}', [
        \App\Http\Controllers\Admin\CollaborationController::class,
        'deleteCollaboration',
    ])->name('admin.deleteCollaboration');
    // Settings
    Route::get('foundation/settings', [
        \App\Http\Controllers\Admin\DashboardController::class,
        'settings',
    ])->name('admin.settings');
    Route::post('foundation/updateSettings', [
        \App\Http\Controllers\Admin\DashboardController::class,
        'updateSettings',
    ])->name('admin.updateSettings');
    // telSupportTags
    Route::get('foundation/telSupportTags', [
        \App\Http\Controllers\Admin\DashboardController::class,
        'telSupportTags',
    ])->name('admin.telSupportTags');
    Route::post('foundation/saveTelSupportTag', [
        \App\Http\Controllers\Admin\DashboardController::class,
        'saveTelSupportTag',
    ])->name('admin.saveTelSupportTag');
    Route::get('foundation/deleteTelSupportTag/{id}', [
        \App\Http\Controllers\Admin\DashboardController::class,
        'deleteTelSupportTag',
    ])->name('admin.deleteTelSupportTag');
    // Notification
    Route::get('foundation/notification', [
        \App\Http\Controllers\Admin\DashboardController::class,
        'notification',
    ])->name('admin.notification');
    Route::post('foundation/sendNotification', [
        \App\Http\Controllers\Admin\DashboardController::class,
        'sendNotification',
    ])->name('admin.sendNotification');
    // Webinars
    Route::get('webinars', [\App\Http\Controllers\Admin\WebinarController::class, 'webinars'])->name('admin.webinars');
    Route::post('getWebinars', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'getWebinars',
    ])->name('admin.getWebinars');
    Route::post('webinars/save', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'saveWebinar',
    ])->name('admin.saveWebinar');
    Route::get('webinars/edit/{id}', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'editWebinar',
    ])->name('admin.editWebinar');
    Route::get('webinars/change/{id}', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'changeWebinarStatus',
    ])->name('admin.changeWebinarStatus');
    Route::post('webinars/update', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'updateWebinar',
    ])->name('admin.updateWebinar');
    Route::get('webinars/delete/{id}', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'deleteWebinar',
    ])->name('admin.deleteWebinar');
    Route::get('getSpecificWebinarUsers/{id}', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'getSpecificWebinarUsers',
    ])->name('admin.getSpecificWebinarUsers');
    Route::get('webinars/webinarsParticipation', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'webinarsParticipation',
    ])->name('admin.webinarsParticipation');
    Route::any('getWebinarsParticipation', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'getWebinarsParticipation',
    ])->name('admin.getWebinarsParticipation');
    Route::any('getWebinarsParticipationPagination', [
        \App\Http\Controllers\Admin\WebinarController::class,
        'getWebinarsParticipationPagination',
    ])->name('admin.getWebinarsParticipationPagination');
    // inviteCodes
    Route::get('foundation/invites', [
        \App\Http\Controllers\Admin\InviteController::class,
        'invites',
    ])->name('admin.invites');
    Route::post('getInvites', [
        \App\Http\Controllers\Admin\InviteController::class,
        'getInvites',
    ])->name('admin.getInvites');
    Route::post('getInvitesExport', [
        \App\Http\Controllers\Admin\InviteController::class,
        'exportInvites',
    ])->name('admin.exportInvites');

    /**
     * REPORTS
     */
    Route::get('reports/telSupports/result', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'telSupportResult',
    ])->name('admin.telSupportResult');
    Route::post('reports/telSupports/result/search', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'telSupportsResultSearch',
    ])->name('admin.telSupportsResultSearch');

    Route::get('reports/telSupports', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'telSupportsReport',
    ])->name('admin.telSupportsReport');
    Route::get('reports/telSupports/comments/{id}', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'telSupportsReportComments',
    ])->name('admin.telSupportComments');
    Route::post('reports/telSupports/search', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'telSupportsReportSearch',
    ])->name('admin.telSupportsReportSearch');
    Route::post('reports/telSupports/exports', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'telSupportsReportExport',
    ])->name('admin.telSupportsReportExport');

    //Work Experience
    Route::get('reports/workExperience', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'workExperience',
    ])->name('admin.workExperience');
    Route::post('reports/workExperience', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'getWorkExperience',
    ])->name('admin.getWorkExperience');
    Route::get('reports/workExperience/list/{id}', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'workExperienceList',
    ])->name('admin.workExperienceList');
    Route::get('reports/workExperience/users/{id}/{supervisorId}', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'workExperienceListUser',
    ])->name('admin.workExperienceListUser');
    Route::get('reports/workExperience/exports/{id}', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'workExperienceExport',
    ])->name('admin.workExperienceExport');
    Route::get('reports/workExperience/user/exports/{id}/{supervisorId}', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'workExperienceUserExport',
    ])->name('admin.workExperienceUserExport');

    Route::get('reports/contracts', [\App\Http\Controllers\Admin\ReportsController::class, 'contracts'])->name('admin.contracts');
    Route::post('reports/contracts/search', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'contractsSearch',
    ])->name('admin.contractsSearch');
    Route::post('reports/contracts/exports', [
        \App\Http\Controllers\Admin\ReportsController::class,
        'contractExport',
    ])->name('admin.contractExport');

    /**
     * Tel support reservation
     */
    Route::get('telSupport/experts', [\App\Http\Controllers\Admin\TelSupportController::class, 'telSupportExpert'])->name('admin.telSupportExpert');
    Route::get('telSupport/times/{id}', [\App\Http\Controllers\Admin\TelSupportController::class, 'telSupportsExpertTimes'])->name('admin.telSupportsExpertTimes');
    Route::post('telSupport/getTimes', [\App\Http\Controllers\Admin\TelSupportController::class, 'telSupportsExpertGetTimes'])->name('admin.telSupportsExpertGetTimes');
    Route::post('telSupport/time/reserve/{id}', [\App\Http\Controllers\Admin\TelSupportController::class, 'telSupportsReserveTime'])->name('admin.telSupportsReserveTime');
    Route::get('telSupport/time/choose/{id}', [\App\Http\Controllers\Admin\TelSupportController::class, 'telSupportsExpertChooseTime'])->name('admin.telSupportsExpertChooseTime');
    Route::post('telSupport/time/reserveTime', [\App\Http\Controllers\Admin\TelSupportController::class, 'telSupportsExpertReserveTime'])->name('admin.telSupportsExpertReserveTime');

});
Route::any('/admin/showVoteData', [
    \App\Http\Controllers\Api\V1\User\VoteController::class,
    'showVoteData',
])->name('admin.showVoteData');
Route::get('/admin/votes', [
    \App\Http\Controllers\Api\V1\User\VoteController::class,
    'expertVotes',
])->name('admin.votes');
Route::get('/admin/deleteVotes', [
    \App\Http\Controllers\Api\V1\User\VoteController::class,
    'deleteVotes',
])->name('admin.deleteVotes');
Route::get('/admin/downloadVotes', [
    \App\Http\Controllers\Api\V1\User\VoteController::class,
    'downloadVotes',
])->name('admin.downloadVotes');

/**
 * Generate Contract
 */
Route::get('/contract/{id}', [
    \App\Http\Controllers\HomeController::class,
    'generateContract',
]);

/**
 * Generate invoice
 */
Route::get('/invoice/{id}', [
    \App\Http\Controllers\Admin\InvoiceController::class,
    'generateInvoice',
])->name('admin.generateInvoice');


Route::get('/admin/import/database', [
    \App\Http\Controllers\Admin\DashboardController::class,
    'importData',
])->name('admin.imports');
Route::post('/admin/import/doing', [
    \App\Http\Controllers\Admin\DashboardController::class,
    'doImports',
])->name('admin.doImports');
