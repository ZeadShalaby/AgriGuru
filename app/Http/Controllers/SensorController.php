<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Sensor;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SensorController extends Controller
{
    use ResponseTrait;

    // ? return all data of sensor for this user
    public function index()
    {
        try {
            $sensorsReading = Sensor::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
            return $this->returnData("sensorsReading", $sensorsReading);
        } catch (Exception $e) {
            $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }
    }

    public function show($type)
    {

        try {
            $validator = Validator::make(
                ['type' => $type],
                ['type' => 'required|integer|min:1|max:5']
            );
            // إذا فشل التحقق، نرجع الخطأ
            if ($validator->fails()) {
                return $this->returnError('400', $validator->errors()->first());
            }
            //! add validation for type sensors
            $type_name = Sensor::getSensorType($type); // إذا كانت الدالة عامة في النموذج
            $type = Sensor::select($type_name)->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
            return $this->returnData("type", $type);
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }

    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(), // تمرير جميع البيانات من الطلب
            [
                "user_id" => "required|exists:users,id",
                "temperature" => "required|integer|min:1|max:200",
                "humidity" => "required|integer|min:1|max:200",
                "light" => "required|integer|min:1|max:200",
                "gas" => "required|numeric|min:0.001|max:100", // يجب أن تكون float أو numeric
                "soil_moisture" => "required|integer|min:1|max:200",
            ]
        );

        // التحقق إذا فشل
        if ($validator->fails()) {
            return $this->returnError('400', $validator->errors()->first());
        }

        try {
            Sensor::create($request->all());
            return $this->returnSuccessMessage("Add Readings Success", "S000");
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }
    }


    // ?todo search product by name || price
    public function AutoComplete(Request $request)
    {
        try {
            $resultSearch = Sensor::whereAny(['name', 'price'], 'like', '%' . $request->search . '%')->get();
            return $this->returnSuccessMessage($resultSearch, "S000");
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }
    }
}
