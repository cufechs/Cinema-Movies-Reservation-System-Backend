<?php

namespace App\Http\Controllers;

use App\Models\MovieReservation;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class MovieReservationController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $moviereservations = MovieReservation::all();
        #$this->authorize('view', $moviereservations);

        return $this->returnData('moviereservation', $moviereservations, 200, 'moviereservations returned!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        #$this->authorize('create',MovieReservation::class);

        $validator = Validator::make(request()->all(), [
            "date" => "required",
            "start_time" => "required",
            "end_time" => "required",
            "capacity" => "required",
            "price" => "required",
            "vacant_reserved_seats" => "required",
            'movie_id' => "required"
        ]);

        $newStartTime = strtotime(request('start_time'));
        $newEndTime = strtotime(request('end_time'));

        //start time can't be greater than end time :D
        if($newStartTime >= $newEndTime)
            return $this->returnError($this->getErrorCode("start time shouldn't be greater than end time"), 404, "start time shouldn't be greater than end time");

        $sameDateReservations = MovieReservation::where('date', 'LIKE', request('date') . '%')->get();

        for($i=0; $i<count($sameDateReservations); $i++)
        {
            $movResv = $sameDateReservations[$i];

            $startTime = strtotime($movResv->start_time);
            $endTime = strtotime($movResv->end_time);

            if($newStartTime > $startTime && $newEndTime < $endTime ||
            ($newStartTime > $startTime && $newStartTime < $endTime) || ($newEndTime > $startTime && $newEndTime < $endTime) ||
            $newStartTime==$startTime || $newEndTime==$endTime||
            $startTime > $newStartTime && $endTime < $newEndTime)
            {
                //overlap in time
                return $this->returnError($this->getErrorCode('overlap in time of reservation'), 404, 'Overlap in time of reservation is not allowed!');
            }
        }

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $moviereservation = MovieReservation::create([
            'date' => request('date'),
            'start_time' => request('start_time'),
            'end_time' => request('end_time'),
            'capacity' => request('capacity'),
            'price' => request('price'),
            'vacant_reserved_seats' => request('vacant_reserved_seats'),
            'movie_id' => request('movie_id')
        ]);

        return $this->returnSuccessMessage('MovieReservation Created Successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($moviereservation)
    {
        $movieReservationFound = MovieReservation::find($moviereservation);
        #$this->authorize('viewAny', $userFound);

        if(!$movieReservationFound)
        {
            return $this->returnError($this->getErrorCode('moviereservation not found'), 404, 'moviereservation is not found');
        }

        return $this->returnData('moviereservation', $movieReservationFound, 200, 'moviereservation found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        #$this->authorize('create',MovieReservation::class);

        $moviereservation = MovieReservation::find($id);
        if ($moviereservation == null)
            return $this->returnError($this->getErrorCode('moviereservation not found'), 404, 'MovieReservation Not Found');

        $date = $request->input('date');
        $newStartTime = strtotime($request->input('start_time'));
        $newEndTime = strtotime($request->input('end_time'));

        if (($date != null && $date != $moviereservation->date) || ($newStartTime != null || $newEndTime != null))
        {
            if ($newStartTime == null)
                $newStartTime = $moviereservation->start_time;
            if ($newEndTime == null)
                $newEndTime = $moviereservation->end_time;
            if ($date == null)
                $date = $moviereservation->date;

            //start time can't be greater than end time :D
            if($newStartTime >= $newEndTime)
                return $this->returnError($this->getErrorCode("start time shouldn't be greater than end time"), 404, "start time shouldn't be greater than end time");

            $sameDateReservations = MovieReservation::where('date', 'LIKE', request('date') . '%')->get();

            for($i=0; $i<count($sameDateReservations); $i++)
            {
                $movResv = $sameDateReservations[$i];

                $startTime = strtotime($movResv->start_time);
                $endTime = strtotime($movResv->end_time);

                if($newStartTime > $startTime && $newEndTime < $endTime ||
                ($newStartTime > $startTime && $newStartTime < $endTime) || ($newEndTime > $startTime && $newEndTime < $endTime) ||
                $newStartTime==$startTime || $newEndTime==$endTime||
                $startTime > $newStartTime && $endTime < $newEndTime)
                {
                    //overlap in time
                    return $this->returnError($this->getErrorCode('overlap in time of reservation'), 404, 'Overlap in time of reservation is not allowed!');
                }
            }
        }

        $moviereservation->update($request->all());

        return $this->returnSuccessMessage('MovieReservation Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        #$this->authorize('delete', MovieReservation::class);

        $movieReservationFound = MovieReservation::find($id);

        if ($movieReservationFound == null)
            return $this->returnError($this->getErrorCode('moviereservation not found'), 404, 'MovieReservation Not Found');

        $movieReservationFound->delete();

        return $this->returnSuccessMessage("MovieReservation Deleted Successfully!");
    }
}
