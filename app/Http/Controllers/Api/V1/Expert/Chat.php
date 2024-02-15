<?php

namespace App\Http\Controllers\Api\V1\Expert;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Chat extends Controller
{
    protected $token = "88NT82VX4x8d1uoW2ZYGZhYd2Sgf5sc0t7YXQCLIuBgSKoOj5udWblS4NcfZtrbTNMmcf2Da7qU6QAnY0R9fjYfx10m0zfCS5jtZp5R3naSWJM7MFQ9X2dK4QtXanlFZ7rXa6PP81TfRu9JNt5ci9pkCLM013Pf7LRTWj69Yc1OITR3Eazcsc4NSyhVq3V0XhgIa3QUw";
    protected $failed_response = ["status" => "403", "message" => "Forbiden"];

    public function voteNotif(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        if (empty($request->userId)) {
            $request->userId = "all";
        }
        $client->request("POST", "https://chat.applygermany.net/notification", [
            'json' => [
                'to' => $request->userId,
                'body' => "در نظر سنجی کارشناس شرکت کنید",
                'title' => "شرکت در نظر سنجی",
                'arguments' => [
                    'expertId' => $request->expertId,
                    'type' => 'fromExpert',
                ],
            ],
            "headers" => [
                "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
            ],
        ]);
    }

    function getSupervisor(Request $request, $id)
    {
        if ($this->validate_source($request)) {
            if (DB::table("user_supervisors")->where("supervisor_id", $id)->exists()) {
                $return_response = [];
                $values = DB::table("user_supervisors")->where("supervisor_id", $id)->get();
                foreach ($values as $value) {
                    $values_users = DB::table("users")->where("id", $value->user_id)->get();
                    foreach ($values_users as $value_user) {
                        $result = [$value_user->firstname, $value_user->lastname, $value_user->id, $value_user->level];
                        array_push($return_response, $result);
                    }
                }
                $values = ["status" => 200, "data" => $return_response];
            } else {
                $values = ["status" => 206, "data" => "not_found"];
            }
            return $values;
        }
        return response()->json($this->failed_response, 403);
    }

    protected function validate_source($request)
    {
        if ($request->getClientIp() == "158.255.74.107" && $request->header("admin_token") == $this->token) {
            return 1;
        }
        return 0;
    }

    function getUser(Request $request, $id)
    {
        if ($this->validate_source($request)) {
            if (DB::table("user_supervisors")->where("user_id", $id)->exists()) {
                $return_response = [];
                $values = DB::table("user_supervisors")->where("user_id", $id)->get();
                foreach ($values as $value) {
                    $values_users = DB::table("users")->where("id", $value->supervisor_id)->get();
                    foreach ($values_users as $value_user) {
                        if ($value_user->level === 2) {
                            $result = [
                                $value_user->firstname,
                                $value_user->lastname,
                                $value_user->id,
                                $value_user->level,
                                is_file(public_path('uploads/avatar/' . $id . '.jpg')),
                            ];
                            array_push($return_response, $result);
                        }
                    }
                }
                $values = ["status" => 200, "data" => $return_response];
            } else {
                $values = ["status" => 206, "data" => "not_found"];
            }
            return $values;
        }
        return response()->json($this->failed_response, 403);
    }

    function adminUser(Request $request)
    {
        if ($this->validate_source($request)) {
            $return_response = [];
            $values = DB::table("users")->get();
            foreach ($values as $value) {
                if ($value->level == 1) {
                    if ($value->type == 2) {
                        $result = [$value->firstname, $value->lastname, $value->id, $value->level];
                        array_push($return_response, $result);
                    }
                } else {
                    $result = [$value->firstname, $value->lastname, $value->id, $value->level];
                    array_push($return_response, $result);
                }
            }
            $values = ["status" => 200, "data" => $return_response];
            return json_encode($values);
        }
        return response()->json($this->failed_response, 403);
    }

    function notification(Request $request)
    {
        if ($this->validate_source($request)) {
            $return_response = [];
            $detail = DB::table("users")->get();
            $admins = [];
            foreach ($detail as $experts) {
                if ($experts->level > 0) {
                    if ($experts->level == 1) {
                        if ($experts->type == 2) {
                            array_push($admins, $experts->id);
                        }
                    }
                    if ($experts->level == 4) {
                        $per = json_decode($experts->admin_permissions, 1);
                        if (isset($per["notification"])) {
                            if ($per["notification"] == 1) {
                                array_push($admins, $experts->id);
                            }
                        }
                    } else {
                        array_push($admins, $experts->id);
                    }
                }
            }
            $values = ["status" => 200, "data" => $admins];
            return json_encode($values);
        }
        return response()->json($this->failed_response, 403);
    }

    function experts(Request $request, $id)
    {
        if ($this->validate_source($request)) {
            $return_response = [];
            $detail = DB::table("users")->get();
            $admins = [];
            foreach ($detail as $experts) {
                if ($experts->level == $id || $experts->level == 4 || $experts->level == 3) {
                    if ($experts->level == 4) {
                        $per = json_decode($experts->admin_permissions, 1);
                        if (isset($per["chat"])) {
                            if ($per["chat"] == 1) {
                                $admins[$experts->id] = [
                                    "firstname" => $experts->firstname,
                                    "lastname" => $experts->lastname,
                                    "level" => $experts->level,
                                ];
                            }
                        }
                    } else {
                        $admins[$experts->id] = [
                            "firstname" => $experts->firstname,
                            "lastname" => $experts->lastname,
                            "level" => $experts->level,
                        ];
                    }
                }
            }
            $values = ["status" => 200, "data" => $admins];
            return json_encode($values);
        }
        return response()->json($this->failed_response, 403);
    }

    function availableConnections(Request $request)
    {
        if ($this->validate_source($request)) {
            $return_response = [];
            $values = DB::table("user_supervisors")->get();
            $details = DB::table("users")->get();
            $admins = [];
            foreach ($details as $experts) {
                if ($experts->level > 1) {
                    $admins[$experts->id] = [
                        "firstname" => $experts->firstname,
                        "lastname" => $experts->lastname,
                        "connections" => [],
                        "level" => $experts->level,
                    ];
                }
            }
            foreach ($values as $value) {
                if (array_key_exists($value->supervisor_id, $admins)) {
                    foreach ($details as $detail) {
                        if ($detail->id == $value->user_id) {
                            $value->firstname = $detail->firstname;
                            $value->lastname = $detail->lastname;
                            $value->level = $detail->level;
                            array_push($admins[$value->supervisor_id]["connections"], $value);
                        }
                    }
                }
            }
            $values = ["status" => 200, "data" => $admins];
            return json_encode($values);
        }
        return response()->json($this->failed_response, 403);
    }
}


