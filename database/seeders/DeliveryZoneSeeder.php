<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\DeliveryZone;

class DeliveryZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            'Abia',
            'Adamawa',
            'Akwa Ibom',
            'Anambra',
            'Bauchi',
            'Bayelsa',
            'Benue',
            'Borno',
            'Cross River',
            'Delta',
            'Ebonyi',
            'Edo',
            'Ekiti',
            'Enugu',
            'FCT - Abuja',
            'Gombe',
            'Imo',
            'Jagawa',
            'Kaduna',
            'Kano',
            'Katsina',
            'Kebbi',
            'Kogi',
            'Kwara',
            'Lagos',
            'Nasarawa',
            'Niger',
            'Ogun',
            'Ondo',
            'Osun',
            'Oyo',
            'Plateau',
            'Rivers',
            'Sokoto',
            'Taraba',
            'Yobe',
            'Zamfara'
        ];

        foreach ($states as $state) {
            DeliveryZone::updateOrCreate(
                ['name' => $state],

                ['delivery_fee' => 2500, 'is_active' => true, 'local_park_fee' => 1500, 'local_park_instructions' => 'You will be called at the pack to negotiate fee with the driver, the amount charged is what it cost to take the product to the pack for you ']
            );
        }
    }
}
