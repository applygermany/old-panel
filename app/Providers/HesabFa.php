<?php

namespace App\Providers;
class HesabFa {
	public $APIKey;
	public $LoginToken;
	public $Username;
	public $Password;
	
	public function __construct() {
		$this->APIKey = env("HESABFA_APIKey");
		//to prevent conflict with previous ids
		//i added numbers to current ids
		// user add = 20
		// invoice add = 1020
		$this->LoginToken = env("HESABFA_LOGIN_TOKEN");
	}
	
	public function addInvoice($invoice) {

		return $this->post(
			"https://api.hesabfa.com/v1/invoice/save",
			[
				"apiKey"     => $this->APIKey,
				"loginToken" => $this->LoginToken,
				"invoice"    => [
					"Number"       => $invoice->id + 1020,
					"Date"         => date("Y-m-d H:i:s"),
					"ContactCode"  => $invoice->user_id + 20,
					"InvoiceType"  => 0,
					"DueDate"      => date("Y-m-d H:i:s"),
					"Status"       => 3,
					"InvoiceItems" => [
						[
							"Description" => "توضیحات",
							"ItemCode"    => $invoice->associate_id,
							"Quantity"    => 1,
							"UnitPrice"   => $invoice->final_amount,
							"Discount"    => $invoice->discount_amount,
							"Tax"         => 0,
						],
					],
				],
			]
		);
	}
	
	public function post($url, $parameter) {
		$parameter = json_encode($parameter, JSON_UNESCAPED_UNICODE);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $parameter);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close($ch);
		return $server_output;
	}
	
	public function addCustomer($user) {
		if ($user->mobile) {
			$contact = [
				"Code"        => $user->id + 20,
				"Name"        => $user->firstname . " " . $user->lastname,
				"ContactType" => 1,
				"Mobile"      => "0" . $user->mobile,
			];
		} else {
			$contact = [
				"Code"        => $user->id + 20,
				"Name"        => $user->firstname . " " . $user->lastname,
				"ContactType" => 1,
				"Email"       => $user->email,
			];
		}
		return $this->post(
			"https://api.hesabfa.com/v1/contact/save",
			[
				"apiKey"     => $this->APIKey,
				"loginToken" => $this->LoginToken,
				"contact"    => $contact,
			]
		);
	}
}
