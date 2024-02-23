<?php

use Illuminate\Support\Facades\Route;

//4gjg
Route::get('logoUni', function () {

    $file = public_path("uploads/university.zip");;
    header('Content-Type:' . mime_content_type($file));
    header('Content-Length: ' . filesize($file));
    readfile($file);
});
Route::post('git-pull', function () {

    exec("cd ../laravel && git pull origin main 2>&1", $output, $return_var);
    print_r($output);
});

Route::any('/test', function () {
    var_dump('');
});

//

// Login Register
Route::group([
    'prefix' => 'v1'
], function ($router) {
    Route::post('signIn', [\App\Http\Controllers\Api\V1\LoginController::class, 'signIn']);
    Route::post('signUp', [\App\Http\Controllers\Api\V1\LoginController::class, 'signUp']);
    Route::post('resendCode', [\App\Http\Controllers\Api\V1\LoginController::class, 'resendCode']);
    Route::post('verify', [\App\Http\Controllers\Api\V1\LoginController::class, 'verify']);
    Route::post('completeSignUp', [\App\Http\Controllers\Api\V1\LoginController::class, 'completeSignUp']);
    Route::get('user', [\App\Http\Controllers\Api\V1\LoginController::class, 'getUser']);
    Route::post('sendRecoveryCode', [\App\Http\Controllers\Api\V1\LoginController::class, 'sendRecoveryCode']);
    Route::post('verifyRecoveryCode', [\App\Http\Controllers\Api\V1\LoginController::class, 'verifyRecoveryCode']);
    Route::post('recoverPassword', [\App\Http\Controllers\Api\V1\LoginController::class, 'recoverPassword']);
    Route::get('signOut', [\App\Http\Controllers\Api\V1\LoginController::class, 'signOut']);

    Route::get('settings', [\App\Http\Controllers\Api\V1\SiteController::class, 'settings']);

    Route::get('teams', [\App\Http\Controllers\Api\V1\SiteController::class, 'teams']);

    Route::post('faqs', [\App\Http\Controllers\Api\V1\SiteController::class, 'faqs']);
    Route::post('cfaqs', [\App\Http\Controllers\Api\V1\SiteController::class, 'cfaqs']);

    Route::post('webinar', [\App\Http\Controllers\Api\V1\SiteController::class, 'webinar']);
    Route::post('userWebinar', [\App\Http\Controllers\Api\V1\SiteController::class, 'userWebinar']);
    Route::get('webinarBanners', [\App\Http\Controllers\Api\V1\SiteController::class, 'webinarBanners']);
//    Route::get('user/webinar/{id}', [\App\Http\Controllers\Api\V1\SiteController::class, 'webinar']);
    Route::post('uploadWebinarReceipt', [\App\Http\Controllers\Api\V1\SiteController::class, 'uploadWebinarReceipt']);
    Route::post('submitWebinar', [\App\Http\Controllers\Api\V1\SiteController::class, 'submitWebinar']);

    Route::post('uploadResumeCollaboration', [\App\Http\Controllers\Api\V1\SiteController::class, 'uploadResumeCollaboration']);
    Route::post('sendCollaboration', [\App\Http\Controllers\Api\V1\SiteController::class, 'sendCollaboration']);


    Route::get('accepteds', [\App\Http\Controllers\Api\V1\SiteController::class, 'accepted']);
    Route::get('comments', [\App\Http\Controllers\Api\V1\SiteController::class, 'comments']);
    Route::get('contact', [\App\Http\Controllers\Api\V1\SiteController::class, 'contact']);
    Route::get('pricing', [\App\Http\Controllers\Api\V1\SiteController::class, 'pricing']);
    Route::get('resume/temps', [\App\Http\Controllers\Api\V1\SiteController::class, 'resumeTemps']);
    Route::get('idpay/verify', [\App\Http\Services\V1\User\InvoiceService::class, 'verifyPay']);
    Route::get('transaction/check/{type}', [\App\Http\Services\V1\User\InvoiceService::class, 'checkPay']);

    Route::get('transaction/code/{hash}', [\App\Http\Controllers\Api\V1\User\FinancialController::class, 'getTransaction']);

    Route::get('version/{panel}', [\App\Http\Controllers\Api\V1\SiteController::class, 'getVersion']);
    //arian apies

    Route::get('downloadAll/{id}', [\App\Http\Controllers\Api\V1\Expert\DocumentController::class, 'downloadAllDocs']);
});
Route::group(['middleware' => ['UserAuth']], function () {


    Route::group(['middleware' => ['NormalUser']], function () {
        /************************************
         ************ Acceptance ************
         ************************************/

        Route::get('v1/user/acceptance', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'acceptance']);
        Route::post('v1/user/submitPackage', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitPackage']);
        Route::post('v1/user/submitContinueCollege', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitContinueCollege']);
        Route::post('v1/user/submitStep1', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitStep1']);
        Route::post('v1/user/submitStep2', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitStep2']);
        Route::post('v1/user/submitStepBachelor', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitStepBachelor']);
        Route::post('v1/user/submitStepMaster', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitStepMaster']);
        Route::post('v1/user/submitMasterContinue', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitMasterContinue']);
        Route::post('v1/user/submitStep3', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitStep3']);
        Route::post('v1/user/submitStep4', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitStep4']);
        Route::post('v1/user/submitStep5', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitStep5']);
        Route::post('v1/user/submitStep6', [\App\Http\Controllers\Api\V1\User\AcceptanceController::class, 'submitStep6']);
    });
    Route::group(['middleware' => ['SpecialUser']], function () {
        /************************************
         ********** Universities ************
         ************************************/

        Route::post('v1/user/universities', [\App\Http\Controllers\Api\V1\User\UniversityController::class, 'universities']);
        Route::post('v1/user/chooseUniversity', [\App\Http\Controllers\Api\V1\User\UniversityController::class, 'chooseUniversity']);
        Route::get('v1/user/applyStatus', [\App\Http\Controllers\Api\V1\User\UniversityController::class, 'applyStatus']);

        /************************************
         ************* Uploads **************
         ************************************/
        Route::get('v1/user/getFileNameAndFormat/{type}/{id}',[\App\Http\Controllers\Api\V1\User\UploadController::class, 'getFileNameAndFormat']);
        Route::get('v1/user/getDownloadByFormat/{type}/{id}',[\App\Http\Controllers\Api\V1\User\UploadController::class, 'downloadFiles']);
        Route::get('v1/user/uploads', [\App\Http\Controllers\Api\V1\User\UploadController::class, 'uploads']);
        Route::get('v1/user/files', [\App\Http\Controllers\Api\V1\User\UploadController::class, 'files']);
        Route::post('v1/user/uploadMandatoryFile', [\App\Http\Controllers\Api\V1\User\UploadController::class, 'uploadMandatoryFile']);
        Route::post('v1/user/uploadFile', [\App\Http\Controllers\Api\V1\User\UploadController::class, 'uploadFile']);
        Route::post('v1/user/deleteUpload', [\App\Http\Controllers\Api\V1\User\UploadController::class, 'deleteUpload']);
    });
    /************************************
     ************ Dashboard *************
     ************************************/

    Route::get('v1/user/dashboard', [\App\Http\Controllers\Api\V1\User\DashboardController::class, 'dashboard']);
    Route::post('v1/user/updateProfile', [\App\Http\Controllers\Api\V1\User\DashboardController::class, 'updateProfile']);
    Route::post('v1/user/uploadImage', [\App\Http\Controllers\Api\V1\User\DashboardController::class, 'uploadImage']);
    Route::post('v1/user/changeEmailMobile', [\App\Http\Controllers\Api\V1\User\DashboardController::class, 'changeEmailMobile']);
    Route::post('v1/user/changeEmailMobileVerify', [\App\Http\Controllers\Api\V1\User\DashboardController::class, 'changeEmailMobileVerify']);
    Route::get('v1/user/changeEmailMobileResendCode', [\App\Http\Controllers\Api\V1\User\DashboardController::class, 'changeEmailMobileResendCode']);
    Route::post('v1/user/updatePassword', [\App\Http\Controllers\Api\V1\User\DashboardController::class, 'updatePassword']);
    Route::get('v1/user/changeDarkMode', [\App\Http\Controllers\Api\V1\User\DashboardController::class, 'changeDarkMode']);
    Route::post('v1/user/doneDuty', [\App\Http\Controllers\Api\V1\User\DashboardController::class, 'doneDuty']);

    /************************************
     *********** Apply Levels ***********
     ************************************/

    Route::get('v1/user/applyLevels', [\App\Http\Controllers\Api\V1\User\ApplyLevelController::class, 'applyLevels']);
    Route::post('v1/user/checkApplyLevel', [\App\Http\Controllers\Api\V1\User\ApplyLevelController::class, 'checkApplyLevel']);

    /************************************
     *********** TelSupports ************
     ************************************/
    Route::get('v1/user/poll/{id}', [\App\Http\Controllers\Api\V1\User\TelSupportController::class, 'expertPollInfo']);
    Route::post('v1/user/sendComment', [\App\Http\Controllers\Api\V1\User\TelSupportController::class, 'sendComment']);
    Route::get('v1/user/telSupports', [\App\Http\Controllers\Api\V1\User\TelSupportController::class, 'telSupports']);
    Route::post('v1/user/setNewTime', [\App\Http\Controllers\Api\V1\User\TelSupportController::class, 'setNewTime']);
    Route::post('v1/user/expertTelSupports', [\App\Http\Controllers\Api\V1\User\TelSupportController::class, 'expertTelSupports']);
    Route::post('v1/user/chooseTelSupport', [\App\Http\Controllers\Api\V1\User\TelSupportController::class, 'chooseTelSupport']);
    Route::post('v1/user/updateTelSupport', [\App\Http\Controllers\Api\V1\User\TelSupportController::class, 'updateTelSupport']);
    Route::post('v1/user/cancelTelSupport', [\App\Http\Controllers\Api\V1\User\TelSupportController::class, 'cancelTelSupport']);

    /************************************
     ************* Resume ***************
     ************************************/

    Route::get('v1/user/resume', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'resume']);
    Route::get('v1/user/resume/{id}', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'resumeId']);
    Route::get('v1/user/resumes', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'resumes']);
    Route::post('v1/user/updateResumeInformation', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'updateResumeInformation']);
    Route::post('v1/user/updateResume', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'updateResume']);
    Route::post('v1/user/resume/uploadImage', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'uploadImage']);
    Route::post('v1/user/resume/addEducationRecord', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'addEducationRecord']);
    Route::post('v1/user/resume/deleteEducationRecord', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'deleteEducationRecord']);
    Route::post('v1/user/resume/addLanguage', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'addLanguage']);
    Route::post('v1/user/resume/deleteLanguage', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'deleteLanguage']);
    Route::post('v1/user/resume/addWork', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'addWork']);
    Route::post('v1/user/resume/deleteWork', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'deleteWork']);
    Route::post('v1/user/resume/addSoftwareKnowledge', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'addSoftwareKnowledge']);
    Route::post('v1/user/resume/deleteSoftwareKnowledge', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'deleteSoftwareKnowledge']);
    Route::post('v1/user/resume/addCourse', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'addCourse']);
    Route::post('v1/user/resume/deleteCourse', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'deleteCourse']);
    Route::post('v1/user/resume/addResearch', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'addResearch']);
    Route::post('v1/user/resume/deleteResearch', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'deleteResearch']);
    Route::post('v1/user/resume/addHobby', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'addHobby']);
    Route::post('v1/user/resume/deleteHobby', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'deleteHobby']);
    Route::post('v1/user/resume/uploadPDF', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'uploadPDF']);
    Route::post('v1/user/resume/deletePDF', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'deletePDF']);
    Route::post('v1/user/updateResumeExtra', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'updateResumeExtra']);
    Route::post('v1/user/editResume', [\App\Http\Controllers\Api\V1\User\ResumeController::class, 'editResume']);

    /************************************
     *********** Motivations ************
     ************************************/

    Route::get('v1/user/newMotivation', [\App\Http\Controllers\Api\V1\User\MotivationController::class, 'newMotivation']);
    Route::get('v1/user/motivations', [\App\Http\Controllers\Api\V1\User\MotivationController::class, 'motivations']);
    Route::get('v1/user/motivation/{id}', [\App\Http\Controllers\Api\V1\User\MotivationController::class, 'motivation']);
    Route::post('v1/user/saveMotivation', [\App\Http\Controllers\Api\V1\User\MotivationController::class, 'saveMotivation']);
    Route::post('v1/user/updateMotivationExtra', [\App\Http\Controllers\Api\V1\User\MotivationController::class, 'updateMotivationExtra']);
    Route::post('v1/user/editMotivation', [\App\Http\Controllers\Api\V1\User\MotivationController::class, 'editMotivation']);
    Route::post('v1/user/updateMotivation', [\App\Http\Controllers\Api\V1\User\MotivationController::class, 'updateMotivation']);
    Route::post('v1/user/motivation/uploadResume', [\App\Http\Controllers\Api\V1\User\MotivationController::class, 'uploadResume']);

    Route::post('v1/user/motivation/deletePDF', [\App\Http\Controllers\Api\V1\User\MotivationController::class, 'deletePDF']);
});

