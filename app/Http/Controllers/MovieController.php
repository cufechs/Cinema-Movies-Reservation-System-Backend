<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Database;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{

    use GeneralTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*try {
            $this->authorize('viewAny', $userFound);
        } catch (\Exception $e)
        {
            return $e;
        }*/

        return $this->returnData('movies', Movie::all(), 200, 'movies found!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        #$this->authorize('create',Movie::class);

        $validator = Validator::make(request()->all(), [
            "title" => "required",
            "poster_image" => "required",
            "description" => "required",
        ]);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $moive = Movie::create([
            'title' => request('title'),
            'poster_image' => request('poster_image'),
            'description' => request('description'),
        ]);

        return 'Movie added Successfully!';
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
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Movie::find($id);

        if(is_null($movie)){
            // TODO fix this
            return $this->returnError(404, $this->getErrorCode('movie not found'), 'movie not found');
        }

        return $this->returnData('movie', $movie, 200, 'movie found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $movie = Movie::where('id', '=', $id)->first();
        return view('movies.update', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $movie = Movie::find($id);
            if(!is_null($movie)) {
                $movie->update(request()->all());
                return 'Movie Updated ';
            }
            else
                return 'Movie does not exist';
        }
        catch(ModelNotFoundException $err){
            return 'error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $movie = Movie::find($id);
            if(!is_null($movie)) {
                $movie->delete();
                return 'Movie deleted';
            }
            else
                return 'Movie does not exist';
        }
        catch(ModelNotFoundException $err){
            return 'error!';
        }
    }
}
