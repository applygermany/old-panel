<?php

namespace App\Http\Resources\Expert;

use App\Models\User;
use App\Models\UserProcess;
use App\Models\UserUniversity;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserProcessCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            $data = [];

            $data['id'] = $item->id;
            $data['userId'] = $item->user_id;
            $data['phone'] = $item->phone;
            $data['admittance'] = $item->admittance;
            $data['field_grade'] = $item->field_grade;
            $data['diploma_grade_average'] = $item->diploma_grade_average;
            $data['field_license'] = $item->field_license;
            $data['average_license'] = $item->average_license;
            $data['what_grade_language'] = $item->what_grade_language;

            $user = User::find($item->user_id);
            $data['user'] = new ExpertResource($user);

            $data['process'] = [];
            $process = UserProcess::where('user_id',$item->user_id)->first();
            if(!$process) {
                $process = new UserProcess();
                $process->user_id = $item->user_id;
                $process->save();
            }
            $process = UserProcess::where('user_id',$item->user_id)->first();
            $process->time_to_next_tracking = null;
            if($process->next_tracking != null) {
                $now = time();
                $next_tracking = strtotime($process->next_tracking);
                $datediff = $next_tracking - $now;
                $datediff = round($datediff / (60 * 60 * 24));
                if($datediff >= 0)
                    $process->time_to_next_tracking = $datediff;
                else
                    $process->time_to_next_tracking = null;
            }
            $data['process'] = $process;

            $supervisor = User::find($item->supervisorID);
            $data['expert'] = new ExpertResource($supervisor);

            $universities = UserUniversity::where('user_id',$item->user_id)->get();
            $data['universities'] = new UserUniversityCollection($universities);
            $data['universitiesCount'] = count($universities);

            return $data;
        });
    }
}
