<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Jobs\SyncPropertiesJob;
use Illuminate\Console\Command;

class SyncChannexCommand extends Command
{
    protected $signature = 'channex:sync {--property= : Sync specific property ID}';
    protected $description = 'Sync properties with Channex API';

    public function handle(): int
    {
        $propertyId = $this->option('property');

        if ($propertyId) {
            $property = Property::find($propertyId);
            if (!$property) {
                $this->error("Property {$propertyId} not found");
                return 1;
            }

            SyncPropertiesJob::dispatch($property);
            $this->info("Queued sync for property: {$property->name}");
        } else {
            $properties = Property::where('sync_status', '!=', 'syncing')
                ->get();

            foreach ($properties as $property) {
                SyncPropertiesJob::dispatch($property);
            }

            $this->info("Queued sync for {$properties->count()} properties");
        }

        return 0;
    }
}
