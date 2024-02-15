<?php

namespace App\Http\Resources\Expert;

use App\Models\User;
use App\Models\Upload;
use App\Models\Acceptance;
use App\Models\UserComment;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AllUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::find($this->id);
        $data = [
            'id' => $this->id,
            'email' => $this->email,
            'mobile' => (strlen($user->mobile) > 5 ? $user->mobile : '--'),
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'type' => (int)$this->type,
            'level' => (int)$this->level,
            'maxUniversityCount' => $this->max_university_count,
            'category' => $this->category_id,
            'comment' => NULL,
            'comments' => NULL,
            'hasUploadAccess' => $this->upload_access === 1,
            'has_contract' => 0,
            'upload_access' => (int)$this->upload_access,
            'acceptance' => NULL,
            'contractType' => $user->contract_type,
            'unableToWork' => $user->unable_to_work,
            'contractUser' => null,
        ];
        $userAcceptance = Acceptance::where('user_id', $this->id)->first();
        $upload = Upload::where("user_id", $this->id)->where('type', 7)->first();
        $data['has_contract'] = (isset($upload) ? 1 : 0);
        if ($userAcceptance)
            $data['acceptance'] = $userAcceptance;

        $userComment = UserComment::where('user_id', $this->id)->where('owner_id', Auth::guard('api')->id())->first();
        if ($userComment)
            $data['comment'] = new UserCommentResource($userComment);

        $comments = UserComment::where('user_id', $this->id)->get();
        $index = 0;
        foreach ($comments as $comment) {
            $data['comments'][$index] = new UserCommentResource($comment);
            $index++;
        }

        $data['image'] = is_file(public_path('uploads/avatar/' . $this->id . '.jpg')) ? route('imageUser', [
            'id' => $this->id,
            'ua' => strtotime($this->updated_at),
        ]) : '';

        if ($user->contract_open_id !== 0) {
            $expert = User::find($user->contract_open_id);
            $data['contractUser']['firstname'] = $expert->firstname;
            $data['contractUser']['lastname'] = $expert->lastname;
        } else {
            $data['contractUser']['firstname'] = '-';
            $data['contractUser']['lastname'] = '-';
        }

        return $data;
    }
}
