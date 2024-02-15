<?php

namespace App\Models;

use App\Jobs\SendEmailJob;
use App\Mail\SendEmail;
use App\Mail\WebinarEmail;
use App\Models\UserExtraUniversity;
use App\Models\UserUniversity;
use App\Providers\MyHelpers;
use App\Mail\MailVerificationCode;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $guarded = [];

    protected $fillable = [
        'username',
        'name',
        'father_name',
        'code',
        'status',
        'level',
        'verified',
        'email',
        'password',
        'admin_permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends = ['image','unTouchedBalance'];
    protected $casts = [
        'admin_permissions' => 'object',
    ];

    public function getUnTouchedBalanceAttribute()
    {
        $oldBalance = DB::select('select balance from nag_users where id = ' . $this->id);
        return $oldBalance[0]->balance;
    }


//    public function getBalanceAttribute(){
//        $oldBalance= DB::select('select balance from nag_users where id = ' . $this->id);
//        $oldBalance=$oldBalance[0]->balance;
//        $extraUniversities=UserExtraUniversity::where('user_id',$this->id)->has('university')->get();
//
//
//        if(!empty($extraUniversities)){
//            $userUniversity=UserUniversity::where([['user_id',$this->id],['status',1]])->get();
//            $numberOfExtra=sizeof($userUniversity)-$this->max_university_count;
//
//            if( sizeof($extraUniversities) > $numberOfExtra ){
//                $numberOfDeletes= sizeof($extraUniversities) - $numberOfExtra;
//                UserExtraUniversity::where('user_id',$this->id)->limit($numberOfDeletes)->delete();
//            }
//            $totaExtralPrice=0;
//            foreach ($extraUniversities as $extra){
//                $totaExtralPrice+=$extra['extra_price_euro'];
//            }
//            return $oldBalance-$totaExtralPrice ;
//        }else{
//            return $oldBalance;
//        }
//    }

    public function getBalanceAttribute(){

        $oldBalance= DB::select('select balance from nag_users where id = ' . $this->id);
        $oldBalance=$oldBalance[0]->balance;
        $userUniversity=UserUniversity::where([['user_id',$this->id],['status',1]])->count();//uni haye dar sabad karbar
        $numberOfExtra=$userUniversity-$this->max_university_count;//tedad ezafeye daneshgah ha
        if($numberOfExtra > 0){//daneshgahe ezafe darad
            $extraUniversities=UserExtraUniversity::where('user_id',$this->id)->count();//uni haye ezafe
            $totalExtraPrice=0;
            if($extraUniversities > 0){
                if( $extraUniversities > $numberOfExtra ){//mahdoodiate entekhabe daneshgah afzayesh yafte ast
                    $numberOfDeletes= $extraUniversities - $numberOfExtra;
                    UserExtraUniversity::where('user_id',$this->id)->limit($numberOfDeletes)->delete();
                    $updatedExtraUniversities=UserExtraUniversity::where('user_id',$this->id)->get();
                    if(!empty($updatedExtraUniversities)){
                        foreach ($updatedExtraUniversities as $extra){
                            $totalExtraPrice+=$extra['extra_price_euro'];
                        }
                    }else{
                        User::where('id',$this->id)->update(['extra_uni_check'=>1]);
                    }
                }elseif ($extraUniversities < $numberOfExtra ){//mahdoodiate entekhabe daneshgah kahesh yafte ast
                    $updatedExtraUniversities=UserExtraUniversity::where('user_id',$this->id)->get();
                    foreach ($updatedExtraUniversities as $extra){
                        $totalExtraPrice+=$extra['extra_price_euro'];
                    }
                    $extraGetRised=$numberOfExtra-$extraUniversities;
                    $pricing=Pricing::first();
                    for($i=0;$i < $extraGetRised;$i++){
                        $newExtraUniversity=new UserExtraUniversity();
                        $newExtraUniversity->user_id=$this->id;
//                    $newExtraUniversity->university_id=$userUniversity[]['university_id'];
                        $newExtraUniversity->extra_price_euro=$pricing->add_college_price;
                        $newExtraUniversity->save();
                    }
                    $totalExtraPrice+=$pricing->add_college_price*$extraGetRised;
                }else{//tedad extraUnies ba extraNumber(ekhtelafe daneshgah ha ba max_university_count) sefr ast
                    $updatedExtraUniversities=UserExtraUniversity::where('user_id',$this->id)->get();
                    foreach ($updatedExtraUniversities as $extra){
                        $totalExtraPrice+=$extra['extra_price_euro'];
                    }
                }
            }else if($this->extra_uni_check ==1){//condition nemizare k user haye ghadimi bedehkar beshan
                $pricing=Pricing::first();
                for($i=0;$i < $numberOfExtra;$i++){
                    $newExtraUniversity=new UserExtraUniversity();
                    $newExtraUniversity->user_id=$this->id;
                    $newExtraUniversity->extra_price_euro=$pricing->add_college_price;
                    $newExtraUniversity->save();
                }
                $totalExtraPrice+=$pricing->add_college_price*$numberOfExtra;
            }
            return $oldBalance-$totalExtraPrice ;
        }else{
            $extraUniversities=UserExtraUniversity::where('user_id',$this->id)->count();//uni haye ezafe
            if($extraUniversities>0){
                UserExtraUniversity::where('user_id',$this->id)->delete();
                if($this->extra_uni_check ==0)
                    User::where('id',$this->id)->update(['extra_uni_check'=>1]);
            }
            return $oldBalance;
        }
    }

    public static function sendMail(MailVerificationCode $mail, $to, $fromName = 'Apply Germany')
    {

        if ($to) {
            if ($mail->title) {
                Mail::to($to)->send(new SendEmail($mail->title, $mail->text, $mail->file));
            }
        }
        return false;
    }

    public static function sendMailWebinar(WebinarEmail $mail, $to, $fromName = 'Apply Germany')
    {

        if ($to) {
            dispatch(new SendEmailJob($mail, $to, $fromName));
        }
        return false;
    }

    public function acceptances()
    {
        return $this->hasMany(Acceptance::class);
    }

    public function metas()
    {
        return $this->hasMany(Usermeta::class);
    }

    public function supervisor()
    {
        return $this->hasOne(UserSupervisor::class);
    }

    public function supervisors()
    {
        return $this->hasMany(UserSupervisor::class);
    }

    public function getSupervisorItemAttribute()
    {
        foreach ($this->supervisors as $item) {
            if ($item->supervisor->level === 5) {
                return $item->supervisor;
            }
        }
    }

    public function getSupportItemAttribute()
    {
        foreach ($this->supervisors as $item) {
            if ($item->supervisor->level === 2) {
                return $item->supervisor;
            }
        }
    }

    public function users()
    {
        return $this->hasMany(UserSupervisor::class, 'supervisor_id');
    }

    public function universities()
    {
        return $this->hasMany(UserUniversity::class);
    }

    public function factors()
    {
        return $this->hasMany(Factor::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function telSupports()
    {
        return $this->hasMany(TelSupport::class);
    }

    public function userTelSupports()
    {
        return $this->hasMany(UserTelSupport::class);
    }

    public function supervisorTelSupports()
    {
        return $this->hasMany(UserTelSupport::class, 'supervisor_id', 'id');
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function getImageAttribute()
    {
        return route('imageUser', ["id" => $this->id, "ua" => time()]);
    }

    public function motivations()
    {
        return $this->hasMany(Motivation::class);
    }

    public function resume()
    {
        return $this->hasOne(Resume::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function duties()
    {
        return $this->hasMany(UserDuty::class);
    }

    public function authorComments()
    {
        return $this->hasMany(Comment::class, 'author', 'id');
    }

    public function ownerComments()
    {
        return $this->hasMany(Comment::class, 'owner', 'id')->orderBy('id', 'desc');
    }

    public function userComment()
    {
        return $this->hasOne(UserComment::class);
    }

    public function userComments()
    {
        return $this->hasMany(UserComment::class)->orderBy('id', 'desc');
    }

    public function processes()
    {
        return $this->hasOne(UserProcess::class);
    }

    public function userApplyLevels()
    {
        return $this->hasMany(UserApplyLevel::class);
    }

    public function userApplyLevelStatus()
    {
        return $this->hasOne(UserApplyLevelStatus::class);
    }

    public function userUniversities()
    {
        return $this->hasMany(UserUniversity::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function invites()
    {
        return $this->hasMany(User::class);
    }

    public function getInviteUserInfoAttribute()
    {
        return User::find($this->user_id);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = MyHelpers::numberToEnglish($value);
    }

    public function setMobileAttribute($value)
    {
        $this->attributes['mobile'] = MyHelpers::numberToEnglish($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = MyHelpers::numberToEnglish(strtolower($value));
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getUserLastStatusAttribute()
    {
        $invitingState = '';
        if ($this->invoices()->where("invoice_title", 'receipt')->first()) {
            $invitingState = "تسویه حساب";
        } elseif ($this->userUniversities()->where("status", 3)->first()) {
            $invitingState = "اخذ پذیرش";
        } elseif ($this->userUniversities()->first()) {
            $invitingState = "در دست اپلای";
        } elseif ($this->acceptances()->first()) {
            $invitingState = "درخواست اخذ پذیرش";
        } else {
            $invitingState = "ثبت نام در پورتال";
        }
        return $invitingState;
    }

    function expert()
    {
        if ($this->attributes['expert_id'] !== 0) {
            return User::find($this->attributes['expert_id']);
        }
        return User::find($this->attributes['id']);
    }

    function getContractTypeTitleAttribute()
    {
        if ($this->attributes['upload_access']) {
            if ($this->attributes['contract_type'] === 'gov-university')
                return "دانشگاه دولتی";
            elseif ($this->attributes['contract_type'] === 'all-university')
                return "دانشگاه دولتی و حصوصی";
            elseif ($this->attributes['contract_type'] === 'pri-university')
                return "دانشگاه حصوصی";
            elseif ($this->attributes['contract_type'] === 'pre-pay')
                return "پیش پرداخت";
        } else {
            return "----";
        }
        return "----";
    }
}
