<?php

namespace App\Http\Controllers\API;

use App\Anime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return response()->json(['data' => Anime::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $anime = new Anime();
        $anime->title = $request->title;
        $anime->episode = $request->episodes;
        $anime->genre = $request->genre;
        $anime->save();
        return response()->json(['message' => 'Anime added successfully!'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Anime $anime)
    {
        return response()->json(['anime' => $anime], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Anime $anime, Request $request)
    {
        $anime->title = $request->title;
        $anime->episode = $request->episodes;
        $anime->genre = $request->genre;
        $anime->save();

        return response()->json(['message' => $anime->title.' updated successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anime $anime)
    {
        $anime->delete();
        return response()->json(['message'=> 'Anime deleted successfully'], 200);
    }
}
