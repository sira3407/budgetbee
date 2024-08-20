<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AccountTypesSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(RuleConditionTypesSeeder::class);
        $this->call(RuleActionTypesSeeder::class);
    }
}
