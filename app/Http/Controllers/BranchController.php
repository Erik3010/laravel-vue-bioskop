<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Branch::all();
        return response()->json(['message' => 'Sucess', 'data' => $data]);
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
        // return $request->all();
        $validate = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json(['message' => 'error'], 422);
        }

        Branch::create(['name' => $request->name]);

        return response()->json(['message' => 'success']); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::find($id);

        if(!$branch) {
            return response()->json(['message'=> 'error'], 422);
        }

        $name = $request->name;

        $validate = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json(['message' => 'error'], 422);
        }

        $branch->update([
            'name' => $name
        ]);

        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::find($id);

        if(!$branch) {
            return response()->json(['message' => 'Cannot find id'], 422);
        }

        $branch->delete();
        return response()->json(['message' => 'deleted'], 200);
    }
}
