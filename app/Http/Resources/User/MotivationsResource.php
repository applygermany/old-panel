<?php
namespace App\Http\Resources\User;

use App\Models\Invoice;
use App\Models\Motivation;
use App\Models\Transaction;
use App\Providers\MyHelpers;
use Illuminate\Http\Resources\Json\JsonResource;

class MotivationsResource extends JsonResource
{
    public function toArray($request)
    {
    	$admins = json_decode($this->url_uploaded_from_admin,true);
    	$attachs = $this->admin_attachment;
        $dataArray = [
            'id' => $this->id,
            'status' => $this->status,
            'price' => Invoice::where("invoice_type", 'resume')->where("associate_id", $this->id)->first()->final_amount ?? 0,
            'currency' => Invoice::where("invoice_type", 'resume')->where("associate_id", $this->id)->first()->currency_title ?? 'ریال',
            'adminMessage' => $this->admin_message,
            'extraText' => $this->extra_text,
            'admin_edit' => $this->admin_comment,
            'universities' => $this->universities,
            'data' => Motivation::find($this->id),
            'user' => $this->user,
            'user_edit' => $this->user_comment,
            'main_file' => ((is_array($admins) and (sizeof($admins) > 0)) ? $admins[sizeof($admins) - 1] : ""),
            'admin_attachment' => ((is_array($attachs) and (sizeof($attachs) > 0)) ? $attachs[sizeof($attachs) - 1] : ""),
            'admin_attachments' => $attachs,
            'user_file' => $this->url_uploaded_from_user,
        ];

        $dataArray['date'] = MyHelpers::dateToJalali($this->created_at);
        $dataArray['image'] = route('imageMotivation',['id'=>$this->id]);
       
      

        return $dataArray;
    }
}
