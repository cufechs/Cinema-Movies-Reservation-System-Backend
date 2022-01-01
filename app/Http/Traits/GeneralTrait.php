<?php

namespace App\Http\Traits;

trait GeneralTrait
{
    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($errNum, $code, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ], $code);
    }

    public function returnSuccessMessage($msg = "", $code = 200, $errNum = "S000")
    {
        return response()->json([
            'status' => true,
            'errNum' => $errNum,
            'msg' => $msg
        ], $code);
    }

    public function returnData($key, $value, $code = 200, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value
        ], $code);
    }


    //////////////////
    public function returnValidationError($code = "E001", $validator)
    {
        // dd($validator);
        return $this->returnError($code, 400, $validator->errors()->first());
    }


     public function returnCodeAccordingToInput($validator)
     {
         $inputs = array_keys($validator->errors()->toArray());
         $code = $this->getErrorCode($inputs[0]);
         return $code;
     }

    public function getErrorCode($input)
    {
        if ($input == "first_name")
            return 'E000';
        else if ($input == "last_name")
            return 'E001';
        else if ($input == "password")
            return 'E002';
        else if ($input == "mobile")
            return 'E003';
        else if ($input == "email")
            return 'E004';
        else if ($input == "id")
            return 'E005';
        else if ($input == "price")
            return 'E006';
        else if ($input == "user not found")
            return 'E007';
        else if ($input == "incorrect email or password")
            return 'E008';
        else if ($input == "movie not found")
            return 'E009';
        else if ($input == "moviereservation not found")
            return 'E010';
        else if ($input == "overlap in time of reservation")
            return 'E011';
        else if ($input == "start time shouldn't be greater than end time")
            return 'E012';
        else if ($input == "reservation not found")
            return 'E013';
        else if ($input == "capacity should be 20 or 30 only")
            return 'E014';
        else if ($input == "seat number should be positive and smaller than capacity")
            return 'E015';
        else if ($input == "seat is already reserved!")
            return 'E016';
        else if ($input == "There is no reserved seat!")
            return 'E017';
        else if ($input == "You can\'t cancel this reservation, it\'s too late!")
            return 'E018';
        else
            return "";
    }


}
