<?php
namespace App\Http\Resources\User;

use App\Models\ApplyLevelTitle;
use App\Providers\MyHelpers;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplyLevelsResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'pos' => $this->pos,
            'phase' => $this->phase,
            'text' => html_entity_decode($this->text),
            'link' => $this->link,

            'nextLevelButton' => $this->next_level_button,
            'image' => null,
            'files' => [],
            'title' => $this->title,
            'seen' => 0,
        ];
        $userApplyLevel = auth()->guard('api')->user()->userApplyLevels()->where('apply_level_id', $this->id)->first();
        if ($userApplyLevel) {
            $dataArray['seen'] = 1;
        }

        $dataArray['date'] = MyHelpers::dateToJalali($this->created_at);

        if (is_file(public_path('uploads/level/' . $this->id . '.jpg'))) {
            $file = route('imageLevel', ['id' => $this->id, 'ua' => strtotime($this->updated_at)]);
            $dataArray['image'] = $file;
        }

        $directory = public_path('/uploads/level-file/' . $this->id . '/');

        $filecount = 0;
        $files = glob($directory . '*');

        $filecount = count($files);

        $files = glob(public_path('uploads/level-file/' . $this->id . '/*.*'));
        $filenames =  ApplyLevelTitle::where("apply_level_id", $this->id)->orderBy("id", "asc")->get();
        for ($i = 0; $i < $filecount; $i++) {
            $ex = explode(".", $files[$i]);
            $file = route('fileLevel', ['id' => $this->id, "pos" => $i]);
            $dataArray['files'][] = ["file" => $file, "type" => $ex[count($ex) - 1], "filename" => $filenames[$i]->title ?? null];
        }


        return $dataArray;
    }
}
