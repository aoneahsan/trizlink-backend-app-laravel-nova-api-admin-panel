<?php

namespace Database\Seeders\ZLink\Plans;

use App\Models\Default\User;
use App\Models\ZLink\Plans\Plan;
use App\Zaions\Enums\PlansEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $ahsanUser = User::where('email', env('ADMIN_EMAIL'))->first();

        $plans = [
            [
                'uniqueId' => uniqid(),
                'userId' => $ahsanUser->id,
                'name' => PlansEnum::free->value,
                'displayName' => 'Free',
                'monthlyPrice' => 0,
                'annualPrice' => 0,
                'monthlyDiscountedPrice' => 0,
                'annualDiscountedPrice' => 0,
                'isMostPopular' => false,
                'description' => '2 QR Codes/month. 10 links/month. 1 Link-in-bio page',
                'currency' => '$',
                'featureListTitle' => 'Includes:',
                'features' => [
                    [
                        'text' => '5 custom back-halves',
                    ],
                    [
                        'text' => 'PNG & JPEG QR Code download formats',
                    ],
                    [
                        'text' => 'QR Code customizations',
                    ],
                ]
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $ahsanUser->id,
                'name' => PlansEnum::core->value,
                'displayName' => 'Core',
                'monthlyPrice' => 8,
                'isAnnualOnly' => true,
                'annualPrice' => 96,
                'monthlyDiscountedPrice' => 8,
                'annualDiscountedPrice' => 96,
                'isMostPopular' => true,
                'currency' => '$',
                'description' => '5 QR Codes/month. 100 links/month. 1 Link-in-bio page',
                'featureListTitle' => 'Everything in Free, plus:',
                'features' => [
                    [
                        'text' => '30 days of click & scan data',
                    ],
                    [
                        'text' => 'UTM Builder',
                    ],
                    [
                        'text' => 'Advanced QR Code customizations',
                    ],
                    [
                        'text' => 'Link & QR Code redirects',
                    ],
                ]
            ],
            [
                'uniqueId' => uniqid(),

                'userId' => $ahsanUser->id,
                'name' => PlansEnum::growth->value,
                'displayName' => 'Growth',
                'monthlyPrice' => 29,
                'annualPrice' => 348,
                'monthlyDiscountedPrice' => 29,
                'annualDiscountedPrice' => 348,
                'isMostPopular' => false,
                'currency' => '$',
                'description' => '10 QR Codes/month. 500 links/month. 2 Link-in-bio page',
                'featureListTitle' => 'Everything in Core, plus:',
                'features' => [
                    [
                        'text' => 'Complimentary custom domain*',
                    ],
                    [
                        'text' => 'Additional QR Code download formats',
                    ],
                    [
                        'text' => '4 months of click & scan data',
                    ],
                    [
                        'text' => 'Bulk link shortening',
                    ],
                ]
            ],
            [
                'uniqueId' => uniqid(),

                'userId' => $ahsanUser->id,
                'name' => PlansEnum::premium->value,
                'displayName' => 'Premium',
                'monthlyPrice' => 199,
                'annualPrice' => 2388,
                'monthlyDiscountedPrice' => 199,
                'annualDiscountedPrice' => 2388,
                'isMostPopular' => false,
                'description' => '200 QR Codes/month. 3,000 links/month. 5 Link-in-bio page',
                'currency' => '$',
                'featureListTitle' => 'Everything in Growth, plus:',
                'features' => [
                    [
                        'text' => '1 year of click & scan data',
                    ],
                    [
                        'text' => 'Custom campaign-level tracking',
                    ],
                    [
                        'text' => 'City-level & device type click & scan data',
                    ],
                    [
                        'text' => 'Mobile deep linking',
                    ],
                ]
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
