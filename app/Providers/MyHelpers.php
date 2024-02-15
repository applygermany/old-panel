<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use App\Providers\MyHelpers;

class MyHelpers extends ServiceProvider {
	public static function checkNumber($value) {
		$En_Value = str_replace(
			['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
			['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
			$value);
		if (preg_match("/[a-z]/i", $En_Value))
			return false;
		if (preg_match("/[A-Z]/i", $En_Value))
			return false;
		$BadChars = ";,*()]$#^?&+=|\/['";
		$CharsLen = strlen($BadChars);
		$StrLen = strlen($En_Value);
		for ($i = 0; $i < $StrLen; $i++) {
			for ($j = 0; $j < $CharsLen; $j++) {
				if ($En_Value[$i] == $BadChars[$j]) {
					return false;
				}
			}
		}
		if (!preg_match("/^[^\x{600}-\x{6FF}]+$/u", $En_Value))
			return false;
		return true;
	}
	
	public static function numberToPersian($inputVal) {
		$Persian_Number = str_replace(
			['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
			['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
			$inputVal
		);
		return $Persian_Number;
	}
	
	public static function get_link($slug) {
		return env("webinar_url") .'/' . $slug;
	}
	
	public static function numberToEnglish($inputVal) {
		$english_Number = str_replace(
			['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
			['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
			$inputVal
		);
		$english_Number2 = str_replace(
			['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
			['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
			$english_Number
		);
		return $english_Number2;
	}
	
	public static function dateToHuman($createdAt) {
		Carbon::setLocale('fa');
		return Carbon::parse($createdAt)->diffForHumans();
	}
	
	public static function dateToJalali($createdAt) {
		$date = explode(' ', $createdAt);
		$date = explode('-', $date[0]);
		$date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
		return $date;
	}
	
	public static function dateToJalali2($createdAt) {
		$date = explode('-', $createdAt);
		$date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
		return $date;
	}
	
	public static function dateGetHour($createdAt) {
		$date = explode(' ', $createdAt);
		$date = explode(':', $date[1]);
		return 'ساعت ' . $date[0] . ':' . $date[1];
	}
	
	public static function generateRandomString($length) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public static function zerosTochars($input) {
		$input = number_format($input);
		$input_count = substr_count($input, ',');
		if ($input_count != '0') {
			if ($input_count == '1') {
				return substr($input, 0, -4) . 'k';
			} elseif ($input_count == '2') {
				return substr($input, 0, -8) . 'mil';
			} elseif ($input_count == '3') {
				return substr($input, 0, -12) . 'bil';
			} else {
				return;
			}
		} else {
			return $input;
		}
	}
	
	public static function formatSizeUnits($bytes) {
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}
		return $bytes;
	}
	
	public static function setmsg($fp, $ex) {
		list($width, $height) = getimagesize($fp);
		if ($width <= $height) {
			$dif = $height - $width;
			switch ($ex) {
				case 'jpeg':
				case 'jpg':
					$img1 = imagecreatefromjpeg($fp);
					break;
				case 'png':
					$img1 = imagecreatefrompng($fp);
					break;
				case 'gif':
					$img1 = imagecreatefromgif($fp);
					break;
				default:
					return false;
					break;
			}
			$img2 = imagecreatetruecolor($width, $width);
			imagecopyresized($img2, $img1, 0, 0, 0, $dif / 2, imagesx($img2), imagesy($img2), imagesx($img1), imagesy($img1) - $dif);
			imagejpeg($img2, $fp);
		}
		if ($width > $height) {
			$dif = $width - $height;
			switch ($ex) {
				case 'jpeg':
				case 'jpg':
					$img1 = imagecreatefromjpeg($fp);
					break;
				case 'png':
					$img1 = imagecreatefrompng($fp);
					break;
				case 'gif':
					$img1 = imagecreatefromgif($fp);
					break;
				default:
					return false;
					break;
			}
			$img2 = imagecreatetruecolor($height, $height);
			imagecopyresized($img2, $img1, 0, 0, $dif / 2, 0, imagesx($img2), imagesy($img2), imagesx($img1) - $dif, imagesy($img1));
			imagejpeg($img2, $fp);
		}
	}
}