Route::group(['middleware' => ['ExpertAuth']], function () {

    /************************************
     ************ Dashboard *************
     ************************************/
    Route::get('v1/expert/getDownloadByFormat/{type}/{id}',[\App\Http\Controllers\Api\V1\Expert\DashboardController::class, 'downloadFiles']);
    Route::post('v1/expert/changeUploadAccess', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'changeUploadAccess']);
    Route::post('v1/expert/uploadImage', [\App\Http\Controllers\Api\V1\Expert\DashboardController::class, 'uploadImage']);
    Route::post('v1/expert/changeEmailMobile', [\App\Http\Controllers\Api\V1\Expert\DashboardController::class, 'changeEmailMobile']);
    Route::post('v1/expert/changeEmailMobileVerify', [\App\Http\Controllers\Api\V1\Expert\DashboardController::class, 'changeEmailMobileVerify']);
    Route::get('v1/expert/changeEmailMobileResendCode', [\App\Http\Controllers\Api\V1\Expert\DashboardController::class, 'changeEmailMobileResendCode']);
    Route::post('v1/expert/updatePassword', [\App\Http\Controllers\Api\V1\Expert\DashboardController::class, 'updatePassword']);
    Route::get('v1/expert/changeDarkMode', [\App\Http\Controllers\Api\V1\Expert\DashboardController::class, 'changeDarkMode']);
    Route::get('v1/expert/users/{uni?}', [\App\Http\Controllers\Api\V1\Expert\DashboardController::class, 'usersList']);

    /************************************
     ********* User Management **********
     ************************************/

    Route::post('v1/expert/searchUser', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'searchUser']);
    Route::post('v1/expert/getAllUser', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'getAllUser']);
    Route::get('v1/expert/getUser/{user}', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'getUser']);
    Route::get('v1/expert/changeUser/{user}', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'changeUser']);
    Route::get('v1/expert/changeUserType/{type}/{user}', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'changeUserType']);
    Route::get('v1/expert/getResume/{user}', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'getResume']);
    Route::post('v1/expert/sendComment/{user}', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'sendComment']);
    Route::post('v1/expert/sendCommentFree/{id}', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'sendCommentFree']);
    Route::put('v1/expert/updateComment/{userComment}', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'updateComment']);
    Route::put('v1/expert/updateCommentFree/{id}', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'updateCommentFree']);
    Route::post('v1/expert/saveCategory', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'saveCategory']);
    Route::post('v1/expert/deleteCategory', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'deleteCategory']);
    Route::post('v1/expert/updateUserCategory', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'updateUserCategory']);
    Route::post('v1/expert/saveUserContractInfo/{id}', [\App\Http\Controllers\Api\V1\Expert\UserController::class, 'saveUserContractInfo']);

    /************************************
     ****** University Management *******
     ************************************/

    Route::post('v1/expert/getAllUniversities', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'getAllUniversities']);
    Route::post('v1/expert/submitUniversities/{user}', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'submitUniversities']);
    Route::post('v1/expert/updateMaxUniversityCount', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'updateMaxUniversityCount']);
    Route::post('v1/expert/cloneUserUniversity', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'cloneUserUniversity']);
    Route::get('v1/expert/getUniversities/{user}', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'getUniversities']);
    Route::put('v1/expert/updateUniversity/{userUniversity}', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'updateUniversity']);
    Route::delete('v1/expert/deleteUserUniversity/{userUniversity}', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'deleteUserUniversity']);
    Route::delete('v1/expert/deleteUniversity/{userUniversity}', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'deleteUniversity']);
    Route::post('v1/expert/cloneUniversity/{userUniversity}', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'cloneUniversity']);
    Route::post('v1/expert/deleteAllUniversities', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'deleteAllUniversities']);
    Route::post('v1/expert/changeUniversityStatus', [\App\Http\Controllers\Api\V1\Expert\UniversityController::class, 'changeUniversityStatus']);

    /************************************
     ******* Document Management ********
     ************************************/

    Route::get('v1/expert/getDocument/{user}', [\App\Http\Controllers\Api\V1\Expert\DocumentController::class, 'getDocument']);
    Route::delete('v1/expert/deleteDocument/{upload}', [\App\Http\Controllers\Api\V1\Expert\DocumentController::class, 'deleteDocument']);
    Route::post('v1/expert/changeStatus', [\App\Http\Controllers\Api\V1\Expert\DocumentController::class, 'changeStatus']);
    Route::post('v1/expert/changeDocument/{upload}', [\App\Http\Controllers\Api\V1\Expert\DocumentController::class, 'changeDocument']);

    /************************************
     ********* Duty Management **********
     ************************************/

    Route::post('v1/expert/saveDuty/{user}', [\App\Http\Controllers\Api\V1\Expert\DutyController::class, 'saveDuty']);
    Route::post('v1/expert/saveDutyAll/{user}', [\App\Http\Controllers\Api\V1\Expert\DutyController::class, 'saveDutyAll']);
    Route::put('v1/expert/updateDuty/{userDuty}', [\App\Http\Controllers\Api\V1\Expert\DutyController::class, 'updateDuty']);
    Route::put('v1/expert/updateStatusDuty/{userDuty}', [\App\Http\Controllers\Api\V1\Expert\DutyController::class, 'updateStatusDuty']);
    Route::delete('v1/expert/deleteDuty/{userDuty}', [\App\Http\Controllers\Api\V1\Expert\DutyController::class, 'deleteDuty']);
    Route::get('v1/expert/getDuties/{user}', [\App\Http\Controllers\Api\V1\Expert\DutyController::class, 'getDuties']);

    /***************************************
     ***** Work Experience Management ******
     **************************************/

    Route::post('v1/expert/workExperience', [\App\Http\Controllers\Api\V1\Expert\WorkExperienceController::class, 'getHistoryWorkExperience']);
    Route::post('v1/expert/workExperience/changeUploadAccess', [\App\Http\Controllers\Api\V1\Expert\WorkExperienceController::class, 'changeUploadAccess']);

    /************************************
     ***** Tel Support Management ******
     ************************************/

    Route::post('v1/expert/getHistoryTel', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'getHistoryTel']);
    Route::get('v1/expert/getTelSupportData', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'getTelSupportData']);
    // Route::get('v1/expert/getTelSupportDataMonth', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'getTelSupportDataMonth']);
    // Route::get('v1/expert/getTelSupportData3Month', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'getTelSupportData3Month']);
    // Route::get('v1/expert/getTelSupportData6Month', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'getTelSupportData6Month']);
    Route::get('v1/expert/getMonthTel', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'getMonthTel']);
    Route::get('v1/expert/getTags', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'getTags']);
    Route::delete('v1/expert/cancelUserTelSupport/{telSupport}', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'cancelUserTelSupport']);
    Route::delete('v1/expert/deleteTelSupport/{telSupport}', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'deleteTelSupport']);
    Route::post('v1/expert/saveSession', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'saveSession']);
    Route::post('v1/expert/saveAutoSession', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'saveAutoSession']);
    Route::get('v1/expert/getComments', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'getComments']);
    Route::post('v1/expert/deleteSupportTime', [\App\Http\Controllers\Api\V1\Expert\TelSupportController::class, 'deleteTimeSupport']);

    /************************************
     ******** Process Management ********
     ************************************/

    Route::post('v1/expert/getProcesses', [\App\Http\Controllers\Api\V1\Expert\ProcessController::class, 'getProcesses']);
    Route::post('v1/expert/updateProcess', [\App\Http\Controllers\Api\V1\Expert\ProcessController::class, 'updateProcess']);
    Route::post('v1/expert/updateProcessUniversity', [\App\Http\Controllers\Api\V1\Expert\ProcessController::class, 'updateProcessUniversity']);

    /************************************
     ***** Apply Status Management ******
     ************************************/

    Route::get('v1/expert/getApply/{user}', [\App\Http\Controllers\Api\V1\Expert\ApplyController::class, 'getApply']);
    Route::post('v1/expert/modalMotivationByExpert',[\App\Http\Controllers\Api\V1\Expert\ApplyController::class, 'modalMotivationByExpert']);
    Route::post('v1/expert/modalTerminationByExpert',[\App\Http\Controllers\Api\V1\Expert\ApplyController::class, 'modalTerminationByExpert']);
    Route::post('v1/expert/modalResumeByExpert',[\App\Http\Controllers\Api\V1\Expert\ApplyController::class, 'modalResumeByExpert']);
    Route::post('v1/expert/uploadApplyFile', [\App\Http\Controllers\Api\V1\Expert\ApplyController::class, 'uploadApplyFile']);
    Route::post('v1/expert/deleteApplyFile', [\App\Http\Controllers\Api\V1\Expert\ApplyController::class, 'deleteApplyFile']);
    Route::get('v1/expert/votes', [\App\Http\Controllers\Api\V1\User\VoteController::class, 'apiExpertVotes']);

    Route::post('v1/expert/orders', [\App\Http\Controllers\Api\V1\Expert\OrderController::class, 'orders']);
    Route::get('v1/expert/getMotivation/{id}', [\App\Http\Controllers\Api\V1\Expert\OrderController::class, 'getMotivation']);
    Route::get('v1/expert/data/getResume/{id}', [\App\Http\Controllers\Api\V1\Expert\OrderController::class, 'getResume']);
    Route::get('v1/expert/orders/acceptFile/{id}/{type}', [\App\Http\Controllers\Api\V1\Expert\OrderController::class, 'acceptFile']);
    Route::get('v1/expert/orders/declineFile/{id}/{type}', [\App\Http\Controllers\Api\V1\Expert\OrderController::class, 'declineFile']);
    Route::post('v1/expert/orders/declineAsUser', [\App\Http\Controllers\Api\V1\Expert\OrderController::class, 'declineAsUser']);
});

