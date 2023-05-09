<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Visit;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckForNewAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visits:check {months}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if there are any customers who need a new appointment based on the given months';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // INFORMATION:

        // process moet dagelijks lopen (doen we in de kernel.php file)
        // Kijkt of er een customer is die geen afspraak heeft in de toekomst
        // Kijkt ook of de customer zijn laatste afspraak minimaal 5 maand geleden is, dit moet een optie zijn en niet hard coded (variabele in de command)
        $customers = Customer::get();
        foreach($customers as $customer){
            $Newvisit = $customer->visits()->whereInFuture()->first();
            // continue if the customer already has an appointment planned in the future
            if($Newvisit !== null){
                continue;
            }

            // This also retrieves visits with null appointment date, this is neccesary for appointments that are not schedulded but already planned
            $lastVisit = $customer->visits()->OrderBy('appointment_date', 'desc')->first();
            // Customer has no visits yet
            if($lastVisit === null){
                $this->createNewAppointment($customer);
                continue;
            }

            $lastVisitDate = new Carbon($lastVisit->appointment_date);
            // Last appointment for customer is longer than .. months ago
            if($lastVisitDate->diffInMonths() > $this->argument('months')){
                $this->createNewAppointment($customer);
                continue;
            }
        }
        return Command::SUCCESS;
    }

    private function createNewAppointment(Customer $customer){
        $visit = new Visit();
        $visit->customer_id = $customer->id;
        $visit->report = "your automatic " .$this->argument('months') . " months checkup";
        #appointment date & time are nullable so we keep them null, maybe the customer cannot check in at the given time/date, thats why the admin has to update the visit.
        $visit->save();
    }
}
