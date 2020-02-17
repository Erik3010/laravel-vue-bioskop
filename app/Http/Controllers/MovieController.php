<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movie = Movie::all();

        return response()->json(['message' => 'success', 'data' => $movie], 200);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $minute_length = $request->minute_length;

        if($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $picture->getClientOriginalExtension();
            $picture->move(public_path('/images'), $picture->getClientOriginalName());
        }

        $validate = Validator::make($request->all(), [
            'name' => 'required|min:4|max:999',
            'minute_length' => 'required|numeric|between: 1,999'
        ]);
        
        if($validate->fails()) {
            return response()->json(['message' => 'invalid input'], 422);
        }

        Movie::create([
            'name' => $name,
            'minute_length' => $minute_length,
            'picture_url' => $picture->getClientOriginalName()
        ]);

        return response()->json(['message' => 'create success'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|min:4|max:999',
            'minute_length' => 'required|numeric|between:1,999'
        ]);

        if($validate->fails()){
            return response()->json(['message' => 'invalid input'], 422);
        }

        $name = $request->name;
        $minute_length = $request->minute_length;

        if($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $picture->getClientOriginalExtension();
            $picture->move(public_path('/images'), $picture->getClientOriginalName());
        }

        $movie->update([
            'name' => $name,
            'minute_length' => $minute_length,
            'picture_url' => $picture->getClientOriginalName() 
        ]);

        return response()->json(['message' => 'update success'], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Movie::find($id);
        if(!$movie) {
            return response()->json(['message' => 'invalid id'], 422);
        }
        $movie->delete();
        return response()->json(['message' => 'success'], 200);

    }
}
