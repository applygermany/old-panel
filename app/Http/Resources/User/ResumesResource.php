<?php
namespace App\Http\Resources\User;

use App\Models\Invoice;
use App\Models\ResumeTemplate;
use App\Providers\MyHelpers;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Transaction;

class ResumesResource extends JsonResource
{

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }


    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'theme' => $this->theme,
            'language' => $this->language,
            'name' => $this->name,
            'family' => $this->family,
            'birthDate' => $this->birth_date,
            'birthPlace' => $this->birth_place,
            'phone' => $this->phone,
            'price' =>  Invoice::where("invoice_type", 'resume')->where("associate_id", $this->id)->first()->final_amount ?? 0,
            'currency' =>  Invoice::where("invoice_type", 'resume')->where("associate_id", $this->id)->first()->currency_title ?? 'ریال',
            'email' => $this->email,
            'address' => $this->address,
            'status' => $this->status,
            'color' => $this->color,
            'admin_edit' => $this->admin_comment,
            'admin_attachment' => $this->admin_attachment,
            'user_edit' => $this->user_comment,
            'main_file' => $this->admin_accepted_filename,
            'user_file' => $this->url_uploaded_from_user . ".pdf",
            'user_file_name' => file_exists(public_path('uploads/resumeUserFile/'. $this->id.'.pdf')) ? $this->id . ".pdf":"",
            'user_file_size' => file_exists(public_path('uploads/resumeUserFile/'. $this->id.'.pdf')) ? $this->formatSizeUnits(filesize(public_path('uploads/resumeUserFile/'. $this->id.'.pdf')))   : 0,
            'socialmediaLinks' => $this->socialmedia_links,

            'text' => $this->text,

        ];

        $dataArray['date'] = MyHelpers::dateToJalali($this->updated_at);
        $dataArray['image'] = is_file(public_path('uploads/resumeImage/'.$this->id.'.jpg')) ? route("imageResume", ["id" => $this->id]) : null;
        $template = ResumeTemplate::where('name',$this->theme)->first();
		if ($template) {
			$dataArray['image'] = $template->image;
		} else {
			$dataArray['image'] = '';
		}
        $dataArray['courses'] = ResumeCoursesResource::collection($this->courses()->get());
        $dataArray['educationRecords'] = ResumeEdusResource::collection($this->educationRecords()->get());
        $dataArray['hobbies'] = ResumeHobbiesResource::collection($this->hobbies()->get());
        $dataArray['languages'] = ResumeLanguagesResource::collection($this->languages()->get());
        $dataArray['researchs'] = ResumeResearchsResource::collection($this->researchs()->get());
        $dataArray['softwareKnowledges'] = ResumeSoftwareKnowledgesResource::collection($this->softwareKnowledges()->get());
        $dataArray['works'] = ResumeWorksResource::collection($this->works()->get());

        return $dataArray;
    }
}
