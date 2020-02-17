<?php

namespace App\Http\Controllers;

use App\Schedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Movie;
use App\Studio;
use Carbon\Carbon;

class SchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedule = Schedules::all();

        return response()->json(['message'=> 'success', 'data' => $schedule], 200);
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
        $studio_id = $request->studio_id;
        $movie_id = $request->movie_id;

        $validate = Validator::make($request->all(), [
            'studio_id' => 'required|numeric|exists:studios,id',
            'movie_id' => 'required|numeric|exists:movies,id',
            'start' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json(['message' => 'error'], 422);
        }

        $studio = Studio::find($request->studio_id);
        $movie = Movie::find($request->movie_id);

        $movieMin = $movie->minute_length;
        $basic_price = $studio->basic_price;
        $additional_friday_price = $studio->additional_friday_price;
        $additional_saturday_price = $studio->additional_saturday_price;
        $additional_sunday_price = $studio->additional_sunday_price;

        // manual PHP
        $startTime = $request->start;
        $day = date('D', strtotime($startTime));

        $time = strtotime($startTime);

        // check date for additional price
        if($day == 'Fri') {
            $basic_price += $additional_friday_price;
        } else if($day == 'Sat') {
            $basic_price += $additional_saturday_price;
        } else if($day == 'Sun') {
            $basic_price += $additional_sunday_price;
        }

        // using Laravel Carbon
        // $time = new Carbon($startTime);
        // $end = $time->addMinutes($movieMin);

        $end = date('Y-m-d H:i:s', strtotime('+'.$movieMin.' minutes', $time));

        $schedules = Schedules::all();

        foreach($schedules as $schedule) {
            if( $studio_id == $schedule->studio_id && (strpos($schedule->start, explode(' ', $startTime)[0]) !== false ) && $startTime >= $schedule->start && $startTime <= $schedule->end || $end >= $schedule->start && $end <= $schedule->end) {
                return response()->json(['message' => 'overlapped']);
            }
        }

        Schedules::create([
            'movie_id' => $request->movie_id,
            'studio_id' => $request->studio_id,
            'start' => $request->start,
            'end' => $end,
            'price' => $basic_price
        ]);
        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function show(Schedules $schedules)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedules $schedules)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $schedule = Schedules::find($id);

        if(!$schedule) {
            return response()->json(['message' => 'invalid id']);
        }

        $validate = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id|numeric',
            'studio_id' => 'required|exists:studios,id|numeric',
            'start' => 'required|'
        ]);

        if($validate->fails()) {
            return response()->json(['message' => 'invalid error']);
        }

        $movie_id = $request->movie_id;
        $studio_id = $request->studio_id;
        $startTime = $request->start;

        $movie =  Movie::find($movie_id);
        $studio = Studio::find($studio_id);

        $movieMin = $movie->minute_length;
        $basic_price = $studio->basic_price;
        $additional_friday_price = $studio->additional_friday_price;
        $additional_saturday_price = $studio->additional_saturday_price;
        $additional_sunday_price = $studio->additional_sunday_price;

        $day = date('D', strtotime($startTime));

        if($day == 'Fri') {
            $basic_price += $additional_friday_price;
        } else if($day == "Sat") {
            $basic_price += $additional_saturday_price;
        } else if($day == "Sun") {
            $basic_price += $additional_sunday_price;
        }

        $end = date('Y-m-d H:i:s', strtotime('+'.$movieMin.'minutes', strtotime($startTime)));

        $schedules = Schedules::all();
        foreach($schedules as $schedule) {
            if($studio_id == $schedule->studio_id && (strpos($schedule->start, explode(' ', $startTime)[0]) !== false) && $startTime >= $schedule->start && $startTime <= $schedule->end || $end >= $schedule->start && $end <= $schedule->end) {
                return response()->json(['messge' => 'overlapped'], 200);
            }
        }

        $schedule->update([
            'movie_id' => $movie_id,
            'studio_id' => $studio_id,
            'start' => $startTime,
            'end' => $end,
            'price' => $basic_price
        ]);

        return response()->json(['message' => 'update success'], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schedule = Schedules::find($id);

        if(!$schedule) {
            return response()->json(['message' => $schedule]);
        }

        $schedule->delete();
        return response()->json(['message' => 'success'], 200);

    }

    public function schedules(Request $request) {
        $schedules = Schedules::groupBy('movie_id', 'price');

        if(isset($request->date) && $request->date!="") {
            $date = $request->date;
            $schedules = Schedules::where('start', $date);
        }


        if(isset($request->branchId) && $request->branchId!="") {    
            $branch_id = $request->branchId;
            $studio =  Studio::where('branch_id', $branch_id)->get()->pluck('id');
            $schedules = Schedules::where('studio_id', $studio);
        }

        $schedules = $schedules->get();

        foreach($schedules as $schedule) {
            $movie_id = $schedule->movie_id;
            $movie_name = $schedule->movie->name;
            $price = $schedule->price;

            $jadwals = Schedules::where('movie_id', $movie_id)->where('price', $price)->get();

            foreach($jadwals as $jadwal) {
                $date = date('H:i', strtotime($jadwal->start));
                $start[] = $date;
            }

            $params = [
                'name' => $movie_name,
                'price' => $price,
                'start' => $start
            ];
            // unset($start);
        }
        return response()->json($params);
    }
}