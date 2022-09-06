<?php

namespace App\Http\Controllers;

use App\Models\WatchList;
use App\Http\Requests\StoreWatchListRequest;
use App\Http\Requests\UpdateWatchListRequest;
use Illuminate\Support\Facades\Auth;

class WatchListController extends Controller
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
     * @param  \App\Http\Requests\StoreWatchListRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWatchListRequest $request)
    {
        $validated = $request->validated();

        $newWatchList = new WatchList($validated);
        $newWatchList->user()->associate(Auth::user());
        $newWatchList->save();

        $newWatchList = $newWatchList->load('user');

        return response()->json($newWatchList);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WatchList  $watchList
     * @return \Illuminate\Http\Response
     */
    public function show(WatchList $watchList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WatchList  $watchList
     * @return \Illuminate\Http\Response
     */
    public function edit(WatchList $watchList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWatchListRequest  $request
     * @param  \App\Models\WatchList  $watchList
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWatchListRequest $request, WatchList $watchList)
    {
        $validated = $request->validated();

        $watchList->users()->syncWithoutDetaching([Auth::id() => $validated]);

        return response()->json($watchList);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WatchList  $watchList
     * @return \Illuminate\Http\Response
     */
    public function destroy(WatchList $watchList)
    {
        //
    }
}
