<?php

namespace App\Http\Controllers;

use App\Models\MovieReservation;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use DateTime;

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
        #$this->authorize('view', $moviereservations);
        $moviereservations = MovieReservation::all();

        return $this->returnData('moviereservation', $moviereservations, 200, 'moviereservations returned!');
    }

    public function getMovieReservations($movieID)
    {
        #$this->authorize('view', $moviereservations);
        $moviereservations = MovieReservation::where('movie_id', 'LIKE', $movieID . '%')->where('end_time', '>', now() . '%')->get();

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
            'movie_id' => "required"
        ]);

        if (request('capacity') != 20 && request('capacity') != 30)
            return $this->returnError($this->getErrorCode("capacity should be 20 or 30 only"), 404, "capacity should be 20 or 30 only");

        $st = new DateTime(request('start_time'));
        $et = new DateTime(request('end_time'));
        $newStartTime = strtotime(strval($st->format('h:i:s')));
        $newEndTime = strtotime(strval($et->format('h:i:s')));

        //start time can't be greater than end time :D
        if($newStartTime >= $newEndTime)
            return $this->returnError($this->getErrorCode("start time shouldn't be greater than end time"), 404, "start time shouldn't be greater than end time");

        $sameDateReservations = MovieReservation::where('date', 'LIKE', request('date') . '%')->get();

        for($i=0; $i<count($sameDateReservations); $i++)
        {
            $movResv = $sameDateReservations[$i];

            $st = new DateTime($movResv->start_time);
            $et = new DateTime($movResv->end_time);
            $startTime = strtotime(strval($st->format('h:i:s')));
            $endTime = strtotime(strval($et->format('h:i:s')));

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

        $vancantSeats = '{"seats": [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]}';

        if (request('capacity') == 30)
            $vancantSeats = '{"seats": [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]}';
        
        $moviereservation = MovieReservation::create([
            'date' => request('date'),
            'start_time' => request('start_time'),
            'end_time' => request('end_time'),
            'capacity' => request('capacity'),
            'price' => request('price'),
            'vacant_reserved_seats' => $vancantSeats,
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

        if (request('capacity') != null && request('capacity') != 20 && request('capacity') != 30)
            return $this->returnError($this->getErrorCode("capacity should be 20 or 30 only"), 404, "capacity should be 20 or 30 only");

        $date = $request->input('date');
        $st = new DateTime(request('start_time'));
        $et = new DateTime(request('end_time'));
        $newStartTime = strtotime(strval($st->format('h:i:s')));
        $newEndTime = strtotime(strval($et->format('h:i:s')));

        if (($date != null && $date != $moviereservation->date) || ($request->input('start_time') != null || $request->input('end_time') != null))
        {
            if ($request->input('start_time') == null)
                $newStartTime = $moviereservation->start_time;
            if ($request->input('end_time') == null)
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

                if($movResv->id == $id)
                    continue;

                $st = new DateTime($movResv->start_time);
                $et = new DateTime($movResv->end_time);
                $startTime = strtotime(strval($st->format('h:i:s')));
                $endTime = strtotime(strval($et->format('h:i:s')));

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


    public function getUserReservation($user)
    {
        $userFound = User::find($user);
        if ($userFound == null)
            return $this->returnError($this->getErrorCode('user not found'), 404, 'User Not Found');

        $reservations = $userFound->moviereservations()->get();

        #if (count($reservations) == 0)
        #    return $this->returnError($this->getErrorCode('There is no reserved seat!'), 404, 'There is no reserved seat!');

        return $this->returnData('moviereservations', $reservations, 200, 'moviereservation found!');
    }

    public function addUserReservation(Request $request, $user)
    {
        $userFound = User::find($user);
        if ($userFound == null)
            return $this->returnError($this->getErrorCode('user not found'), 404, 'User Not Found');

        $reservation = $request->input('id');
        $reservationFound = MovieReservation::find($request->input('id'));
        if ($reservationFound == null)
            return $this->returnError($this->getErrorCode('reservation not found'), 404, 'Reservation Not Found');

        $seatNo = $request->input('seat_no');

        if ($seatNo > $reservationFound->capacity || $seatNo < 1)
            return $this->returnError($this->getErrorCode('seat number should be positive and smaller than capacity'), 404, 'seat number should be positive and smaller than capacity');

        $vacSeats = json_decode($reservationFound->vacant_reserved_seats, true)['seats'];
        
        if ($vacSeats[$seatNo - 1] == 1)
            return $this->returnError($this->getErrorCode('seat is already reserved!'), 404, 'seat is already reserved!');

        $vacSeats[$seatNo - 1] = 1;

        $reservationFound->vacant_reserved_seats = "{" . '"seats": ' . json_encode($vacSeats) . "}";

        $reservationFound->save();

        $userFound->moviereservations()->attach($reservationFound->id, ['seat_no' => $seatNo]);

        return $this->returnSuccessMessage('Reservation is Successful!');
    }

    public function deleteUserReservation(Request $request, $user)
    {
        $userFound = User::find($user);
        if ($userFound == null)
            return $this->returnError($this->getErrorCode('user not found'), 404, 'User Not Found');

        $reservation = $request->input('id');
        $reservationFound = MovieReservation::find($request->input('id'));
        if ($reservationFound == null)
            return $this->returnError($this->getErrorCode('reservation not found'), 404, 'Reservation Not Found');

        $vacSeats = json_decode($reservationFound->vacant_reserved_seats, true)['seats'];
        
        $reservations = $userFound->moviereservations()->get();

        if (count($reservations) == 0)
            return $this->returnError($this->getErrorCode('There is no reserved seat!'), 404, 'There is no reserved seat!');

        $st = new DateTime($reservationFound->start_time);
        $newStartTime = strtotime(strval($st->format('h:i:s')));
        if ($newStartTime - strtotime(strval(now()->format('h:i:s'))) < 10800)
            return $this->returnError($this->getErrorCode('You can\'t cancel this reservation, it\'s too late!'), 404, 'You can\'t cancel this reservation, it\'s too late!');

        $seatNo = 0;
        foreach($reservations as $reserv)
        {
            if ($reserv->id == $reservation)
            {
                $seatNo = $reserv->pivot->seat_no;
                $userFound->moviereservations()->detach($reservation);
                break;
            }
        }
      
        $vacSeats[$seatNo - 1] = 0;

        $reservationFound->vacant_reserved_seats = "{" . '"seats": ' . json_encode($vacSeats) . "}";

        $reservationFound->save();

        return $this->returnSuccessMessage('Deletion is Successful!');
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
