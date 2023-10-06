<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

class TourController extends Controller
{
    public function index(Travel $travel, TourListRequest $request)
    {
        $tours = $travel->tours()
            ->when($request->priceFrom, function ($q) use ($request) {
                $q->where('price', '>=', $request->priceFrom * 100);
            })
            ->when($request->priceTo, function ($q) use ($request) {
                $q->where('price', '<=', $request->priceTo * 100);
            })
            ->when($request->dateFrom, function ($q) use ($request) {
                $q->where('starting_date', '>=', $request->dateFrom);
            })
            ->when($request->dateTo, function ($q) use ($request) {
                $q->where('starting_date', '<=', $request->dateTo);
            })
            ->when($request->sortBy, function ($q) use ($request) {
                if (! in_array($request->sortBy, ['price']) || (! in_array($request->sortOrder, ['asc', 'desc']))) {
                    return;
                }

                $q->orderBy($request->sortBy, $request->sortOrder);
            })
            ->orderBy('starting_date')->paginate();

        return TourResource::collection($tours);
    }
}
