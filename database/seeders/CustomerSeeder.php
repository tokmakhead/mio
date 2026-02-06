<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // 5 bireysel müşteri
        Customer::factory()
            ->individual()
            ->active()
            ->count(5)
            ->create();

        // 5 kurumsal müşteri
        Customer::factory()
            ->corporate()
            ->active()
            ->count(5)
            ->create();
    }
}
