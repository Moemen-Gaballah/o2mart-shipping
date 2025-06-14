<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CalculateShippingRequest;
use App\Services\ShippingCalculatorService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function calculate(CalculateShippingRequest $request, ShippingCalculatorService $shippingService){
        $result = $shippingService->calculate($request->validated());
        return response()->json($result); // TODO Trait for response
    }
}
