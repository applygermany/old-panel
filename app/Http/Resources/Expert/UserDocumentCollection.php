<?php

namespace App\Http\Resources\Expert;

use App\Models\Upload;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserDocumentCollection extends ResourceCollection
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
                'title' => $item->title,
                'text' => $item->type === 7 ? $item->user->contract_code : ($item->text == "" || $item->text == "null" ? "---" : $item->text),
                'date' => $item->date,
                'status' => $item->status,
                'type' => $item->type,
                'realTitle' => $item->realTitle?:'',
            ];
            $data['url'] = route('madrak', ['id' => $item->id]);

            $title = $item->title;
            switch ($item->type) {
                case 1:
                {
                    $title = 'تمامی مدارک دبیرستان';
                    break;
                }
                case 2:
                {
                    $title = 'گواهی قبولی کنکور';
                    break;
                }
                case 3:
                {
                    $title = 'مدرک زبان';
                    break;
                }
                case 4:
                {
                    $title = 'پاسپورت';
                    break;
                }
                case 5:
                {
                    $title = 'گواهی کار';
                    break;
                }
                case 6:
                {
                    $title = 'عکس پرسنلی';
                    break;
                }
                case 7:
                {
                    $title = 'قرارداد';
                    break;
                }
                case 8:
                {
                    $title = 'تمامی مدارک دوره کارشناسی';
                    break;
                }
                case 9:
                {
                    $title = 'توصیه نامه';
                    break;
                }
                case 10:
                {
                    $title = 'گواهی شرکت در دوره های تخصصی';
                    break;
                }
                case 11:
                {
                    $title = 'گواهی شرح دروس';
                    break;
                }
                case 12:
                {
                    $title = 'گواهی سیستم نمره دهی';
                    break;
                }
                case 13:
                {
                    $title = 'سایر مدارک';
                    break;
                }
            }

            $data['title'] = $title;

            return $data;
        });
    }
}
