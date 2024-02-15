<?php

namespace App\Http\Resources;

use App\Providers\MyHelpers;
use Illuminate\Http\Resources\Json\JsonResource;

class WebinarResource extends JsonResource {
	public function toArray($request) {
		
		$dataArray = [
			'id'                     => $this->id,
			'title'                  => $this->title,
			'time'                   => $this->time,
			'headlines'              => $this->headlines,
			'paymentText'            => $this->payment_text,
			'paymentLink'            => $this->payment_link,
			'link'                   => MyHelpers::get_link($this->slug),
			'price'                  => $this->price,
			'organizerName'          => $this->organizer_name,
			'organizerField'         => $this->organizer_field,
			'firstMeeting'           => $this->first_meeting,
			'firstMeetingStartTime'  => $this->first_meeting_start_time,
			'firstMeetingEndTime'    => $this->first_meeting_end_time,
			'secondMeeting'          => $this->second_meeting,
			'secondMeetingStartTime' => $this->second_meeting_start_time,
			'secondMeetingEndTime'   => $this->second_meeting_end_time,
			'status'                 => $this->status,
			'count'                  => 490 - $this->users->count(),
		];
		$dataArray['image'] = route('imageWebinar', ['id' => $this->id, 'ua' => strtotime($this->updated_at)]);
		$dataArray['imageOrganizer'] = route('imageWebinarOrganizer', [
			'id' => $this->id,
			'ua' => strtotime($this->updated_at),
		]);
		$dataArray['banner'] = route('webinarBanner', ['id' => $this->id, 'ua' => strtotime($this->updated_at)]);;
		return $dataArray;
	}
}
