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
    public function handle(){
        $customers = Customer::with(['visits' => function($query){
            $query->orderBy('appointment_date', 'desc');
        }])->get()->each(function ($customer){
            // Retrieve the latest visit
            $lastVisit = $customer->visits->first();

            // a visit is not yet created
            if($lastVisit === null){
                $this->createNewAppointment($customer);
                return true;
            }

            $lastVisitDate = new Carbon($lastVisit->appointment_date);
            // Last appointment for customer is longer than .. months ago
            if($lastVisitDate->diffInMonths() > $this->argument('months')){
                $this->createNewAppointment($customer);
                return true;
            }
            return true;
        });

        // Command succesfully run
        return Command::SUCCESS;
    }

    private function createNewAppointment(Customer $customer){
        $visit = new Visit();
        $visit->customer_id = $customer->id;
        $visit->report = "your automatic " .$this->argument('months') . " months checkup";
        #appointment date & time are nullable so we keep them null, maybe the customer cannot check in at the given time/date, thats why the admin has to update the visit.
        $visit->save();
        echo "Created an appointment for customer " . $customer->name . "\n";
    }
}
