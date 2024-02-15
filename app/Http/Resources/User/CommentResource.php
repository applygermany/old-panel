<?php
namespace App\Http\Resources\User;

use App\Providers\MyHelpers;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'text' => $this->text,
            'user'  => $this->userAuthor,
            'date'  => $this->created_at,
            'score' => $this->score,
            'author' => new UserResource($this->userAuthor)
        ];

        $dataArray['date'] = MyHelpers::dateToHuman($this->created_at);

        return $dataArray;
    }
}
