<?php
namespace App\Http\Resources\User;

use App\Providers\MyHelpers;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
class SecondCommentResource extends ResourceCollection
{
	public $list;
	
	public function __construct($list) {
	
		$this->list = $list;
	}
    public function toArray($request)
    {
	    return $this->list->map(function ($item) {
		    $dataArray = [
			    'expert_id' => $item->owner,
			
			    'text' => $item->text,
			    'id'    => $item->id,
			    'user'  => $item->userAuthor,
			    'date'  => $item->created_at,
			    'score' => $item->score,
//			    'author' => new UserResource($this->userAuthor)
		    ];
		
//		    $dataArray['date'] = MyHelpers::dateToHuman($item->created_at);
		
		    return $dataArray;
	    });

    }
}
