<?php

namespace App\Http\Resources\Expert;

use App\Models\UserComment;
use App\Models\UserTelSupportInformation;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class TelSupportCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            $data = [
                'id' => $item->id,
                'dayTel' => $item->day_tel,
                'dayTelFa' => $item->day_tel_fa,
                'fromTime' => $item->from_time,
                'toTime' => $item->to_time,
                'type' => $item->type,
                'status' => $item->status,
                'user' => [],
                'advise' => ''
            ];

            if ($item->userTell) {
                if($item->userTell->user) {
                    $data['user'] = $item->userTell->user ? new UserResource($item->userTell->user) : null;
                    $data['advise'] = $item->userTell->title;
                    $data['information'] = UserTelSupportInformation::where('user_id', $item->userTell->user->id)
                        ->where('tel_support_id', $item->id)->first();
                    $data['information']['user_id'] = $item->userTell->user->id;

                    $comment = UserComment::orderBy('id', 'desc')->where('user_id', $item->userTell->user->id)->where('owner_id', Auth::guard('api')->id());
                    $data['information']['comment'] = $comment ? $comment->first() : null;

                    $comments = UserComment::orderBy('id', 'desc')->where('user_id', $item->userTell->user->id)->get();
                    $index = 0;
                    foreach ($comments as $comment1) {
                        $data['information']['comments'][$index] = new UserCommentResource($comment1);
                        $index++;
                    }
                }
            }
            return $data;
        });
    }
}
