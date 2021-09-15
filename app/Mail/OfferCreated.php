<?php

namespace App\Mail;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $offer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Our Offer')
            ->view('offeremail')->attach(asset($this->offer->pdf_url), [
                'as' => 'offer_' . $this->offer->id . '.pdf',
                'mime' => 'application/pdf',
            ])->with(['offer' => $this->offer]);
    }
}
