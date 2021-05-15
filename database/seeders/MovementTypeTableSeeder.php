<?php

namespace Database\Seeders;

use App\Models\MovementType;
use Illuminate\Database\Seeder;

class MovementTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MovementType::create(['name' => 'Load box']);
        MovementType::create(['name' => 'Unload box']);
        MovementType::create(['name' => 'Payment made']);
        MovementType::create(['name' => 'Return of payment made']);
    }
}
