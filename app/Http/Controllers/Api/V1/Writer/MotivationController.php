<?php

namespace App\Http\Controllers\Api\V1\Writer;

use App\Http\Controllers\Controller;
use App\Models\Acceptance;
use App\Models\Motivation;
use App\Models\User;
use Illuminate\Http\Request;

class MotivationController extends Controller
{
    function getMotivation($id)
    {
        $motivation = Motivation::find($id);

        $output = new \stdClass();
        $output = $motivation;
        $output->universities = $motivation->universities;

        $files = array();
        if ($motivation->url_uploaded_from_user) {
            $index = 0;
            foreach (json_decode($motivation->url_uploaded_from_user) as $key => $item) {
                $file = new \stdClass();
                $file->title = "دانلود فایل " . ($key + 1);
                $file->file = $item;
                $files[$index] = $file;
                $index++;
            }
        }
        $output->files = $files;
        $output->pdf = url('api/v1/writer/exports/pdf', ['type' => 'motivation', 'id' => $motivation->id]);

        return response([
            'status' => 1,
            'msg' => 'اطلاعات انگیزه نامه',
            'motivation' => $output,
            'acceptance' => $motivation->user->acceptances[0],
        ]);
    }
}
