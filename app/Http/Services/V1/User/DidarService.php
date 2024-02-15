<?php

namespace App\Http\Services\V1\User;

use App\Models\DidarUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DidarService
{
    private $apiKey = "frqjiboicvebjh130u484y5okr3muen9";

    public function __construct($apiKey = null)
    {
        if ($apiKey != null)
            $this->apiKey = $apiKey;
    }

    public function saveToTable($name, $value, $userId)
    {
        if(DidarUser::where('name', $name)->where('user_id', $userId)->first()){
            DidarUser::where('name', $name)->where('user_id', $userId)->delete();
        }
        $didarUser = new DidarUser();
        $didarUser->user_id = $userId;
        $didarUser->name = $name;
        $didarUser->value = $value;
        $didarUser->save();
    }

    public function updateTable($name, $value, $userId)
    {
        $didarUser = DidarUser::where('name', $name)->where('user_id', $userId)->first();
        $didarUser->value = $value;
        $didarUser->save();
    }

    public function deleteRow($id)
    {
        $didarUser = DidarUser::find($id);
        $didarUser->delete();
    }

    public function getData($id, $name)
    {
        $didarUser = DidarUser::where('user_id', $id)->where('name', $name)->first();;
        return $didarUser ? true : false;
    }

    public function updateDidarApi($id)
    {
        $didarUser = DidarUser::where('user_id', $id)->get();

        if ($didarUser->where('name', 'didar_id')->first()) {
            try {
                $client = new \GuzzleHttp\Client();
                $result = $client->request(
                    'POST',
                    'https://app.didar.me/api/contact/save?ApiKey=' . $this->apiKey,
                    [
                        'json' => [
                            "Contact" => [
                                'Id' => $didarUser->where('name', 'didar_id')->first()->value ?: '',
                                'FirstName' => $didarUser->where('name', 'FirstName')->first()->value ?: '',
                                'LastName' => $didarUser->where('name', 'LastName')->first()->value ?: '',
                                'MobilePhone' => $didarUser->where('name', 'MobilePhone')->first()->value ?: '',
                                'Email' => $didarUser->where('name', 'Email')->first()->value ?: '',
                                'OwnerId' => $didarUser->where('name', 'owner-id')->first()->value ?: '',
                                'Type' => 'Person',
                                'VisibilityType' => 'All',
                                'DisplayName' => $didarUser->where('name', 'DisplayName')->first()->value ?: '',
                                "Fields" => [
                                    "Field_996_0_22" => $didarUser->where('name', 'Field_996_0_22')->first()->value ?: '',
                                    "Field_996_0_7" => $didarUser->where('name', 'Field_996_0_7')->first()->value ?: '',
                                    "Field_996_4_9" => $didarUser->where('name', 'Field_996_4_9')->first()->value ?: '',
                                    "Field_996_0_8" => $didarUser->where('name', 'Field_996_0_8')->first()->value ?: '',
                                    "Field_996_0_10" => $didarUser->where('name', 'Field_996_0_10')->first()->value ?: '',
                                    "Field_996_4_11" => $didarUser->where('name', 'Field_996_4_11')->first()->value ?: '',
                                    "Field_996_4_18" => $didarUser->where('name', 'Field_996_4_18')->first()->value ?: '',
                                    "Field_996_0_16" => $didarUser->where('name', 'Field_996_0_16')->first()->value ?: '',
                                    "Field_996_3_19" => $didarUser->where('name', 'Field_996_3_19')->first()->value ?: '',
                                    "Field_996_1_13" => $didarUser->where('name', 'Field_996_1_13')->first()->value ?: '',
                                ],
                            ],
                        ], [
                        'Content-Type' => 'application/json',
                    ],
                    ]
                );
            } catch (\Exception $e) {
                //echo $e->getMessage();
            }
        }
    }

    public function insertDidarApi($id)
    {
        $didarUser = DidarUser::where('user_id', $id)->get();

        try {
            $client = new \GuzzleHttp\Client();
            $result = $client->request(
                'POST',
                'https://app.didar.me/api/contact/save?ApiKey=' . $this->apiKey,
                [
                    'json' => [
                        "Contact" => [
                            'FirstName' => $didarUser->where('name', 'FirstName')->first()->value ?: '',
                            'LastName' => $didarUser->where('name', 'LastName')->first()->value ?: '',
                            'MobilePhone' => $didarUser->where('name', 'MobilePhone')->first()->value ?: '',
                            'Email' => $didarUser->where('name', 'Email')->first()->value ?: '',
                            'OwnerId' => $didarUser->where('name', 'owner-id')->first()->value ?: '',
                            'Type' => 'Person',
                            'VisibilityType' => 'All',
                            'DisplayName' => $didarUser->where('name', 'DisplayName')->first()->value ?: '',
                            "Fields" => [
                                "Field_996_0_16" => $didarUser->where('name', 'Field_996_0_16')->first()->value ?: '',
                                "Field_996_3_19" => ($didarUser->where('name', 'Field_996_3_19')->first()->value === 1 ? true : false) ?: false,
                            ],
                        ],
                    ], [
                    'Content-Type' => 'application/json',
                ],
                ]
            );

            $this->saveToTable("didar_id", json_decode($result->getBody()->getContents())->Response->Id, $id);

            $user = User::find($id);
            $user->didar_user_id = json_decode($result->getBody()->getContents())->Response->Id;
            $user->save();
        } catch (\Exception $e) {
            //echo $e->getMessage();

        }
    }

    public function addNote($note, $title, $userId)
    {
        $didarUser = DidarUser::where('user_id', $userId)->get();
        if ($didarUser->where('name', 'didar_id')->first()) {
            try {
                $client = new \GuzzleHttp\Client();
                $result = $client->request(
                    'POST',
                    'https://app.didar.me/api/activity/save?ApiKey=' . $this->apiKey,
                    [
                        'json' => [
                            "Activity" => [
                                "ActivityTypeId" => "00000000-0000-0000-0000-000000000000",
                                'ResultNote' => $note,
                                "Duration" => 0,
                                'Note' => "",
                                "IsDone" => true,
                                'Title' => $title,
                                "DueDate" => Carbon::now(),
                                "DoneDate" => Carbon::now(),
                                "DueDateType" => "NoTime",
                                "DoneDateType" => "NoTime",
                                "RecurrenceEndDate" => Carbon::now(),
                                "RecurrenceType" => "OneTime",
                                "RecurrenceData" => 1,
                                "ContactIds" => [$didarUser->where('name', 'didar_id')->first()->value],
                                "SetDone" => false
                            ],
                        ], [
                        'Content-Type' => 'application/json',
                    ],
                    ]
                );
            } catch (\Exception $e) {
                //echo $e->getMessage();
            }
        }
    }

    public function addDeal($userId, $stageId)
    {
        $didarUser = DidarUser::where('user_id', $userId)->get();
        if ($didarUser->where('name', 'didar_id')->first()) {
            try {
                $client = new \GuzzleHttp\Client();
                $result = $client->request(
                    'POST',
                    'https://app.didar.me/api/deal/save?ApiKey=' . $this->apiKey,
                    [
                        'json' => [
                            "Deal" => [
                                "PersonId" => $didarUser->where('name', 'didar_id')->first()->value,
                                "PipelineStageId" => $stageId,
                                "Price" => $didarUser->where('name', 'Field_996_4_18')->first()->value === 'ویژه' ? "1000" : "800",
                                "OwnerId" => $didarUser->where('name', 'owner-id')->first()->value,
                                "DealItems" => [
                                    "ProductId" => $didarUser->where('name', 'Field_996_4_18')->first()->value === 'ویژه' ? "c6b9846b-ee86-4cea-93aa-a74e59dbc490" :
                                        "56812401-b240-4ba3-9f5e-2e6cb5095506",
                                    "Quantity" => 1,
                                    "UnitPrice" => $didarUser->where('name', 'Field_996_4_18')->first()->value === 'ویژه' ? 1000 : 800
                                ]
                            ],
                        ], [
                        'Content-Type' => 'application/json',
                    ],
                    ]
                );

                $this->saveToTable("deal_id", json_decode($result->getBody()->getContents())->Response->Id, $userId);
            } catch (\Exception $e) {
                Log::info($e);
                //echo $e->getMessage();
            }
        }
    }

    public function updateDeal($userId, $stageId)
    {
        $didarUser = DidarUser::where('user_id', $userId)->get();
        if ($didarUser->where('name', 'didar_id')->first()) {
            if ($didarUser->where('name', 'deal_id')->first()) {
                try {
                    $client = new \GuzzleHttp\Client();
                    $result = $client->request(
                        'POST',
                        'https://app.didar.me/api/deal/save?ApiKey=' . $this->apiKey,
                        [
                            'json' => [
                                "Deal" => [
                                    "Id" => $didarUser->where('name', 'deal_id')->first()->value,
                                    "PersonId" => $didarUser->where('name', 'didar_id')->first()->value,
                                    "PipelineStageId" => $stageId,
                                    "OwnerId" => $didarUser->where('name', 'owner-id')->first()->value,
                                    "Price" => $didarUser->where('name', 'Field_996_4_18')->first()->value === 'ویژه' ? "1000" : "800",
                                    "DealItems" => [
                                        "ProductId" => $didarUser->where('name', 'Field_996_4_18')->first()->value === 'ویژه' ? "c6b9846b-ee86-4cea-93aa-a74e59dbc490" :
                                            "56812401-b240-4ba3-9f5e-2e6cb5095506",
                                        "Quantity" => 1,
                                        "UnitPrice" => $didarUser->where('name', 'Field_996_4_18')->first()->value === 'ویژه' ? 1000 : 800
                                    ]
                                ],
                            ], [
                            'Content-Type' => 'application/json',
                        ],
                        ]
                    );
                } catch (\Exception $e) {
                    //echo $e->getMessage();
                }
            }
        }
    }
}
