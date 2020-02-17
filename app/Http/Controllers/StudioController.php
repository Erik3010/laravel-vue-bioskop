<?php

namespace App\Http\Controllers;

use App\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class StudioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studios = Studio::all();
        $data = [];

        foreach($studios as $studio) {
            $data[] = [
                'id' => $studio->id,
                'name' => $studio->name,
                'basic_price' => $studio->basic_price,
                'additional_friday_price' => $studio->additional_friday_price,
                'additional_saturday_price' => $studio->additional_saturday_price,
                'additional_sunday_price' => $studio->additional_sunday_price,
                'branch_name' => $studio->branch->name
            ];
        }

        return response()->json(['message' => 'success', 'data' => $data], 200);
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
        $validate = Validator::make($request->all(), [
            'name' => 'required|min:4|max:100',
            'branch_id' => 'required|numeric|exists:branches,id',
            'basic_price' => 'required|numeric|between:1,1000000',
            'additional_friday_price' => 'required|numeric|between:0,1000000',
            'additional_saturday_price' => 'required|numeric|between: 0,1000000',
            'additional_sunday_price' => 'required|numeric|between:0,1000000' 
        ]);
        
        if($validate->fails()) {
            return response()->json(['message' => 'invalid field'], 422);
        }

        Studio::create([
            'name' => $request->name,
            'branch_id' => $request->branch_id,
            'basic_price' => $request->basic_price,
            'additional_friday_price' => $request->additional_friday_price,
            'additional_saturday_price' => $request->additional_saturday_price,
            'additional_sunday_price' => $request->additional_sunday_price,
        ]);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Studio  $studio
     * @return \Illuminate\Http\Response
     */
    public function show(Studio $studio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Studio  $studio
     * @return \Illuminate\Http\Response
     */
    public function edit(Studio $studio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Studio  $studio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $studio = Studio::find($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|min:4|max:100',
            'branch_id' => 'required|numeric|exists:branches,id',
            'basic_price' => 'required|numeric|between:1,1000000',
            'additional_friday_price' => 'required|numeric|between:0,100000',
            'additional_saturday_price' => 'required|numeric|between:0,100000',
            'additional_sunday_price' => 'required|numeric|between:0,100000',
        ]);

        if($validate->fails()) {
            return response()->json([
                'message' => 'invalid field'
            ], 422);
        }

        $studio->update([
            'name' => $request->name,
            'branch_id' => $request->branch_id,
            'basic_price' => $request->basic_price,
            'additional_friday_price' => $request->additional_friday_price,
            'additional_saturday_price' => $request->additional_saturday_price,
            'additional_sunday_price' => $request->additional_sunday_price,
        ]);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Studio  $studio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $studio = Studio::find($id);

        $studio->delete();

        return response()->json(['message' => 'success'], 200);
    }
}
