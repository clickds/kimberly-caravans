<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\VehicleEnquiry as Model;

class VehicleEnquiry extends Mailable
{
    use Queueable;
    use SerializesModels;

    public Model $vehicleEnquiry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Model $vehicleEnquiry)
    {
        $this->vehicleEnquiry = $vehicleEnquiry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.vehicle-enquiries.new');
    }
}
