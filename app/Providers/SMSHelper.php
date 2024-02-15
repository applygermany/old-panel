<?php

namespace App\Providers;
class SMSHelper {
	/**
	 * Gets config parameters for sending request.
	 *
	 * @param string $APIKey API Key
	 * @param string $SecretKey Secret Key
	 * @param string $APIURL API URL
	 *
	 * @return void
	 */
	public function __construct($APIKey, $SecretKey, $APIURL) {
		$this->APIKey = $APIKey;
		$this->SecretKey = $SecretKey;
		$this->APIURL = $APIURL;
	}
	
	/**
	 * Ultra Fast Send Message.
	 *
	 *
	 * @return string Indicates the sent sms result
	 */
	public function ultraFastSend($data) {
		$token = $this->_getToken($this->APIKey, $this->SecretKey);
		if ($token != false) {
			$postData = $data;
			$url = $this->APIURL . $this->getAPIUltraFastSendUrl();
			$UltraFastSend = $this->_execute($postData, $url, $token);
			$object = json_decode($UltraFastSend);
			$result = false;
			if (is_object($object)) {
//				var_dump($object);
				$result = $object->status == 'OK';
			} else {
				$result = false;
			}
		} else {
			$result = false;
		}
		return $result;
	}
	
	/**
	 * Gets token key for all web service requests.
	 *
	 * @return string Indicates the token key
	 */
	private function _getToken() {
		$postData = [
			'UserApiKey' => $this->APIKey,
			'SecretKey'  => $this->SecretKey,
			'System'     => 'php_rest_v_2_0',
		];
		$postString = json_encode($postData);
		$ch = curl_init($this->APIURL . $this->getApiTokenUrl());
		curl_setopt(
			$ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
			]
		);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
		$result = curl_exec($ch);
		curl_close($ch);
		print(curl_error($ch));
		$response = json_decode($result);
		$resp = false;
		$IsSuccessful = '';
		$TokenKey = '';
		if (is_object($response)) {
			$IsSuccessful = $response->IsSuccessful;
			if ($IsSuccessful == true) {
				$TokenKey = $response->TokenKey;
				$resp = $TokenKey;
			} else {
				$resp = false;
			}
		}
		return $resp;
	}
	
	/**
	 * Gets Api Token Url.
	 *
	 * @return string Indicates the Url
	 */
	protected function getApiTokenUrl() {
		return "api/Token";
	}
	
	/**
	 * Gets API Ultra Fast Send Url.
	 *
	 * @return string Indicates the Url
	 */
	protected function getAPIUltraFastSendUrl() {
		return "api/UltraFastSend";
	}
	
	private function _execute($postData, $url, $token) {
		$postString = json_encode($postData);
		$key = 'sHlP71Z5DrRSAHwgnOJjwmF0vFn8Zo8tc9CwCq73r_U=';
		$ch = curl_init('https://rest.ippanel.com/v1/messages/patterns/send');
		curl_setopt(
			$ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
				'Authorization: ' . "AccessKey $key",
			]
		);
//		curl_setopt($ch, CURLOPT_HEADER, false);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	/**
	 * Executes the main method.
	 *
	 * @param postData[] $postData array of json data
	 * @param string $url url
	 * @param string $token token string
	 *
	 * @return string Indicates the curl execute result
	 */
	private function _execute1($postData, $url, $token) {
		$postString = json_encode($postData);
		$ch = curl_init($url);
		curl_setopt(
			$ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
				'x-sms-ir-secure-token: ' . $token,
			]
		);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	
}
