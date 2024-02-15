<?php

namespace App\Http\Resources\Expert;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        $data = [
            'id' => $this->id,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'type' => (int)$this->type,
            'level' => (int)$this->level,
            'maxUniversityCount' => $this->max_university_count,
            'category' => $this->category_id,
            'upload_access' => $this->upload_access,
            'contractType' => $this->contract_type,
            'unableToWork' => $this->unable_to_work,
            'hasUploadAccess' => (int)$this->upload_access,
            'contractUser' => null,
        ];
        if ($this->acceptances->first()) {
            $data['languageFavor'] = $this->acceptances->first()->language_favor;
            $data['licenseLanguage'] = $this->acceptances->first()->license_language;
            $data['whatGradeLanguage'] = $this->acceptances->first()->what_grade_language;
            $data['admittance'] = $this->acceptances->first()->admittance;
            $data['diplomaGradeAverage'] = $this->acceptances->first()->diploma_grade_average;
            $data['fieldGrade'] = $this->acceptances->first()->field_grade;
        }
        $data['comment'] = new UserCommentResource($this->userComment);

        $index = 0;
        foreach ($this->userComments as $comment) {
            $data['comments'][$index] = new UserCommentResource($comment);
            $index++;
        }

        $data['image'] = route('imageUser', ['id' => $this->id, 'ua' => strtotime($this->updated_at)]);
        $data['hasImage'] = is_file(public_path('uploads/avatar/' . $this->id . '.jpg'));
        $data['hasContract'] = (bool)$this->uploads->where('type', 7)->first();

        if ($this->contract_open_id !== 0) {
            $expert = User::find($this->contract_open_id);
            $data['contractUser']['firstname'] = $expert->firstname;
            $data['contractUser']['lastname'] = $expert->lastname;
        } else {
            $data['contractUser']['firstname'] = '-';
            $data['contractUser']['lastname'] = '-';
        }

        return $data;
    }
}
