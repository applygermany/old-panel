<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Votes;
use App\Exports\Sheets\VoteSheet;
use App\Exports\Sheets\MotivationSheet;
use App\Models\Motivation;
use App\Exports\Sheets\MotivationQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VoteExport implements WithMultipleSheets {
	use Exportable;
	
	/**
	 * @return array
	 */
	public function sheets() : array {
		
		$sheets = [];
		$votesRaw = Votes::all();
		if (!$votesRaw) {
			abort(404);
		}
		$votes = [];
		$questions = [
			"1" => "به چه میزان از موارد زیر رضایت دارید؟",
			"2" => "سرعت پاسخگویی و در دسترس بودن کارشناس",
			"3" => "شیوه پاسخگویی و برخورد کارشناس",
			"4" => "کیفیت نگارش رزومه و انگیزه نامه خود توسط تیم اپلای جرمنی",
			"5" => "پشتیبانی بعد از پذیرش و انجام امور مرتبط با ویزا, ثبت نام دانشگاه, بیمه و درخواست خوابگاه",
			"6" => "به طور کلی چه میزان از خدمات و کیفیت کار تیم اپلای جرمنی رضایت داشتید؟",
			"7" => "چقدر احتمال دارد اپلای جرمنی را به دوستان و اشنایان خود معرفی کنید؟",
		];
		foreach ($votesRaw as $index => $vote) {
			$user = User::find($vote->user_id);
			$expert = User::find($vote->expert_id);
			$vote->userName = $user->firstname . ' ' . $user->lastname;
			$vote->expertName = $expert->firstname . ' ' . $expert->lastname;
			$answer = json_decode($vote->answer, true);
			$type = json_decode($vote->types, true);
			$answers = [];
			$types = [];
			foreach ($answer as $key => $value) {
				
				$answers[] = $this->getAnswer($value);
			}
			foreach ($type as $item) {
				$types[] = $this->getType($item);
			}
			$vote->answers = $answers;
			$vote->types = implode(',',$types);
			$votes[] = $vote;
		}
		$sheets[] = new VoteSheet("اطلاعات", "admin.votes.exports", ['votes'     => $votes, 'questions' => $questions,]);
		return $sheets;
	}
	
	private function getAnswerName($key) : string {
		$questions = [
			"1" => "به چه میزان از موارد زیر رضایت دارید؟",
			"2" => "سرعت پاسخگویی و در دسترس بودن کارشناس",
			"3" => "شیوه پاسخگویی و برخورد کارشناس",
			"4" => "کیفیت نگارش رزومه و انگیزه نامه خود توسط تیم اپلای جرمنی",
			"5" => "پشتیبانی بعد از پذیرش و انجام امور مرتبط با ویزا, ثبت نام دانشگاه, بیمه و درخواست خوابگاه",
			"6" => "به طور کلی چه میزان از خدمات و کیفیت کار تیم اپلای جرمنی رضایت داشتید؟",
			"7" => "چقدر احتمال دارد اپلای جرمنی را به دوستان و اشنایان خود معرفی کنید؟",
		];
		return isset($questions[$key]) ? $questions[$key] : "نا مشخص";
	}
	
	private function getAnswer($value) : string {
		switch ($value) {
			case 1:
				return "بسیار زیاد";
			case 2:
				return "زیاد";
			case 3:
				return "متوسط";
			case 4:
				return "کم";
			case 5:
				return "خیلی کم";
		}
		return "نا مشخص";
	}
	
	private function getType($value) : string {
		switch ($value) {
			case 1:
				return "کارشناس";
			case 2:
				return "مشــاور";
			case 3:
				return "امور گرافیکی";
			case 4:
				return "گرافیست";
			case 5:
				return "ادیتور ویدیو";
			case 6:
				return "برنامه نویس";
			case 7:
				return "تولیـــد محتوای متنی";
			case 8:
				return "روابط عمومی و فــــروش";
			case 9:
				return "تولید محتوای ویدیویی";
			case 10:
				return "نگارش رزومه و انگیـــــزه‌نامه";
			case 11:
				return "سفیر اپلای جرمنی در شهر سکونت خود در آلمان";
		}
		return "نا مشخص";
	}
	
}
