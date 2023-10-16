<?php

namespace App\Console\Commands;

use App\Mail\sendBookingReminders;
use App\Models\Booking;
use App\Models\ContactsCommunication;
use App\Services\CommunicationService;
use App\Traits\CompanyIdentityTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyClientAboutBokking extends Command
{
    use CompanyIdentityTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:alertClientAboutBooking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var CommunicationService
     */
    private $communicationService;
    /**
     * @var ContactsCommunication
     */
    private $contactsCommunication;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        CommunicationService  $communicationService,
        ContactsCommunication $ContactsCommunication
    )
    {
        parent::__construct();
        $this->communicationService = $communicationService;
        $this->contactsCommunication = $ContactsCommunication;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info("Booking reminder sent Successfully");
        $this->communicationService->NotifyUserAboutBooking();
        $this->info('Booking reminder sent Successfully! ran successfully @ ' . \Carbon\Carbon::now());

    }


    public function NotifyUserAboutBooking()
    {

        $todayBookings = Booking::whereDate('start', Carbon::today())->with('patient')->get();

        $details = $this->CompanyIdentityDetails();

        foreach ($todayBookings as $bookings) {
//            dd($details['logo']);
            $bookingDetails = [
                'logo' => $details['logo'],
                'name' => $bookings->patient->first_name,
                'surname' => $bookings->patient->surname,
                'title' => $bookings->title,
                'start' => Carbon::parse($bookings->start)->format('d F, Y h:i:s A'),
                'end' => Carbon::parse($bookings->end)->format('d F, Y, h:i:s A'),
                'Notes' => $bookings->Notes
            ];

            try {

                Mail::to($bookings->patient->email)->send(new sendBookingReminders($bookingDetails));

            } catch (\Exception $e) {
                echo  $e;
            }

        }

    }
}
