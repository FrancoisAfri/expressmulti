<?php

namespace App\Console\Commands;

use App\Models\Packages;
use App\Models\Patient;
use App\Models\User;
use App\Services\crons\EmailInvoiceToAllClients;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send monthly PDF invoices to clients';
    private EmailInvoiceToAllClients $emailInvoiceToAllClients;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EmailInvoiceToAllClients $emailInvoiceToAllClients)
    {
        parent::__construct();
        $this->emailInvoiceToAllClients = $emailInvoiceToAllClients;
    }


    public function handle()
    {
        Log::info("Monthly invoices sent successfully.");
        $this->emailInvoiceToAllClients->EmailInvoiceToAllClientsDependingOnTheirSubscription();
        $this->info('Monthly invoices sent successfully. @ ' . \Carbon\Carbon::now());
    }

}
