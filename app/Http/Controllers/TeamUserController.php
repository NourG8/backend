<?php

namespace App\Http\Controllers;

use App\Models\TeamUser;
use App\Http\Requests\StoreTeamUserRequest;
use App\Http\Requests\UpdateTeamUserRequest;

class TeamUserController extends Controller
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
     * @param  \App\Http\Requests\StoreTeamUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeamUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamUser  $teamUser
     * @return \Illuminate\Http\Response
     */
    public function show(TeamUser $teamUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeamUser  $teamUser
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamUser $teamUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTeamUserRequest  $request
     * @param  \App\Models\TeamUser  $teamUser
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeamUserRequest $request, TeamUser $teamUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamUser  $teamUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamUser $teamUser)
    {
        //
    }
}
