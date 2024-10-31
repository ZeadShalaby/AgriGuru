<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Sensor;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;

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
            //! add validation for type sensors
            $type_name = $this->getSensorType();
            $type = Sensor::where('user_id', auth()->user()->id)->where('type', $type)->orderBy('created_at', 'desc')->first();
            return $this->returnData($type_name, $type);
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }

    }

    public function store(Request $request)
    {
        try {
            $sensor = Sensor::create($request->all());
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
