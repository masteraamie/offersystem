<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Support\Facades\Storage;
use PDF;

class PDFController extends Controller
{
    public function generateOfferPDF($offerId)
    {
        $offer = Offer::find($offerId);

        if ($offer) {
            $data = [
                'offer' => $offer
            ];

            $pdf = PDF::loadView('offerpdf', $data);

            Storage::put('public/pdfs/offer_pdf_' . $offer->id . '.pdf', $pdf->output());

            return $pdf->download('offer_pdf_' . $offer->id . '.pdf');
        }
    }
}
