<?php

namespace App\Http\Resources\Expert;

use App\Providers\JDF;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VoteResource extends ResourceCollection {
	public $list;
	
	public function __construct($list) {
		$this->list = $list;
	}
	
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param Request $request
	 *
	 * @return array|Arrayable|\JsonSerializable
	 */
	public function toArray($request) {
		return $this->list->map(function ($item) {
			$answers = json_decode($item->answer, true);
			return [
				'id'        => $item->id,
				'expert_id' => $item->expert_id,
				'user'      => $item->user_id,
				'date'      => $item->created_at,
				'score'     => (float)($answers ? $answers['6'] : 2.5),
			];
		});
	}
}
