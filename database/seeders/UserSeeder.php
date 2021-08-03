<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\MPesaCredentials;
use App\Models\Project;
use App\Models\SenderID;
use App\Models\Subscription;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;

class UserSeeder extends Seeder
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
            Subscription::truncate();
            MPesaCredentials::truncate();
            Project::truncate();
            Membership::truncate();
            Team::truncate();
            User::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $alvin = User::create([
            'name' => 'Alvin K. Gichira',
            'email' => 'geekaburu@amprest.co.ke',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        $alvin->ownedTeams()->create([
            'name' => "Alvin's Team",
            'personal_team' => true,
        ]);

        $brian = User::create([
            'name' => 'Brian K. Kiragu',
            'email' => 'brian@amprest.co.ke',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        $brian->ownedTeams()->create([
            'name' => "Brian's Team",
            'personal_team' => true,
        ]);

        // Seed the Amprest user.
        $amprest = User::create([
            'name' => 'Amprest Technologies',
            'email' => 'dev@amprest.co.ke',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->ownedTeams()->create([
            'name' => 'Amprest Technologies',
            'personal_team' => true,
        ]);
        $amprest->users()->attach(
            Jetstream::findUserByEmailOrFail($alvin->email),
            ['role' => 'administrator']
        );
        $amprest->users()->attach(
            Jetstream::findUserByEmailOrFail($brian->email),
            ['role' => 'administrator']
        );

        // Seed the projects.
        $transact = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Amprest Transact',
            'description' => 'Amprest Technologies Transact Service',
            'pay_callback' => ''
        ]);

        $masomo = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Masomo by Amprest',
            'description' => 'Amprest Technologies Masomo Education Platform',
            'uuid' => '48236419',
            'api_key' => '329ac8c2a9901864f9bab2c4603de9ba',
        ]);

        $saimun = Project::create([
            'team_id' => $amprest->id,
            'name' => 'SAIMUN Registration',
            'description' => 'Sub-Saharan International Model United Nations Registration Platform',
            'uuid' => '31980374',
            'api_key' => '29210a4bcc93d3c1c4a7e316c04f1465',
        ]);

        $cakeUniverse = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Cake Universe KE',
            'description' => 'Cake Universe Cake Resellers Kenya'
        ]);

        $minie = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Minie By Amprest',
            'description' => 'Amprest Technologies Minie Application',
            'pay_callback' => '',
            'uuid' => '10000001',
            'api_key' => 'adcce7437dca604f4b4515397465c252',
        ]);

        $rms = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Residents Management System',
            'description' => 'Amprest Technologies Residents Management System',
            'pay_callback' => 'http://197.137.65.245/api/mpesa/transactions',
            'uuid' => '10000002',
            'api_key' => '46ce0d0fd8c4326c19fc9d1a4f90af78',
        ]);

        // Seed the M-Pesa credentials.
        $transact->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $transact->id,
                'short_code' => '204440',
                'operating_short_code' => '204440',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'aWhxai3dsFDUf09YbWnlpo334F9DhxR3',
                'consumer_secret' => '6Xa1MMAGCMvSHQoi',
                'pass_key' => '157374172682982e8e44847210d710d03136b8d01f955ef94f0ef3f614d51657',
                'app_user_name' => 'amprest',
                'app_user_password' => '#Amprest-1234!',
            ])
        );

        //  Seed rms mpesa credentials
        $rms->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $rms->id,
                'short_code' => '4020309',
                'operating_short_code' => '4020309',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'aWhxai3dsFDUf09YbWnlpo334F9DhxR3',
                'consumer_secret' => '6Xa1MMAGCMvSHQoi',
                'pass_key' => '157374172682982e8e44847210d710d03136b8d01f955ef94f0ef3f614d51657',
                'app_user_name' => 'amprest',
                'app_user_password' => '#Amprest-1234!',
            ])
        );

        //  Seed rms mpesa credentials
        $minie->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $minie->id,
                'short_code' => '204440',
                'operating_short_code' => '204440',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'aWhxai3dsFDUf09YbWnlpo334F9DhxR3',
                'consumer_secret' => '6Xa1MMAGCMvSHQoi',
                'pass_key' => '157374172682982e8e44847210d710d03136b8d01f955ef94f0ef3f614d51657',
                'app_user_name' => 'amprest',
                'app_user_password' => '#Amprest-1234!',
            ])
        );

        $masomo->senderId()->save(
            SenderID::create([
                'project_id' => $transact->id,
                'code' => 'KNDSCHOOL',
            ])
        );

        // Seed the subscriptions.
        $transact->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $transact->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $transact->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        // Seed the subscriptions.
        $rms->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $rms->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $rms->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        // Seed the subscriptions.
        $minie->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $minie->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $minie->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        $masomo->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $masomo->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $masomo->id,
                    'tier_id' => 5,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $masomo->id,
                'tier_id' => 1,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ]),
            Subscription::create([
                'project_id' => $masomo->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        // Seed the M-Pesa credentials.
        $saimun->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $saimun->id,
                'short_code' => '657531',
                'operating_short_code' => '657531',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'DU3ZARknazFuQFE6O0ksm9U650QARTQO',
                'consumer_secret' => 'KfMpbohT8of2ElBC',
                'pass_key' => '74859e2c9ed8182acadbc2b6786a12e3ba0e1a8a7522d9cf6433ad130d29a402',
                'app_user_name' => 'AmprestAPI',
                'app_user_password' => '#Amprest-1234',
            ])
        );

        // Seed the subscriptions.
        $saimun->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $saimun->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $saimun->id,
                'tier_id' => 1,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addYear()
            ])
        );

        // Seed the M-Pesa credentials.
        $cakeUniverse->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $cakeUniverse->id,
                'short_code' => '950112',
                'operating_short_code' => '929074',
                'short_code_type' => 'buy_goods',
                'consumer_key' => 'ogbDR2Ez6d0ewFkiGeFH2fRdA6oWf826',
                'consumer_secret' => 'RMftl00KOFTAs098',
                'pass_key' => '032b52504854cd5f21429fff627dd23d4bb5e692b125d4f3ffe83b8aa6d8a63b',
                'app_user_name' => 'AmprestAPI',
                'app_user_password' => '#Amprest-1234',
            ])
        );

        // Seed the subscriptions.
        $cakeUniverse->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $cakeUniverse->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $cakeUniverse->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );
    }
}
