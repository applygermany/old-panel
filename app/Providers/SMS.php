<?php

namespace App\Providers;
use Illuminate\Support\Facades\Http;
class SMS {
	public $codes;
	
	public function __construct() {
		$this->codes = (object)[
			"signup"                 => '92tk148utiy15pg',
			"forgot"                 => 'jjzmj9p72swq13u',
			"registered"             => 'qmmgs4caake0hui',
			"invite_code"            => 'g4veyaovqnuhe3v',
		//	"university_add"         => 'jdutu6jbnfuzg71',
		//	"university_remove"      => 'z5xqqtp0leno703',
		//	"motivation_added"       => '0b0jm2x5rv7984m',
			"motivation_edit_needed" => 'zlba0td4lsyjj48',
			"motivation_done"        => 'nbp8ob3sefsk2n5',
		//	"resume_added"           => 'x8s5h8lwf86x7bx',
			"resume_edit_needed"     => '4hsyo69wye2cog4',
			"resume_done"            => 'vugecufj72coxp1',
		//	"add_duty"               => '34ujfw7lpyo59pk',
		//	"edit_duty"              => 'sc6asf44hsfg721',
			"add_pre_invoice"        => 'a761znyqqfasicw',
			"add_receipt_invoice"    => '8rn01evkx1wjj3z',
		//	"pay_inovice"            => 'acgb4uhyo7fu2kc',
		//	"uni_apply"              => 'd2wy5jljw2x8ukl',
			"university_accept"      => '2uw6cnz3v6ylrw2',
		//	"university_reject"      => 'qtagz3z7uguy8rz',
		//	"add_support"            => 'hxgkvba48qcpgl8',
		//	"add_supervisor"         => 'hoa2xt8sy1ohi5e',
		//	"change_support"         => '4tpi34wmrxq8krb',
		//	"change_supervisor"      => '2zm808puggol86i',
		//	"new_acceptance"         => 'i8y5hnf63eye9nk',
			"tel_reserved"           => 'yr3065t4hiw0534',
			"tel_24h_remained"       => 'zmmg0x4cg6u1tm0',
		//	"new_time"               => '7c3enjhtq5a2lc5',
		//	"webinar"                => '9fogk3i43n5tskq',
		//	"remove_uni"             => 'z5xqqtp0leno703',
			"blog_post"              => 'r20ms4daguvxgvd',
			"contract_ready"         => 'gqu959ks4mho0j0',
			"tel_support_poll"       => 'b0zwhtf0y1wj7gx',
			"webinar"                => 'pnezg1uw4h40byn',
		];
	}

    public function sendVerification($phone, $action, $parameter) {
        $apiKey='sHlP71Z5DrRSAHwgnOJjwmF0vFn8Zo8tc9CwCq73r_U=';
        $pattern_code=$this->codes->$action . '';
        $originator='+983000505';
        $recipientPhone='+98'.$phone;
        $url='http://ippanel.com:8080/?apikey='.$apiKey.'&pid='.$pattern_code.'&fnum='.$originator.'&tnum='.$recipientPhone;
        $p=0;
        $firstStringPart='';
        $values=$this->getParam($parameter);
        foreach ($values as $key=>$value){
            $p+=1;
            $firstStringPart=$firstStringPart.'&p'.$p.'='.$key;
            $secondStringPart=$secondStringPart.'&v'.$p.'='.$value;
        }
        $url=$url.$firstStringPart.$secondStringPart;
        $response = Http::get($url);
        return $response;
    }
	
	public function sendVerificationOld($phone, $action, $parameter) {
		
		try {
			date_default_timezone_set("Asia/Tehran");
			$APIKey = env("SMS_APIKey");
			$SecretKey = env("SMS_SecretKey");
			$APIURL = "https://ws.sms.ir/";
			// message data
			$data = [
				"values"       => $this->getParam($parameter),
				"originator"   => "+983000505",
				"recipient"    => "+98$phone",
				"pattern_code" => $this->codes->$action . '',
			];
			$SmsIR_UltraFastSend = new SMSHelper($APIKey, $SecretKey, $APIURL);
			$UltraFastSend = $SmsIR_UltraFastSend->ultraFastSend($data);
			return $UltraFastSend ? 2 : 0;
		} catch(Exeption $e) {
			
			return 0;
		}
		return 0;
	}

