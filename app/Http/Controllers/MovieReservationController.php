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

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $newpassword = bcrypt(request('password'));

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
            return $this->returnError(404, $this->getErrorCode('moviereservation not found'), 'moviereservation is not found');

        return $this->returnData('moviereservation', $userFound, 200, 'moviereservation found!');
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

        // $validator = Validator::make(request()->all(), [
        //     "username" => "unique:moviereservations,username,".$id,
        //     "email" => "email|unique:moviereservations,email,".$id,
        //     "role" => [Rule::in(['admin', 'customer','manager']),],
        //     "mobile_number" => "digits_between:10,11"
        // ]);

        // if ($validator->fails()) {
        //     $code = $this->returnCodeAccordingToInput($validator);
        //     return $this->returnValidationError($code, $validator);
        // }

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
