<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\MPesaCredentials;
use App\Models\Project;
use App\Models\Subscription;
use App\Models\Team;
use App\Models\User;
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
            'pay_transaction_callback' => 'https://wagtickets.co.ke/api/v1/mpesa/callback',
            'api_key' => '4af06203465217ea0b27038c18cdb4d2'
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
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000003',
            'api_key' => 'c0ef8bc5a007f00a9c534440ab1caa59',
        ]);

        // Seed a project.
        $wambuAfrikan = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Wambuafrikan Global',
            'description' => 'Management of the wambuafrikan brand',
            'pay_transaction_callback' => 'https://payments.wambuafrikan.co.ke/api/v1/transactions',
            'uuid' => '10000004',
            'api_key' => 'e28d77fe328fd26f9f1d7afe6d4e2d47',
        ]);

        // Seed a project.
        $nyumbani = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Nyumbani Tech Solutions',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://demo.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000005',
            'api_key' => '02c5f213616f43615832027b69f4156d',
        ]);

        // Seed a project.
        $bestcare = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Bestcare Property Consultant Ltd',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000006',
            'api_key' => '45ec72179026023d8ed0d6202f081f83',
        ]);

        //  Seed a project
        $augustino = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Augustino Ltd',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000007',
            'api_key' => '834adb5c1446f05ff4434757e1618f18',
        ]);

        //  Seed a project
        $centralClose = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Central Close Residents Association',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000008',
            'api_key' => 'cd4269811d52c5534f267aab6b5eb5a5',
        ]);

        //  Seed a project
        $muthaigaParkside = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Muthaiga North Parkside Residents Association',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000009',
            'api_key' => 'fe32b6a3b5f1734d001420927e339eec',
        ]);

        //  Seed a project
        $kihuwan = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Kihuwan Investements Limited',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000010',
            'api_key' => '0778696ea7ce437a2bdadb3966754093',
        ]);

        //  Seed a project
        $warira = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Warira Court Management Limited',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000011',
            'api_key' => '8566d7f8898d20a39b7da8b5545bc6c8',
        ]);

        //  Seed a project
        $waburugu1 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Waburugu Enterprises',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000012',
            'api_key' => '2d3ae33c0072a28a1c01fe7666677a08',
        ]);

        //  Seed a project
        $waburugu2 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Waburugu Enterprises',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000013',
            'api_key' => 'c3ba9c80e35f7fcf6985a0b4f2a5562e',
        ]);

        //  Seed a project
        $royalHostels = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Royal Ladies Hostels Limited',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000014',
            'api_key' => '970f12bfa2cf2e1080ea467fadfc45b4',
        ]);

        //  Seed a project.
        $nyumbani2 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Nyumbani Tech Solutions',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000015',
            'api_key' => '8ddc36dae71ce7ee1c2eee4ff2a5eb38',
        ]);

        //  Seed a project.
        $uniqueHomes1 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Unique Homes Management LTD',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000016',
            'api_key' => 'f108f1d44edcbc5fb3a67a6cd1b11de2',
        ]);

        //  Seed a project.
        $uniqueHomes2 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Unique Homes Management LTD',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000017',
            'api_key' => 'ef5c9bbe8e8c0006f9f7b7f293386b3f',
        ]);

        //  Seed a project.
        $balozi = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Muthaiga North Gardens Balozi Association',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000018',
            'api_key' => '09f3e26071683115adca841674895248',
        ]);

        //  Seed a project.
        $townview = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Townview Hostels',
            'description' => 'Management of residents and tenants',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000019',
            'api_key' => '139dca372febef5a28fed052e9e4196d',
        ]);

        //  Seed a project.
        $nyumbaniBoysClub = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Nyumbani - Boys Club',
            'description' => 'Management of chama collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000020',
            'api_key' => '3b78324a2e617023cbbc04d6e4f8cbcf',
        ]);

        //  Seed a project.
        $staroot1 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Staroot Residency Management Limited',
            'description' => 'Management of resident collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000021',
            'api_key' => '7fe0488c3a93c8d657656c0199d146c0',
        ]);

        //  Seed a project.
        $staroot2 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Staroot Residency Management Limited',
            'description' => 'Management of resident collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000022',
            'api_key' => '1228ae7be41938a41f4221ca0c670b44',
        ]);

        //  Seed a project.
        $mobiletaka = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Mobile Taka Solutions',
            'description' => 'Management of resident collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000023',
            'api_key' => '8c9544435e5b94186cfb085dcd2524b8',
        ]);

        //  Seed a project.
        $goldPark = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Goldpark Property Management Limited',
            'description' => 'Management of resident collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000024',
            'api_key' => '201155ca8cf43ef1ad316396eeceefbb',
        ]);

        //  Seed a project.
        $goldPark2 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Goldpark Property Management Limited',
            'description' => 'Management of resident collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000028',
            'api_key' => '9b3db1d25cfddc7855beff88c2807946',
        ]);

        //  Seed a project.
        $forhome1 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Forhome Court Management PLC',
            'description' => 'Management of resident collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000025',
            'api_key' => '4ecd88d57f3482922cc0a47d99cba8df',
        ]);

        //  Seed a project.
        $forhome2 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Forhome Court Management PLC',
            'description' => 'Management of resident collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000026',
            'api_key' => 'fa793dc8dfb98f77228998b04ce80ec8',
        ]);

        //  Seed a project.
        $whiteApartments = Project::create([
            'team_id' => $amprest->id,
            'name' => 'White Apartments',
            'description' => 'Management of resident collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000027',
            'api_key' => '354689719058f71527a5eb7029f51c26',
        ]);

        //  Seed a project.
        $kaisa1 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Kaisa Garden Management PLC',
            'description' => 'Management of resident collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000028',
            'api_key' => '3b9030066c61dfa51d09eaa8e9cc3c1e',
        ]);

        //  Seed a project.
        $kaisa2 = Project::create([
            'team_id' => $amprest->id,
            'name' => 'Kaisa Garden Management PLC',
            'description' => 'Management of resident collections',
            'pay_transaction_callback' => 'https://app.nyumbani.ke/api/v1/mpesa/transactions',
            'uuid' => '10000029',
            'api_key' => 'f9d49d06113079255c8568a7fc5fd912',
        ]);

        //  Seed the M-Pesa credentials.
        $amprestProject->mpesaCredentials()->create([
            'short_code' => $shortCode = '204440',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'aWhxai3dsFDUf09YbWnlpo334F9DhxR3',
            'consumer_secret' => '6Xa1MMAGCMvSHQoi',
            'pass_key' => '157374172682982e8e44847210d710d03136b8d01f955ef94f0ef3f614d51657',
            'app_user_name' => 'amprest',
            'app_user_password' => '#Amprest-1234!',
        ]);

        // Seed the M-Pesa credentials.
        $cakeUniverse->mpesaCredentials()->create([
            'short_code' => $shortCode = '950112',
            'short_code_type' => 'buy_goods',
            'consumer_key' => 'ogbDR2Ez6d0ewFkiGeFH2fRdA6oWf826',
            'consumer_secret' => 'RMftl00KOFTAs098',
            'pass_key' => '032b52504854cd5f21429fff627dd23d4bb5e692b125d4f3ffe83b8aa6d8a63b',
            'app_user_name' => 'AmprestAPI',
            'app_user_password' => '#Amprest-1234',
        ]);

        // Seed the M-Pesa credentials.
        $saimun->mpesaCredentials()->create([
            'short_code' => $shortCode = '657531',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'DU3ZARknazFuQFE6O0ksm9U650QARTQO',
            'consumer_secret' => 'KfMpbohT8of2ElBC',
            'pass_key' => '74859e2c9ed8182acadbc2b6786a12e3ba0e1a8a7522d9cf6433ad130d29a402',
            'app_user_name' => 'AmprestAPI',
            'app_user_password' => '#Amprest-1234',
        ]);

        // Seed the M-Pesa credentials.
        $rundaGardens->mpesaCredentials()->create([
            'short_code' => $shortCode = '4076235',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'vnJbsrtt0PD97FvpOLMA5mJljsLWpRgD',
            'consumer_secret' => 'N2SZqnlKHN3GCxlQ',
            'pass_key' => '74859e2c9ed8182acadbc2b6786a12e3ba0e1a8a7522d9cf6433ad130d29a402',
            'app_user_name' => 'rgeaapi',
            'app_user_password' => '#RGEAAPI1234',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $wambuAfrikan->mpesaCredentials()->create([
            'short_code' => $shortCode = '4077909',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'yu6CIfVLQFpBeyRuELUSSZk3q0OQoXDe',
            'consumer_secret' => 'v8fvK67lRGgGXGWo',
            'pass_key' => '0cb85be2d3f47bf63975bcd800bdf04f04c04e5af95e67d4a29c19c173f5f670',
            'app_user_name' => 'geekaburu',
            'app_user_password' => '',
        ]);

        // Seed the M-Pesa credentials.
        $nyumbani->mpesaCredentials()->create([
            'short_code' => $shortCode = '4077493',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'JE4DeZ8w1RBAN5Tr6EPyL6e5LGiwGVqv',
            'consumer_secret' => 'nMA99NqXjxi4mWkX',
            'pass_key' => '7920e6cd06da5721e7472e335843c3287cdcf585d780957d94f14c03b7e7fd1b',
            'app_user_name' => 'nyumbaniapi',
            'app_user_password' => '#NYUMBANIAPI1234',
            'balance_callback' => "https://demo.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $nyumbani2->mpesaCredentials()->create([
            'short_code' => $shortCode = '4095211',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'yGFqAcKDTNc7CdOmKyGc6jBO7Fud8pP9',
            'consumer_secret' => 'lFU09OdMPNlXOzZn',
            'pass_key' => '37fdf7ebb042ed54911a7aacb7ccd04687d2d2b6ef6cb9ee7a1e3e0da2f0c536',
            'app_user_name' => 'NYUMBANIAPI',
            'app_user_password' => '#Nyumbaniapi@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $bestcare->mpesaCredentials()->create([
            'short_code' => $shortCode = '4078251',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'cX9KKMIdjCoMDoT4sJSlm5sYgfPG1JLA',
            'consumer_secret' => 'ZVW8bMxFbC93ARAl',
            'pass_key' => '7920e6cd06da5721e7472e335843c3287cdcf585d780957d94f14c03b7e7fd1b',
            'app_user_name' => 'BestcareAPI',
            'app_user_password' => 'SDHSHDS3747434!!',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $augustino->mpesaCredentials()->create([
            'short_code' => $shortCode = '4085035',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'TGgfx1XA1A78Nm1rNR8A1mFjSruP5OAU',
            'consumer_secret' => 'bS1hOF9qMZGOY6r5',
            'pass_key' => '1b380772934a647e93bb6c5df7c0796a3ebb25d9c9300baafc3a9b398f569923',
            'app_user_name' => 'AUGUSTINOAPI',
            'app_user_password' => 'AUGUSTINO@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $centralClose->mpesaCredentials()->create([
            'short_code' => $shortCode = '4084931',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'AqcPv5YH2aldGcGq9xCxAoBCYnsWj5db',
            'consumer_secret' => '6DWckDvM29W5bo73',
            'pass_key' => 'a113e3c8c11e218e45a7cba9d82ec3a7e84596516fcddbe996726191a7927083',
            'app_user_name' => 'CENTRALCLOSEAPI',
            'app_user_password' => 'CENTRALCLOSE@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $muthaigaParkside->mpesaCredentials()->create([
            'short_code' => $shortCode = '4085961',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'wPEE6mM4DXHSAA8Vrvgzsc1BfFMoEBed',
            'consumer_secret' => 'TlB2AF6YsFPMQase',
            'pass_key' => '6ac97e164a863c240ee11ba3a9d0266f377c1a6485197f87315a01fac6eb6f87',
            'app_user_name' => 'PARKSIDEAPI',
            'app_user_password' => 'Parkside@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $kihuwan->mpesaCredentials()->create([
            'short_code' => $shortCode = '597097',
            'short_code_type' => 'pay_bill',
            'consumer_key' => '6PONQqO7OzoCzI0OLHgw2FORv2qJkhMY',
            'consumer_secret' => 'DqAUBgYKgJuqJ6cN',
            'pass_key' => '8b0bc3ca70d7325a563f945c1e3e8985eda7069ac6fcd6c0a6e5078ca6841738',
            'app_user_name' => 'NyumbaniKihuwan',
            'app_user_password' => 'TechSoln@2022!',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $warira->mpesaCredentials()->create([
            'short_code' => $shortCode = '4106603',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'tF8IfchzUv2s4hTM9uZOPnjIRpBBeqpE',
            'consumer_secret' => 'qjW7ift6B9Nlr2yX',
            'pass_key' => '22532e4db366f53c3c6d80a667e61ee6c1b2c307fde0056825f9598922a5f1a8',
            'app_user_name' => 'WARIRAAPI',
            'app_user_password' => 'Warira@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $waburugu1->mpesaCredentials()->create([
            'short_code' => $shortCode = '4068455',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'dtcBynS4fZwuMtWPnrMtH3Y5QPY0uVGa',
            'consumer_secret' => 'tUj0amGTD04ZY6kb',
            'pass_key' => '09e8d7f755d95308dc61ffd0063d6bdd0680bce7ee34348489558ac2c75a0a35',
            'app_user_name' => 'NYUMBANIAPI',
            'app_user_password' => '#Nyumbaniapi@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $waburugu2->mpesaCredentials()->create([
            'short_code' => $shortCode = '4068461',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'LwAfYONAb1uL4GU3xiIxCfrz8oGBXwEX',
            'consumer_secret' => '9gDHnPHPlQisgHUL',
            'pass_key' => '4ed2d73c720aa01a3bce326b928cf1f6b6c1c442526e02600ba3a897e3a0db0d',
            'app_user_name' => 'NYUMBANIAPI',
            'app_user_password' => '#Nyumbaniapi@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $royalHostels->mpesaCredentials()->create([
            'short_code' => $shortCode = '4108279',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'IOkKtLfBXcrOJqr3Wq7DPKsp8ALF4lsp',
            'consumer_secret' => 'elMMeb8PtEoNFTCf',
            'pass_key' => '56e0357839a71eb2870c5e11dc7425cea678625a218f2e682a20b26d35af1f86',
            'app_user_name' => 'NYUMBANIAPI',
            'app_user_password' => '#NyumbaniAPI@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $uniqueHomes1->mpesaCredentials()->create([
            'short_code' => $shortCode = '4113687',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'Ql0mGCWHmZmPiZAQ1ONk4LOhxDDDyFgp',
            'consumer_secret' => 'pFJJ7mAAe8iHNLhU',
            'pass_key' => '0998207e85646929cfb274284df79a027cfba9b17bb27285d6551c3332b0213c',
            'app_user_name' => 'NYUMBANIAPI',
            'app_user_password' => '#Nyumbaniapi@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $uniqueHomes2->mpesaCredentials()->create([
            'short_code' => $shortCode = '4114037',
            'short_code_type' => 'pay_bill',
            'consumer_key' => '6kBfqHbwzhBPKjKVflLFmsnWA2qGdPqV',
            'consumer_secret' => 'csBHtKu4ROKvrTPW',
            'pass_key' => '8b1faf5e4662ba7ff330d05c5df3f411c04d27ddbd252508b0f64a342c23b9d9',
            'app_user_name' => 'NYUMBANIAPI',
            'app_user_password' => '#Nyumbaniapi@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $balozi->mpesaCredentials()->create([
            'short_code' => $shortCode = '4113815',
            'short_code_type' => 'pay_bill',
            'consumer_key' => '7KuMMopNZ8uJEOmtkHScKktq2PmFucJx',
            'consumer_secret' => '2DkyUcIbzcGVMc7U',
            'pass_key' => '9bcfacc4f9e6272e1b2e617eb148e45dad730ce43a3d649dd3633e6d37a1a5a7',
            'app_user_name' => 'BALOZIAPI',
            'app_user_password' => '#NyumbaniBalozi@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $townview->mpesaCredentials()->create([
            'short_code' => $shortCode = '4113689',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'PHPVBMVucSUhcFpG5T2WMoabXFW4TKAP',
            'consumer_secret' => '42pXE6SNcL28tO83',
            'pass_key' => '117beb4d061e2e6834402022217fa28c21aa94ff9cedb375fa8a54f68798594e',
            'app_user_name' => 'TOWNVIEWAPI',
            'app_user_password' => '#TownView@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the M-Pesa credentials.
        $nyumbaniBoysClub->mpesaCredentials()->create([
            'short_code' => $shortCode = '4119469',
            'short_code_type' => 'pay_bill',
            'consumer_key' => '22TCHKiJzQZzLemPA7rAjNuKPh43H7rI',
            'consumer_secret' => 'E4vMmWBFzSKvCmIi',
            'pass_key' => '117beb4d061e2e6834402022217fa28c21aa94ff9cedb375fa8a54f68798594e',
            'app_user_name' => 'BOYSCLUBAP1',
            'app_user_password' => '#BoysClub@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the Staroot credentials.
        $staroot1->mpesaCredentials()->create([
            'short_code' => $shortCode = '4121711',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'HShhbL5gNy3Uo0gHUEVOB1l3YX5QXUaq',
            'consumer_secret' => 'XFdBBPGNdjCu2bcF',
            'pass_key' => '7664ca400772faa35467e454ae596c4eb70071549faca7948ef0cb0279831a6d',
            'app_user_name' => 'STAROOTAPI',
            'app_user_password' => '#NyumbaniStaroot@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the Staroot credentials.
        $staroot2->mpesaCredentials()->create([
            'short_code' => $shortCode = '4121291',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'P4reQICzOs33ZlRpLmtPAsMf7YAzAFxh',
            'consumer_secret' => 'S88ZivseAZhiYnGl',
            'pass_key' => '65105bf206fbf159658b6d6358c91c79df896a9d23679a06d00254647eda6f16',
            'app_user_name' => 'STAROOTAPI',
            'app_user_password' => '#NyumbaniStaroot@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        // Seed the Mobile Take credentials.
        $mobiletaka->mpesaCredentials()->create([
            'short_code' => $shortCode = '4060083',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'XEVEGkO9E9tYdJWAaVkFAjbAI2s3syAz',
            'consumer_secret' => 'F6S1pB1NIXu6whep',
            'pass_key' => '7b26fdfd5e314400dac3907fd5027d4f37d6b52b2723e1b470df778d71a9e476',
            'app_user_name' => 'MTAKAAPI',
            'app_user_password' => '#NyumbaniMtaka@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        //  Seed the Gold Park credentials.
        $goldPark->mpesaCredentials()->create([
            'short_code' => $shortCode = '4014773',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'RI47VGfOfJqQl9NW1AkxiQGTq6o1rKr9',
            'consumer_secret' => 'brKlLyZ7tm4MnMyx',
            'pass_key' => 'd90923f82b4b493696840b222e1fc7ffbb8bb71a10ac7aaad065a473f97c8266',
            'app_user_name' => 'GOLDPARKAPI',
            'app_user_password' => '#NyumbaniGoldpark@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        //  Seed the Gold Park credentials.
        $goldPark2->mpesaCredentials()->create([
            'short_code' => $shortCode = '4107020',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'kGAM5wZKDtEB00dQdZxPw0AkkqgRhYeqb31lmHuIIrLNGLPB',
            'consumer_secret' => 'asQJgyLdoGtB4EAACYy6uTYxj3IFcdNERwuSHPgiGk8XR690iICG7apTI0Fa26p6',
            'pass_key' => '10ab89129bac90908e480e289a87d8f0191883a18b61ef7a0d32781086098b88',
            'app_user_name' => 'GOLDPARKAPI',
            'app_user_password' => '#NyumbaniGoldpark@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        //  Seed the Gold Park credentials.
        $forhome1->mpesaCredentials()->create([
            'short_code' => $shortCode = '4122037',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'CyvHWWOeOlIqftmRQMTdeLATE08kRRZs',
            'consumer_secret' => 'TCLbNFXyAKtUfI8n',
            'pass_key' => '86aa911fe13d76edecb4c7f064f9e24d8e02b450080dc8b06faac8151752c4e7',
            'app_user_name' => 'FORHOMESAPI',
            'app_user_password' => '#NyumbaniForhomes@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        //  Seed the Gold Park credentials.
        $forhome2->mpesaCredentials()->create([
            'short_code' => $shortCode = '4122195',
            'short_code_type' => 'pay_bill',
            'consumer_key' => '4kGzzsR7eCaAgE6I6eVxeGgp2mumBnlD',
            'consumer_secret' => 'eN7rS9Gjbbm7Z9oG',
            'pass_key' => 'dddc14dba6f7e500272e0141604609ba168092c59301b12f3426a7afa3460841',
            'app_user_name' => 'FORHOMESAPI',
            'app_user_password' => '#NyumbaniForhomes@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        //  Seed the Gold Park credentials.
        $whiteApartments->mpesaCredentials()->create([
            'short_code' => $shortCode = '4125143',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'pDa4FGFk1mvk8oGvTGQzRbHlaar3jyYy',
            'consumer_secret' => '9FOzDF3GKyPLJf5U',
            'pass_key' => '552c51e776d63bdd121cc43e4cef43a2d2174ccff839f3e82c4d8b7a85facd29',
            'app_user_name' => 'WHITEAPARTMENTSAPI',
            'app_user_password' => '#NyumbaniWhite@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        //  Seed the Gold Park credentials.
        $kaisa1->mpesaCredentials()->create([
            'short_code' => $shortCode = '4132063',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'xNWaz7jP97aC3lEQKJYBjGXKCpAUR2RWifftRATWdZRj4LZA',
            'consumer_secret' => 'Be5t2FXQQFA5ijqWzBgSExUZh3KjrvVs5SR3CIAxM9QKLGCEMGjj5woA2k3v8SCQ',
            'pass_key' => 'f8f3d86c005903796ca0d1fc652bc99950883a82c71c52b2166a83f10fd6f835',
            'app_user_name' => 'KAISAPI',
            'app_user_password' => '#NyumbaniKaisa@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);

        //  Seed the Gold Park credentials.
        $kaisa2->mpesaCredentials()->create([
            'short_code' => $shortCode = '401857',
            'short_code_type' => 'pay_bill',
            'consumer_key' => 'i5AMZBR3kG2AkWRXqjA2o5fU3U5AFFi48AMTNZEwIx19wXYA',
            'consumer_secret' => 'cT0CnIFMUn2hf4IORsG6iyCec5e2e1Gjs18v8U5nj8z0kjRaNPOqbWC4tSp0pEBt',
            'pass_key' => '552c51e776d63bdd121cc43e4cef43a2d2174ccff839f3e82c4d8b7a85facd29',
            'app_user_name' => 'KAISAPI',
            'app_user_password' => '#NyumbaniKaisa@2022',
            'balance_callback' => "https://app.nyumbani.ke/api/v1/mpesa/$shortCode/balance",
        ]);
    }
}
