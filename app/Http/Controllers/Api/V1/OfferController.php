<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\Api\V1\OffersFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Http\Resources\V1\OfferCollection;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request, OffersFilter $offersFilter): OfferCollection
    {
        echo "HELLO"; exit();

        $filterItems = $offersFilter->transform($request);

        $offers = Offer::where($filterItems)
            ->with('room.hotel')
            ->paginate();

        return new OfferCollection($offers->appends($request->query()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOfferRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOfferRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOfferRequest  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOfferRequest $request, Offer $offer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        //
    }
}
