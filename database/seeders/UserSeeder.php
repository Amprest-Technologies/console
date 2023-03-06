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

        // Seed a project.
        $amprestProject = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Amprest Official Project',
            'description' => 'Amprest Technologies Official Project',
            'pay_transaction_callback' => 'https://botman.amprest.co.ke/api/botman/mpesa/confirm',
            'api_key' => '4af06203465217ea0b27038c18cdb4d2'
        ]);

        // Seed a project.
        $masomo = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Masomo by Amprest',
            'description' => 'Amprest Technologies Masomo Education Platform',
            'uuid' => '48236419',
            'api_key' => '329ac8c2a9901864f9bab2c4603de9ba',
        ]);

        // Seed a project.
        $saimun = Project::create([
            'team_id' => $amprest->id,
            'name' => 'SAIMUN Registration',
            'description' => 'Sub-Saharan International Model United Nations Registration Platform',
            'uuid' => '31980374',
            'api_key' => '29210a4bcc93d3c1c4a7e316c04f1465',
        ]);

        // Seed a project.
        $cakeUniverse = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Cake Universe KE',
            'description' => 'Cake Universe Cake Resellers Kenya'
        ]);

        // Seed a project.
        $rundaGardens = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Runda Gardens Residents Estate Association',
            'description' => 'Residents Management Project',
            'pay_transaction_callback' => 'https://nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000003',
            'api_key' => 'c0ef8bc5a007f00a9c534440ab1caa59',
        ]);

        // Seed a project.
        $enoque = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Wambuafrikan Global',
            'description' => 'Management of the wambuafrikan brand',
            'pay_transaction_callback' => 'https://ticktes.wambuafrikan.co.ke/api/v1/mpesa/callback',
            'uuid' => '10000004',
            'api_key' => 'e28d77fe328fd26f9f1d7afe6d4e2d47',
        ]);

        // Seed a project.
        $nyumbani = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Nyumbani Tech Solutions',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://demo.nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000005',
            'api_key' => '02c5f213616f43615832027b69f4156d',
        ]);

        // Seed a project.
        $bestcare = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Bestcare Property Consultant Ltd',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000006',
            'api_key' => '45ec72179026023d8ed0d6202f081f83',
        ]);

        //  Seed a project
        $augustino = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Augustino Ltd',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000007',
            'api_key' => '834adb5c1446f05ff4434757e1618f18',
        ]);

        //  Seed a project
        $centralClose = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Central Close Residents Association',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000008',
            'api_key' => 'cd4269811d52c5534f267aab6b5eb5a5',
        ]);

        //  Seed a project
        $muthaigaParkside = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Muthaiga North Parkside Residents Association',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000009',
            'api_key' => 'fe32b6a3b5f1734d001420927e339eec',
        ]);

        //  Seed a project
        $kihuwan = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Kihuwan Investements Limited',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000010',
            'api_key' => '0778696ea7ce437a2bdadb3966754093',
        ]);

        //  Seed a project
        $warira = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Warira Court Management Limited',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000011',
            'api_key' => '8566d7f8898d20a39b7da8b5545bc6c8',
        ]);

        //  Seed a project
        $waburugu1 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Waburugu Enterprises',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000012',
            'api_key' => '2d3ae33c0072a28a1c01fe7666677a08',
        ]);

        //  Seed a project
        $waburugu2 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Waburugu Enterprises',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000013',
            'api_key' => 'c3ba9c80e35f7fcf6985a0b4f2a5562e',
        ]);

        //  Seed a project
        $royalHostels = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Royal Ladies Hostels Limited',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://nyumbanitech.co.ke/api/v1/mpesa/transactions',
            'uuid' => '10000014',
            'api_key' => '970f12bfa2cf2e1080ea467fadfc45b4',
        ]);

        // Seed the M-Pesa credentials.
        $amprestProject->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $amprestProject->id,
                'short_code' => '204440',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'aWhxai3dsFDUf09YbWnlpo334F9DhxR3',
                'consumer_secret' => '6Xa1MMAGCMvSHQoi',
                'pass_key' => '157374172682982e8e44847210d710d03136b8d01f955ef94f0ef3f614d51657',
                'app_user_name' => 'amprest',
                'app_user_password' => '#Amprest-1234!',
            ])
        );

        // Seed the M-Pesa credentials.
        $cakeUniverse->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $cakeUniverse->id,
                'short_code' => '950112',
                'short_code_type' => 'buy_goods',
                'consumer_key' => 'ogbDR2Ez6d0ewFkiGeFH2fRdA6oWf826',
                'consumer_secret' => 'RMftl00KOFTAs098',
                'pass_key' => '032b52504854cd5f21429fff627dd23d4bb5e692b125d4f3ffe83b8aa6d8a63b',
                'app_user_name' => 'AmprestAPI',
                'app_user_password' => '#Amprest-1234',
            ])
        );

        // Seed the M-Pesa credentials.
        $saimun->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $saimun->id,
                'short_code' => '657531',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'DU3ZARknazFuQFE6O0ksm9U650QARTQO',
                'consumer_secret' => 'KfMpbohT8of2ElBC',
                'pass_key' => '74859e2c9ed8182acadbc2b6786a12e3ba0e1a8a7522d9cf6433ad130d29a402',
                'app_user_name' => 'AmprestAPI',
                'app_user_password' => '#Amprest-1234',
            ])
        );

        // Seed the M-Pesa credentials.
        $rundaGardens->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $rundaGardens->id,
                'short_code' => '4076235',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'vnJbsrtt0PD97FvpOLMA5mJljsLWpRgD',
                'consumer_secret' => 'N2SZqnlKHN3GCxlQ',
                'pass_key' => '74859e2c9ed8182acadbc2b6786a12e3ba0e1a8a7522d9cf6433ad130d29a402',
                'app_user_name' => 'rgeaapi',
                'app_user_password' => '#RGEAAPI1234',
                'balance_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/4076235/balance',
            ])
        );

        // Seed the M-Pesa credentials.
        $enoque->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $enoque->id,
                'short_code' => '4077909',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'yu6CIfVLQFpBeyRuELUSSZk3q0OQoXDe',
                'consumer_secret' => 'v8fvK67lRGgGXGWo',
                'pass_key' => '0cb85be2d3f47bf63975bcd800bdf04f04c04e5af95e67d4a29c19c173f5f670',
                'app_user_name' => 'geekaburu',
                'app_user_password' => '',
            ])
        );

        // Seed the M-Pesa credentials.
        $nyumbani->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $nyumbani->id,
                'short_code' => '4077493',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'JE4DeZ8w1RBAN5Tr6EPyL6e5LGiwGVqv',
                'consumer_secret' => 'nMA99NqXjxi4mWkX',
                'pass_key' => '7920e6cd06da5721e7472e335843c3287cdcf585d780957d94f14c03b7e7fd1b',
                'app_user_name' => 'nyumbaniapi',
                'app_user_password' => '#NYUMBANIAPI1234',
                'balance_callback' => 'https://demo.nyumbanitech.co.ke/api/v1/mpesa/4077493/balance',
            ])
        );

        // Seed the M-Pesa credentials.
        $bestcare->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $bestcare->id,
                'short_code' => '4078251',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'cX9KKMIdjCoMDoT4sJSlm5sYgfPG1JLA',
                'consumer_secret' => 'ZVW8bMxFbC93ARAl',
                'pass_key' => '7920e6cd06da5721e7472e335843c3287cdcf585d780957d94f14c03b7e7fd1b',
                'app_user_name' => 'BestcareAPI',
                'app_user_password' => 'SDHSHDS3747434!!',
                'balance_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/4078251/balance',
            ])
        );

        // Seed the M-Pesa credentials.
        $augustino->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $augustino->id,
                'short_code' => '4085035',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'TGgfx1XA1A78Nm1rNR8A1mFjSruP5OAU',
                'consumer_secret' => 'bS1hOF9qMZGOY6r5',
                'pass_key' => '1b380772934a647e93bb6c5df7c0796a3ebb25d9c9300baafc3a9b398f569923',
                'app_user_name' => 'AUGUSTINOAPI',
                'app_user_password' => 'AUGUSTINO@2022',
                'balance_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/4085035/balance',
            ])
        );

        // Seed the M-Pesa credentials.
        $centralClose->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $centralClose->id,
                'short_code' => '4084931',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'AqcPv5YH2aldGcGq9xCxAoBCYnsWj5db',
                'consumer_secret' => '6DWckDvM29W5bo73',
                'pass_key' => 'a113e3c8c11e218e45a7cba9d82ec3a7e84596516fcddbe996726191a7927083',
                'app_user_name' => 'CENTRALCLOSEAPI',
                'app_user_password' => 'CENTRALCLOSE@2022',
                'balance_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/4084931/balance',
            ])
        );

        // Seed the M-Pesa credentials.
        $muthaigaParkside->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $muthaigaParkside->id,
                'short_code' => '4085961',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'wPEE6mM4DXHSAA8Vrvgzsc1BfFMoEBed',
                'consumer_secret' => 'TlB2AF6YsFPMQase',
                'pass_key' => '6ac97e164a863c240ee11ba3a9d0266f377c1a6485197f87315a01fac6eb6f87',
                'app_user_name' => 'PARKSIDEAPI',
                'app_user_password' => 'Parkside@2022',
                'balance_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/4085961/balance',
            ])
        );

        // Seed the M-Pesa credentials.
        $kihuwan->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $kihuwan->id,
                'short_code' => '597097',
                'short_code_type' => 'pay_bill',
                'consumer_key' => '6PONQqO7OzoCzI0OLHgw2FORv2qJkhMY',
                'consumer_secret' => 'DqAUBgYKgJuqJ6cN',
                'pass_key' => '8b0bc3ca70d7325a563f945c1e3e8985eda7069ac6fcd6c0a6e5078ca6841738',
                'app_user_name' => 'NyumbaniKihuwan',
                'app_user_password' => 'TechSoln@2022!',
                'balance_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/597097/balance',
            ])
        );

        // Seed the M-Pesa credentials.
        $warira->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $warira->id,
                'short_code' => '4106603',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'tF8IfchzUv2s4hTM9uZOPnjIRpBBeqpE',
                'consumer_secret' => 'qjW7ift6B9Nlr2yX',
                'pass_key' => '22532e4db366f53c3c6d80a667e61ee6c1b2c307fde0056825f9598922a5f1a8',
                'app_user_name' => 'WARIRAAPI',
                'app_user_password' => 'Warira@2022',
                'balance_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/4106603/balance',
            ])
        );

        // Seed the M-Pesa credentials.
        $waburugu1->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $waburugu1->id,
                'short_code' => '4068455',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'dtcBynS4fZwuMtWPnrMtH3Y5QPY0uVGa',
                'consumer_secret' => 'tUj0amGTD04ZY6kb',
                'pass_key' => '09e8d7f755d95308dc61ffd0063d6bdd0680bce7ee34348489558ac2c75a0a35',
                'app_user_name' => 'NYUMBANIAPI',
                'app_user_password' => '#Nyumbaniapi@2022',
                'balance_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/4068455/balance',
            ])
        );

        // Seed the M-Pesa credentials.
        $waburugu2->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $waburugu2->id,
                'short_code' => '4068461',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'LwAfYONAb1uL4GU3xiIxCfrz8oGBXwEX',
                'consumer_secret' => '9gDHnPHPlQisgHUL',
                'pass_key' => '4ed2d73c720aa01a3bce326b928cf1f6b6c1c442526e02600ba3a897e3a0db0d',
                'app_user_name' => 'NYUMBANIAPI',
                'app_user_password' => '#Nyumbaniapi@2022',
                'balance_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/4068461/balance',
            ])
        );

        // Seed the M-Pesa credentials.
        $royalHostels->mpesaCredentials()->save(
            MPesaCredentials::create([
                'project_id' => $royalHostels->id,
                'short_code' => '4108279',
                'short_code_type' => 'pay_bill',
                'consumer_key' => 'IOkKtLfBXcrOJqr3Wq7DPKsp8ALF4lsp',
                'consumer_secret' => 'elMMeb8PtEoNFTCf',
                'pass_key' => '56e0357839a71eb2870c5e11dc7425cea678625a218f2e682a20b26d35af1f86',
                'app_user_name' => 'NYUMBANIAPI',
                'app_user_password' => '#NyumbaniAPI@2022',
                'balance_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/4108279/balance',
            ])
        );

        //  Seed a sender ID
        $masomo->senderId()->save(
            SenderID::create([
                'project_id' => $masomo->id,
                'code' => 'KNDSCHOOL',
            ])
        );

        // Seed the subscriptions.
        $amprestProject->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $amprestProject->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ])
        );

        // Seed the subscriptions.
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

        // Seed the subscriptions.
        $rundaGardens->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $rundaGardens->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $rundaGardens->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        // Seed the subscriptions.
        $enoque->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $enoque->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $enoque->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        // Seed the subscriptions.
        $nyumbani->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $nyumbani->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $nyumbani->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        // Seed the subscriptions.
        $bestcare->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $bestcare->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $bestcare->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        // Seed the subscriptions.
        $augustino->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $augustino->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $augustino->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        // Seed the subscriptions.
        $centralClose->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $centralClose->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $centralClose->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        // Seed the subscriptions.
        $muthaigaParkside->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $muthaigaParkside->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $muthaigaParkside->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );

        // Seed the subscriptions.
        $kihuwan->subscriptions()->saveMany(
            Subscription::factory()
                ->times(mt_rand(3, 5))
                ->create([
                    'project_id' => $kihuwan->id,
                    'tier_id' => 1,
                    'usage_limit' => null,
                    'amount' => null,
                ]),
            Subscription::create([
                'project_id' => $kihuwan->id,
                'tier_id' => 5,
                'usage_limit' => null,
                'amount' => null,
                'expires_at' => Carbon::now()->addMonth()
            ])
        );
    }
}
