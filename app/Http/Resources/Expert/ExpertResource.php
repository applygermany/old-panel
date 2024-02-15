<?php

namespace App\Http\Resources\Expert;

use App\Models\ExpertTag;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'level' => $this->level,
            'score' => number_format($this->ownerComments->avg('score'),2),
            'description' => "",
            'tags' => []
        ];

        $tag = ExpertTag::where("expert_id", $this->id)->first();
        if($tag){
            $dataArray['description'] = $tag->description;
            $dataArray['tags'] = explode("|", $tag->tags);
        }

        $dataArray['image'] = route('imageUser',['id'=>$this->id,'ua'=>strtotime($this->updated_at)]);
	    $dataArray['hasImage'] = is_file(public_path('uploads/avatar/' . $this->id . '.jpg'));

        return $dataArray;
    }
}
