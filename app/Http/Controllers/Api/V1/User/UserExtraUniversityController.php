<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storeuser_extra_universityRequest;
use App\Http\Requests\Updateuser_extra_universityRequest;
use App\Models\user_extra_university;

class UserExtraUniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeuser_extra_universityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeuser_extra_universityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\user_extra_university  $user_extra_university
     * @return \Illuminate\Http\Response
     */
    public function show(user_extra_university $user_extra_university)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\user_extra_university  $user_extra_university
     * @return \Illuminate\Http\Response
     */
    public function edit(user_extra_university $user_extra_university)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateuser_extra_universityRequest  $request
     * @param  \App\Models\user_extra_university  $user_extra_university
     * @return \Illuminate\Http\Response
     */
    public function update(Updateuser_extra_universityRequest $request, user_extra_university $user_extra_university)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user_extra_university  $user_extra_university
     * @return \Illuminate\Http\Response
     */
    public function destroy(user_extra_university $user_extra_university)
    {
        //
    }
}
