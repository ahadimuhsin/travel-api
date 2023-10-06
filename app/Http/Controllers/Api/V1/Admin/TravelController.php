<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

class TravelController extends Controller
{
    public function store(TravelRequest $request)
    {
        $travel = Travel::create($request->validated());

        return new TravelResource($travel);
    }

    public function edit(Travel $travel)
    {
        return new TravelResource($travel);
    }

    public function update(Travel $travel, TravelRequest $travelRequest)
    {
        $travel->update($travelRequest->validated());

        return new TravelResource($travel);
    }
}
