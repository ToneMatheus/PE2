<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AddressSeeder::class,
            Employee\EmployeeProfileSeeder::class,
            UserSeeder::class,
            RoleSeeder::class,
            UserRoleSeeder::class,
            Employee\EmployeeContractSeeder::class,
            Employee\PayslipSeeder::class,
            Employee\HolidayTypeSeeder::class,
            Employee\BalanceSeeder::class,
            Employee\HolidaySeeder::class,

            Customer\CustomerContractSeeder::class,
            Customer\CustomerAddressSeeder::class,

            Meter\MeterSeeder::class,
            Meter\MeterAddressSeeder::class,
            Meter\MeterReaderScheduleSeeder::class,

            Invoice\EstimationSeeder::class,
            Invoice\TariffSeeder::class,
            Invoice\ProductSeeder::class,
            Invoice\ProductTariffSeeder::class,
            Invoice\ContractProductSeeder::class,
            Invoice\DiscountSeeder::class,

            Meter\IndexValueSeeder::class,
            Meter\ConsumptionSeeder::class,

            Invoice\InvoiceSeeder::class,
            Invoice\InvoiceLineSeeder::class,
        ]);
    }
}