    public function sendUltraFastArian($phone, $action, $parameter) {
        $apiKey='sHlP71Z5DrRSAHwgnOJjwmF0vFn8Zo8tc9CwCq73r_U=';
        $pattern_code=$this->codes->$action . '';
        $originator='+983000505';
        $recipientPhone='+98'.$phone;
        $url='http://ippanel.com:8080/?apikey='.$apiKey.'&pid='.$pattern_code.'&fnum='.$originator.'&tnum='.$recipientPhone;
        $p=0;
        $firstStringPart='';
        foreach($parameter['names'] as $name){
            $p+=1;
            $firstStringPart=$firstStringPart.'&p'.$p.'='.$name;
        }
        $v=0;
        $secondStringPart='';
        foreach($parameter['values'] as $value){
            $v+=1;
            $secondStringPart=$secondStringPart.'&v'.$v.'='.$value;
        }
        $url=$url.$firstStringPart.$secondStringPart;
        $response = Http::get($url);
        return $response;
    }

    public function sendUltraFastArianMethod3($phone, $action, $parameter) {

        try {
            date_default_timezone_set("Asia/Tehran");
            $APIKey = env("SMS_APIKey");
            $SecretKey = env("SMS_SecretKey");
            $APIURL = "https://ws.sms.ir/";
            // message data
            $data = [
                "values"       => $this->getParam($parameter),
                "originator"   => "+983000505",
                "recipient"    => "+98$phone",
                "pattern_code" => $this->codes->$action . '',
            ];

            $postString = json_encode($data);
            $key = 'sHlP71Z5DrRSAHwgnOJjwmF0vFn8Zo8tc9CwCq73r_U=';
            $ch = curl_init('https://rest.ippanel.com/v1/messages/patterns/send');
            curl_setopt(
                $ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: ' . "AccessKey $key",
                ]
            );
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
//            $SmsIR_UltraFastSend = new SMSHelper($APIKey, $SecretKey, $APIURL);
//            $UltraFastSend = $SmsIR_UltraFastSend->ultraFastSend($data);
//            return $UltraFastSend ? 2 : 0;
        } catch(Exeption $e) {

            return 0;
        }
        return 0;
    }
	
	public function getParam($parameter) {
		$params = explode("&", $parameter);
		$out = [];
		foreach ($params as $param) {
			$n_p = explode("==", $param);
			$out[$n_p[0]] = $n_p[1];
		}
		return $out;
	}
	
	public function getParam1($parameter) {
		$params = explode("&", $parameter);
		$out = [];
		foreach ($params as $param) {
			$n_p = explode("==", $param);
			$out[] = [
				"Parameter"      => $n_p[0],
				"ParameterValue" => $n_p[1],
			];
		}
		return $out;
	}
	
	public function sendNewTime($phone, $userName, $expertName) {
		
		try {
			date_default_timezone_set("Asia/Tehran");
			$APIKey = env("SMS_APIKey");
			$SecretKey = env("SMS_SecretKey");
			$APIURL = "https://ws.sms.ir/";
			// message data
			$data = [
				'values'       => [
					'name'  => $userName,
					'name1' => $expertName,
				],
				"originator"   => "+983000505",
				"recipient"    => "+98$phone",
				"pattern_code" => $this->codes->new_time,
			];
			$SmsIR_UltraFastSend = new SMSHelper($APIKey, $SecretKey, $APIURL);
			$UltraFastSend = $SmsIR_UltraFastSend->ultraFastSend($data);
			return $UltraFastSend == "your verification code is sent" ? 2 : 0;
		} catch(Exeption $e) {
			
			return 0;
		}
		return 0;
	}


}
