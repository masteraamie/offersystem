<?php

namespace App\Http\Controllers;

use App\Mail\OfferCreated;
use App\Models\Offer;
use App\Models\OfferVehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;

class OfferController extends Controller
{
    public function home()
    {
        return view('offers');
    }

    public function index()
    {
        return response(Offer::all()->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $offer =  new Offer();
        $offer->customer_id = $request->customer_id;
        $offer->save();

        foreach ($request->vehicles as $v) {
            $offerVehicle = new OfferVehicle();
            $offerVehicle->offer_id = $offer->id;
            $offerVehicle->vehicle_id = $v['vehicle_id'];
            $offerVehicle->quantity = $v['quantity'];
            $offerVehicle->save();
        }

        $offer = Offer::find($offer->id);
        $data = [
            'offer' => $offer
        ];

        $pdf = PDF::loadView('offerpdf', $data);

        Storage::put('public/pdfs/offer_pdf_' . $offer->id . '.pdf', $pdf->output());

        $offer->pdf_url = 'storage/pdfs/offer_pdf_' . $offer->id . '.pdf';
        $offer->save();

        Mail::to($offer->customer->email)->send(new OfferCreated($offer));

        return response($offer->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OfferVehicle::where('offer_id', $id)->delete();
        Offer::destroy($id);
        return response(null, Response::HTTP_OK);
    }
}
