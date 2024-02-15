<?php

namespace App\Http\Services\V1\User;

use App\Models\Votes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class VoteService {
	public function submitVote(Request $request) {
		$vote = new Votes();
		$vote->expert_id = $request->expertId;
		$vote->user_id = $request->userId;
		$vote->answer = json_encode($request->answer);
		$vote->types = json_encode($request->types);
		$vote->comment = $request->comment;
		$vote->recommend = $request->recommend;
		if ($vote->save()) {
			return 1;
		}
		return 0;
	}
}
