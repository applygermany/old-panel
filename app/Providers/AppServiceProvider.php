<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('badChars', function($attribute, $value, $parameters, $validator) {

            $BadChars = ";,*()]$#^?&+=|\/['";
            $CharsLen = strlen($BadChars);
            $StrLen = strlen($value);

            for($i=0;$i<$StrLen;$i++) { for($j=0;$j<$CharsLen;$j++) { if($value[$i] == $BadChars[$j]) {return false;} } }

            return true;
        });
        \Validator::extend('haveNums', function($attribute, $value, $parameters, $validator) {

            $BadChars = "1234560789";
            $CharsLen = strlen($BadChars);
            $StrLen = strlen($value);

            for($i=0;$i<$StrLen;$i++) { for($j=0;$j<$CharsLen;$j++) { if($value[$i] == $BadChars[$j]) {return false;} } }

            return true;
        });
        \Validator::extend('shouldBeNums', function($attribute, $value, $parameters, $validator) {

            $En_Value = str_replace(
                array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'),
                array('0','1','2','3','4','5','6','7','8','9'),
                $value);

            if(preg_match("/[a-z]/i", $En_Value))
                return false;
            if(preg_match("/[A-Z]/i", $En_Value))
                return false;

            $BadChars = ";,*()]$#^?&+=|\/['";
            $CharsLen = strlen($BadChars);
            $StrLen = strlen($En_Value);

            for($i=0;$i<$StrLen;$i++) { for($j=0;$j<$CharsLen;$j++) { if($En_Value[$i] == $BadChars[$j]) {return false;} } }

            if(!preg_match("/^[^\x{600}-\x{6FF}]+$/u", $En_Value))
                return false;

            return true;
        });
        \Validator::extend('shouldBePos', function($attribute, $value, $parameters, $validator) {
            if($value < 0)
                return false;

            return true;
        });
        \Validator::extend('haveEnChars', function($attribute, $value, $parameters, $validator) {

            if(preg_match("/[a-z]/i", $value))
                return false;
            if(preg_match("/[A-Z]/i", $value))
                return false;

            return true;
        });
        \Validator::extend('haveFaChars', function($attribute, $value, $parameters, $validator) {

            if(!preg_match("/^[^\x{600}-\x{6FF}]+$/u", $value))
                return false;

            return true;
        });
        \Validator::extend('maximumNum', function($attribute, $value, $parameters, $validator) {

            $En_Value = str_replace(
                array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'),
                array('0','1','2','3','4','5','6','7','8','9'),
                $value);

            if($En_Value > $parameters[0])
                return false;

            return true;
        });
        \Validator::extend('minimumNum', function($attribute, $value, $parameters, $validator) {

            $En_Value = str_replace(
                array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'),
                array('0','1','2','3','4','5','6','7','8','9'),
                $value);

            if($En_Value < $parameters[0])
                return false;

            return true;
        });
        \Validator::extend('shouldBeMobile', function($attribute, $value, $parameters, $validator) {

            if(strlen($value > 10))
                $value = substr($value,1,10);

            $En_Value = str_replace(
                array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'),
                array('0','1','2','3','4','5','6','7','8','9'),
                $value);

            if(preg_match("/[a-z]/i", $En_Value))
                return false;
            if(preg_match("/[A-Z]/i", $En_Value))
                return false;

            $BadChars = ";,*()]^?&+=|\/['";
            $CharsLen = strlen($BadChars);
            $StrLen = strlen($En_Value);

            for($i=0;$i<$StrLen;$i++) { for($j=0;$j<$CharsLen;$j++) { if($En_Value[$i] == $BadChars[$j]) {return false;} } }

            if(!preg_match("/^[^\x{600}-\x{6FF}]+$/u", $En_Value))
                return false;

            return true;

        });

        Paginator::useBootstrap();
    }
}