Route::group(['middleware' => ['WriterAuth']], function () {
    Route::post('v1/writer/dashboard', [\App\Http\Controllers\Api\V1\Writer\DashboardController::class, 'index']);
    Route::get('v1/writer/changeDarkMode', [\App\Http\Controllers\Api\V1\Writer\DashboardController::class, 'changeDarkMode']);

    Route::get('v1/writer/getMotivation/{id}', [\App\Http\Controllers\Api\V1\Writer\MotivationController::class, 'getMotivation']);
    Route::get('v1/writer/getResume/{id}', [\App\Http\Controllers\Api\V1\Writer\ResumeController::class, 'getResume']);
    Route::get('v1/writer/getUploadFiles/{id}/{type}', [\App\Http\Controllers\Api\V1\Writer\ResumeController::class, 'getFiles']);

    Route::get('v1/writer/getDocument/{user}', [\App\Http\Controllers\Api\V1\Expert\DashboardController::class, 'getDocument']);
    Route::get('v1/writer/getApply/{user}', [\App\Http\Controllers\Api\V1\Expert\DashboardController::class, 'getApply']);

    Route::post('v1/writer/uploadFile', [\App\Http\Controllers\Api\V1\Writer\ResumeController::class, 'uploadFile']);
    Route::post('v1/writer/deleteFile', [\App\Http\Controllers\Api\V1\Writer\ResumeController::class, 'deleteFile']);
});

