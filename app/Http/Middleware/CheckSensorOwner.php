<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Sensor;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;

class CheckSensorOwner
{
    use ResponseTrait;

    public function handle(Request $request, Closure $next)
    {
        // $sensor = Sensor::find($request->route('sensor_id'));

        // if (!$sensor || $sensor->user_id !== auth()->id()) {
        //     return $this->returnError('403', 'UnAuthorized Forbidden oops...');
        // }

        return $next($request);
    }
}
