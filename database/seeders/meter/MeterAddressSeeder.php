<?php

namespace Database\Seeders\Meter;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Meter_address;
use Carbon\Carbon;

class MeterAddressSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::now()->toDateString();
        for($i=3; $i <= 10; $i++){
            DB::table('meter_addresses')->insert([
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => $i,
                'meter_id' => $i-2
            ]);
        }

        DB::table('meter_addresses')->insert([
            [ /* renter 1-1 */
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => 12,
                'meter_id' => 9
            ],
            [ /* renter 1-2 */
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => 13,
                'meter_id' => 10
            ],
            [ /* renter 2-1 */
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => 15,
                'meter_id' => 11
            ],
            [ /* renter 2-2 */
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => 16,
                'meter_id' => 12
            ],
            [ /* old 1 */
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => 17,
                'meter_id' => 13
            ],
            [ /* old 2 */
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => 18,
                'meter_id' => 14
            ],
            [ /* lazy */
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => 19,
                'meter_id' => 15
            ],
            [ /* very lazy */
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => 20,
                'meter_id' => 16
            ],
            [ /* landlord personal */
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => 21,
                'meter_id' => 17
            ],
        ]);
    }
}
