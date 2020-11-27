<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Tier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the data.
        if (env('DB_CONNECTION') == 'mysql') {
            Schema::disableForeignKeyConstraints();
            Tier::truncate();
            Service::truncate();
            Schema::enableForeignKeyConstraints();
        }

        // Seed the service.
        $pay = Service::create([
            'name' => 'Pay with Amprest',
            'slug' => 'pay',
            'description' => 'Safeguard and simplify your online transactions with the Amprest Technologies Web Services Payment Gateway.'
        ]);

        // Seed its tiers.
        $pay->tiers()->saveMany([
            Tier::create([
                'service_id' => $pay->id,
                'name' => 'HQ',
                'usage_limit' => 100000,
                'price' => 1,
                // 'status' => 'private'
            ]),
            Tier::create([
                'service_id' => $pay->id,
                'name' => 'Bronze',
                'usage_limit' => 500,
                'price' => 400.00
            ]),
            Tier::create([
                'service_id' => $pay->id,
                'name' => 'Silver',
                'usage_limit' => 1200,
                'price' => 800.00
            ]),
            Tier::create([
                'service_id' => $pay->id,
                'name' => 'Gold',
                'usage_limit' => 2500,
                'price' => 1200.00
            ]),
        ]);

        // Seed the service.
        $message = Service::create([
            'name' => 'Message with Amprest',
            'slug' => 'message',
            'description' => 'Send customisable message (SMS, Mail) services using the Amprest Technologies Web Services.'
        ]);

        // Seed its tiers.
        $message->tiers()->saveMany([
            Tier::create([
                'service_id' => $message->id,
                'name' => 'HQ',
                'usage_limit' => 100000,
                'price' => 1,
                // 'status' => 'private'
            ]),
            Tier::create([
                'service_id' => $message->id,
                'name' => 'Bronze',
                'usage_limit' => 500,
                'price' => 400.00
            ]),
            Tier::create([
                'service_id' => $message->id,
                'name' => 'Silver',
                'usage_limit' => 1200,
                'price' => 800.00
            ]),
            Tier::create([
                'service_id' => $message->id,
                'name' => 'Gold',
                'usage_limit' => 2500,
                'price' => 1200.00
            ]),
        ]);
    }
}
