<?php

namespace App\Http\Controllers;

use App\Models\Acceptance;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Models\Upload;
use App\Models\User;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use setasign\Fpdi\Fpdi;
use App\Models\UserDuty;
use App\Models\Transaction;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use PDF;

class HomeController extends Controller
{
    function imageLogo()
    {
        $file = public_path('logo.png');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function favicon()
    {
        $file = public_path('favicon.png');
        $type = 'image/png';
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function resumeAdminFile($id)
    {
        if (is_file(public_path('uploads/resumeAdminFile/' . $id)))
            $file = public_path('uploads/resumeAdminFile/' . $id);
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function adminCommentPhoto($id)
    {
        if (is_file(public_path('uploads/adminComment/' . $id . '.png')))
            $file = public_path('uploads/adminComment/' . $id . '.png');
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function newAcceptedsPhoto($id)
    {
        if (is_file(public_path('uploads/newAcceptedsPhoto/' . $id . '.png')))
            $file = public_path('uploads/newAcceptedsPhoto/' . $id . '.png');
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function newAcceptedsVideo($id)
    {
        if (is_file(public_path('uploads/newAcceptedsVideo/' . $id . '.mp4')))
            $file = public_path('uploads/newAcceptedsVideo/' . $id . '.mp4');
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function imageUser($id, $ua)
    {
        if (is_file(public_path('uploads/avatar/' . $id . '.jpg'))) {
            $file = public_path('uploads/avatar/' . $id . '.jpg');
            $type = 'image/jpeg';
        } else {
            if (isset($_GET['isDark']) and $_GET['isDark'] == 1) {
                $file = public_path('avatar_dark.png');
            } else {
                $user = User::find($id);
                if ($user->darkmode == 1 || isset($_GET['dark']) and $_GET['dark'] == 1) {
                    $file = public_path('avatar_dark.png');
                } else {
                    $file = public_path('avatar_light.png');
                }
            }
            $type = 'image/svg+xml';
        }
        header('Content-Type:' . $type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function templateImage($id)
    {
        if (is_file(public_path('uploads/templateImage/' . $id . '.jpg')))
            $file = public_path('uploads/templateImage/' . $id . '.jpg');
        else
            $file = public_path('avatar_light.png');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function imageLevel($id, $ua)
    {
        if (is_file(public_path('uploads/level/' . $id . '.jpg')))
            $file = public_path('uploads/level/' . $id . '.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function fileLevel($id, $pos)
    {
        $files = glob(public_path('uploads/level-file/' . $id . '/' . $pos . '.*'));
        header('Content-Type:' . mime_content_type($files[0]));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($files[0]));
        readfile($files[0]);
    }

    function imageTeam($id, $ua)
    {
        if (is_file(public_path('uploads/team/' . $id . '.jpg')))
            $file = public_path('uploads/team/' . $id . '.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function teamHeader($ua)
    {
        if (is_file(public_path('uploads/teamHeader.jpg')))
            $file = public_path('uploads/teamHeader.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function imageUniversity($id, $ua)
    {
        if (is_file(public_path('uploads/university/' . $id . '.jpg')))
            $file = public_path('uploads/university/' . $id . '.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($file));
        header('Cache-Control: no-cache, must-revalidate');
        readfile($file);
    }

    function logoUniversity($id, $ua)
    {
        if (is_file(public_path('uploads/university/' . $id . '_logo.jpg')))
            $file = public_path('uploads/university/' . $id . '_logo.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function logoAcceptanceUniversity($id, $ua)
    {
        if (is_file(public_path('uploads/university/' . $id . '_acceptance_logo.jpg')))
            $file = public_path('uploads/university/' . $id . '_acceptance_logo.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function resumeCollaboration($id)
    {
        if (is_file(public_path('uploads/resumeCollaboration/' . $id . '.pdf')))
            $file = public_path('uploads/resumeCollaboration/' . $id . '.pdf');
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function imageWebinar($id, $ua)
    {
        if (is_file(public_path('uploads/webinar/' . $id . '.jpg')))
            $file = public_path('uploads/webinar/' . $id . '.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function imageWebinarOrganizer($id, $ua)
    {
        if (is_file(public_path('uploads/webinar/' . $id . '_organizer.jpg')))
            $file = public_path('uploads/webinar/' . $id . '_organizer.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function webinarBanner($id, $ua)
    {
        if (is_file(public_path('uploads/webinar/' . $id . '_banner.jpg')))
            $file = public_path('uploads/webinar/' . $id . '_banner.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function webinarReceipt($id)
    {
        if (is_file(public_path('uploads/webinarReceipt/' . $id . '.jpg')))
            $file = public_path('uploads/webinarReceipt/' . $id . '.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . mime_content_type($file));
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function imageVisa($id)
    {
        if (is_file(public_path('uploads/acepted/' . $id . '_visa.jpg'))) {
            $file = public_path('uploads/acepted/' . $id . '_visa.jpg');
//        else
//            $file = public_path('NoImage.jpg');
            $type = 'image/jpeg';
            header('Content-Type:' . $type);
            header('Content-Length: ' . filesize($file));
            readfile($file);
        } else {
            return NULL;
        }
    }

    function imageAcceptance($id, $pos)
    {
        if (is_file(public_path('uploads/acepted/' . $id . '_acceptance_' . $pos . '.jpg')))
            $file = public_path('uploads/acepted/' . $id . '_acceptance_' . $pos . '.jpg');
        else
            abort(404);
        //$file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function madrak($id)
    {
        if (is_file(public_path('uploads/madarek/' . $id . '.pdf'))) {
            $file = public_path('uploads/madarek/' . $id . '.pdf');
        } elseif (is_file(public_path('uploads/madarek/' . $id . '.jpg'))) {
            $file = public_path('uploads/madarek/' . $id . '.jpg');
        } elseif (is_file(public_path('uploads/madarek/' . $id . '.rar'))) {
            $file = public_path('uploads/madarek/' . $id . '.rar');
        } elseif (is_file(public_path('uploads/madarek/' . $id . '.zip'))) {
            $file = public_path('uploads/madarek/' . $id . '.zip');
        } else {
            return 'فایل یافت نشد';
        }

        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function userMadrak($type)
    {
        if (is_file(public_path('uploads/madarek/' . $type . '.pdf')))
            $file = public_path('uploads/madarek/' . $type . '.pdf');
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function imageResume($id)
    {
        if (is_file(public_path('uploads/resumeImage/' . $id . '.jpg')))
            $file = public_path('uploads/resumeImage/' . $id . '.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function resumeUserFile($id)
    {
        if (is_file(public_path('uploads/resumeUserFile/' . $id . '.pdf')))
            $file = public_path('uploads/resumeUserFile/' . $id . '.pdf');
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function motivationAdminFile($id)
    {
        if (is_file(public_path('uploads/motivationAdminFile/' . $id)))
            $file = public_path('uploads/motivationAdminFile/' . $id);
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function writerFile($id)
    {
        if (is_file(public_path('uploads/WriterFile/' . $id)))
            $file = public_path('uploads/WriterFile/' . $id);
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));

        return response()->download($file);
    }


    function imageMotivation($id)
    {
        if (is_file(public_path('uploads/motivationImage/' . $id . '.jpg')))
            $file = public_path('uploads/motivationImage/' . $id . '.jpg');
        else
            $file = public_path('NoImage.jpg');
        $type = 'image/jpeg';
        header('Content-Type:' . $type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function motivationUserFile($id)
    {
        if (is_file(public_path('uploads/motivationUserFile/' . $id . '.pdf')))
            $file = public_path('uploads/motivationUserFile/' . $id . '.pdf');
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function resumeAdminAttachment($id, $pos)
    {
        if (is_file(public_path('uploads/resumeAdminAttachment/' . $id . "_{$pos}.pdf")))
            $file = public_path('uploads/resumeAdminAttachment/' . $id . "_{$pos}.pdf");
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function motivationAdminAttachment($id, $pos)
    {
        if (is_file(public_path('uploads/motivationAdminAttachment/' . $id . "_{$pos}.pdf")))
            $file = public_path('uploads/motivationAdminAttachment/' . $id . "_{$pos}.pdf");
        else
            return 'فایل یافت نشد';
        header('Content-Type:' . mime_content_type($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    function applyFile($userid, $id)
    {
        $user = User::find($userid);
        if (!$user)
            return NULL;
        if (!is_file(public_path('uploads/applies/' . $userid . '/' . $id . '.pdf')))
            return NULL;
        $file = public_path('uploads/applies/' . $userid . '/' . $id . '.pdf');
        $transaction = Invoice::where('user_id', $userid)->where('invoice_type', 'final')->where('payment_status', 'paid')->first();
        if ($transaction) {
            header('Content-Type:' . mime_content_type($file));
            header('Cache-Control: no-cache, must-revalidate');
            header('Content-Length: ' . filesize($file));
            readfile($file);
        } else {
//			header('Content-Type:' . mime_content_type($file));
//			header('Cache-Control: no-cache, must-revalidate');
//			header('Content-Length: ' . filesize($file));
//			readfile($file);
//			return;
            $filecontent = file_get_contents($file);
            if (preg_match("/^%PDF-1.7/", $filecontent)) {
                $text_image = public_path('watermark.png');;
                $pdf = new Fpdi();
                $pagecount = $pdf->setSourceFile($file);
                // Add watermark image to PDF pages
                for ($i = 1; $i <= $pagecount; $i++) {
                    $tpl = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tpl);
                    $pdf->addPage();
                    $pdf->useTemplate($tpl, 1, 1, $size['width'], $size['height'], true);
                    //Put the watermark
                    $xxx_final = ($size['width'] - 250);
                    $yyy_final = ($size['height'] - 300);
                    $pdf->Image($text_image, $xxx_final, $yyy_final, 0, 0, 'png');
                }
                // Output PDF with watermark
                $pdf->Output();
            }
        }
    }

    function checkDuties()
    {
        Log::info(" CJ : " . date('Y-m-d H:i:s'));
        $date = date('Y-m-d');
        $duties = UserDuty::where('status', 1)->where('deadline', '<', $date)->get();
        foreach ($duties as $duty) {
            $duty->status = 2;
            $duty->save();
        }
    }

    function generateContract($id)
    {
        $user = User::find($id);
        if (!$user->contract_code) {
            $lastContractCode = User::where('contract_code', '<>', 'null')->orderBy('contract_code','desc')->latest()->first();
            $numberAsString=strval($lastContractCode->contract_code);
            $number = substr($numberAsString, 4);

            $number = intval($number);
            $number= $number+1;
            $year=MyHelpers::numberToEnglish(JDF::jdate('Y'));
            $numLength = strlen((string)$number);
            if ($numLength == 1)
                $number =$year. '0000' . $number;
            elseif ($numLength == 2)
                $number =$year. '000' . $number;
            elseif ($numLength == 3)
                $number=$year. '00' . $number;
            elseif ($numLength == 4)
                $number=$year. '0' . $number;
            else
                $number=$year.$number;

            $user->contract_code = $number;
            $user->save();
        }

        $acceptance = Acceptance::where('user_Id', $id)->first();
        if ($user->type === 2) {

            if ($user->contract_type === 'gov-university') {
                $pdf = PDF::loadView('contract.gov-university.1000', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 1000 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'all-university') {
                $pdf = PDF::loadView('contract.all-university.1000', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 1000 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'pri-university') {
                if($user->id==26339){
                    $pdf = PDF::loadView('contract.pri-university.old-1000', [
                        'user' => $user,
                        'acceptance' => $acceptance
                    ], [], [
                        'subject' => $user->id . 'قرارداد 1000 یورو ',
                    ]);
                }else{
                    $pdf = PDF::loadView('contract.pri-university.1000', [
                        'user' => $user,
                        'acceptance' => $acceptance
                    ], [], [
                        'subject' => $user->id . 'قرارداد 1000 یورو ',
                    ]);
                }

                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'pre-pay') {
                $pdf = PDF::loadView('contract.pre-pay.1000', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 1000 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'os-contract') {

            } elseif ($user->contract_type === 'work-contract') {

            }

        } else {
            if ($user->contract_type === 'gov-university') {
                $pdf = PDF::loadView('contract.gov-university.800', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 800 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'all-university') {
                $pdf = PDF::loadView('contract.all-university.800', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 800 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'pri-university') {
                $pdf = PDF::loadView('contract.pri-university.800', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 800 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'pre-pay') {
                $pdf = PDF::loadView('contract.pre-pay.800', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 800 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'os-contract') {

            } elseif ($user->contract_type === 'work-contract') {

            }
        }
    }
    function generateContractOld($id)
    {
        $user = User::find($id);
        if (!$user->contract_code) {

            $lastContractCode = User::orderBy('contract_code', 'DESC')->where('contract_code', '<>', 'null')->first();
            $code = intval(substr($lastContractCode->contract_code, 4, 4)) + 1;
            $numLength = strlen(intval(substr($lastContractCode->contract_code, 4, 4)) + 1);
            $number = '';
            if ($numLength === 1)
                $number .= '0000' . $code;
            elseif ($numLength === 2)
                $number .= '000' . $code;
            elseif ($numLength === 3)
                $number .= '00' . $code;
            elseif ($numLength === 4)
                $number .= '0' . $code;
            else
                $number = $code;

            $user->contract_code = MyHelpers::numberToEnglish(JDF::jdate('Y')) . $number;
            $user->save();
        }

        $acceptance = Acceptance::where('user_Id', $id)->first();
        if ($user->type === 2) {

            if ($user->contract_type === 'gov-university') {
                $pdf = PDF::loadView('contract.gov-university.1000', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 1000 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'all-university') {
                $pdf = PDF::loadView('contract.all-university.1000', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 1000 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'pri-university') {
                if($user->id==26339){
                    $pdf = PDF::loadView('contract.pri-university.old-1000', [
                        'user' => $user,
                        'acceptance' => $acceptance
                    ], [], [
                        'subject' => $user->id . 'قرارداد 1000 یورو ',
                    ]);
                }else{
                    $pdf = PDF::loadView('contract.pri-university.1000', [
                        'user' => $user,
                        'acceptance' => $acceptance
                    ], [], [
                        'subject' => $user->id . 'قرارداد 1000 یورو ',
                    ]);
                }

                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'pre-pay') {
                $pdf = PDF::loadView('contract.pre-pay.1000', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 1000 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'os-contract') {

            } elseif ($user->contract_type === 'work-contract') {

            }

        } else {
            if ($user->contract_type === 'gov-university') {
                $pdf = PDF::loadView('contract.gov-university.800', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 800 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'all-university') {
                $pdf = PDF::loadView('contract.all-university.800', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 800 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'pri-university') {
                $pdf = PDF::loadView('contract.pri-university.800', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 800 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'pre-pay') {
                $pdf = PDF::loadView('contract.pre-pay.800', [
                    'user' => $user,
                    'acceptance' => $acceptance
                ], [], [
                    'subject' => $user->id . 'قرارداد 800 یورو ',
                ]);
                return $pdf->stream($user->id . ' ' . $acceptance->created_at . '.pdf');
            } elseif ($user->contract_type === 'os-contract') {

            } elseif ($user->contract_type === 'work-contract') {

            }
        }
    }
}
