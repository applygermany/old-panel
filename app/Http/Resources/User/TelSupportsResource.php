<?php

namespace App\Http\Resources\User;

use App\Models\User;
use App\Models\Comment;
use App\Models\ExpertTag;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Expert\ExpertResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TelSupportsResource extends JsonResource
{
    public function toArray($request)
    {
        if (auth()->guard("api")->user()->type == 1) {
            $expert_level = User::find($this->user_id)->level;
            $comments = Comment::where('owner', $this->user_id)->where('status', 1)->orderBy('id', 'DESC')->get();
            if ($expert_level == 5 || $expert_level == 7) {

                $dataArray = [
                    'id' => $this->user_id,
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'status' => 0,
                    'score' => 0,
                    'description' => "",
                    'tags' => [],
                    'comments' => $comments,
                    'permission' => '1',
                    'level' => $this->sup_level
                ];
                $totalScore = DB::select('select avg(score) as total_score from nag_comments where type = 2 and owner = ' . $this->user_id);
                $tag = ExpertTag::where("expert_id", $this->user_id)->first();
                if ($tag) {
                    $dataArray['description'] = $tag->description;
                    $dataArray['tags'] = explode("|", $tag->tags);
                }
                if ($totalScore[0]->total_score) {
                    $dataArray['score'] = number_format($totalScore[0]->total_score, 1);
                }
                $dataArray['image'] = route('imageUser', [
                    'id' => $this->user_id,
                    'ua' => strtotime($this->updated_at),
                ]);
                return $dataArray;
            }
        } else {
            $expert_level = User::find($this->user_id)->level;
            if ($expert_level == 3 || $expert_level == 5 || $expert_level == 7) {
                $dataArray = [
                    'id' => $this->user_id,
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'status' => 1,
                    'score' => 0,
                    'description' => "",
                    'tags' => [],
                    'comments' => [],
                    'permission' => User::find($this->user_id)->admin_permissions->sup_tel,
                    'level' => $this->sup_level
                ];
                $totalScore = DB::select('select avg(score) as total_score from nag_comments where type = 2 and owner = ' . $this->user_id);
                if ($totalScore[0]->total_score) {
                    $dataArray['score'] = number_format($totalScore[0]->total_score, 1);
                }
                $tag = ExpertTag::where("expert_id", $this->user_id)->first();
                if ($tag) {
                    $dataArray['description'] = $tag->description;
                    $dataArray['tags'] = explode("|", $tag->tags);
                }
                $dataArray['image'] = route('imageUser', [
                    'id' => $this->user_id,
                    'ua' => strtotime($this->updated_at),
                ]);
                return $dataArray;
            }
        }
    }
}
