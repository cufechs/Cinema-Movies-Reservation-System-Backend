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
        else
            return "";
    }


}