Route::get('v1/writer/exports/pdf/{type}/{id}', [\App\Http\Controllers\Api\V1\Writer\ResumeController::class, 'generateExport']);


/************************************
 ****** Chat ******
 ************************************/
Route::get('v1/supervisorUser/{id}', [\App\Http\Controllers\Api\V1\Expert\Chat::class, "getSupervisor"]);
Route::get('v1/userSupervisor/{id}', [\App\Http\Controllers\Api\V1\Expert\Chat::class, "getUser"]);
Route::get('v1/adminUser', [\App\Http\Controllers\Api\V1\Expert\Chat::class, "adminUser"]);
Route::get('v1/experts/{id}', [\App\Http\Controllers\Api\V1\Expert\Chat::class, "availableConnections"]);
Route::get('v1/notification', [\App\Http\Controllers\Api\V1\Expert\Chat::class, "notification"]);
Route::get('v1/availableConnections', [\App\Http\Controllers\Api\V1\Expert\Chat::class, "availableConnections"]);
Route::post('v1/voteNotif', [\App\Http\Controllers\Api\V1\Expert\Chat::class, "voteNotif"]);

/************************************
 ****** Vote ******
 ************************************/
Route::post('v1/voteNotif', [\App\Http\Controllers\Api\V1\Expert\Chat::class, "voteNotif"]);

Route::post('v1/vote', [\App\Http\Controllers\Api\V1\User\VoteController::class, "submitVote"]);
