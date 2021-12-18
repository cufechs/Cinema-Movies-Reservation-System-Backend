<?php

namespace App\Http\Traits;

trait GeneralTrait
{
    
    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($msg="Error",$errNum="00")
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }


    public function returnSuccessMessage($msg = "", $errNum = "S000")
    {
        return [
            'status' => true,
            'errNum' => $errNum,
            'msg' => $msg
        ];
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value
        ]);
    }


    //////////////////
     public function returnValidationError($validator,$code = "E001")
     {
         return $this->returnError($msg=$validator->errors()->first(),$errNum=$code);
         //return $this->returnError($validator->errors()->first());
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
        else
            return "";
    }


}
