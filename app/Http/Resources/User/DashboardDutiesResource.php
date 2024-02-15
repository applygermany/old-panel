<?php

namespace App\Http\Resources\User;

use App\Providers\JDF;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardDutiesResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'title' => $this->title,
            'diff' => [],
            'text' => $this->text ?? "",
            'status' => $this->status != 3 ? (time() > strtotime($this->deadline) ? 2 : 1) : $this->status,
            'deadline' => $this->deadline,
            'applyLevel' => null
        ];

        $date = explode('-', $this->deadline);
        $year = $date[0];
        $month = $date[1];
        $day = $date[2];

        $deadline = JDF::gregorian_to_jalali($year, $month, $day);
        $month = JDF::jdate_words(array('mm' => $deadline[1]));

        $diff = strtotime($this->deadline) - time();
        $dataArray["diff"] = [round($diff / (24 * 60 * 60)), round(($diff) % 24)];

        $dataArray['year'] = $deadline[0];
        $dataArray['month'] = $month['mm'];
        $dataArray['day'] = $deadline[2];


        if ($this->apply_level_id !== 0) {
            $dataArray['applyLevel']['title'] = $this->apply_level->title;
            $dataArray['applyLevel']['image'] = null;
            if (is_file(public_path('uploads/level/' . $this->apply_level->id . '.jpg'))) {
                $file = route('imageLevel', ['id' => $this->apply_level->id, 'ua' => strtotime($this->apply_level->updated_at)]);
                $dataArray['applyLevel']['image'] = $file;
            }
            $dataArray['applyLevel']['id'] = $this->apply_level->id;
            $dataArray['applyLevel']['pos'] = $this->apply_level->pos;
            $dataArray['applyLevel']['phase'] = $this->apply_level->phase;
            $dataArray['applyLevel']['text'] = html_entity_decode($this->apply_level->text);
            $dataArray['applyLevel']['link'] = $this->apply_level->link;
            $dataArray['applyLevel']['next_level_button'] = $this->apply_level->next_level_button;
        }

        return $dataArray;
    }
}