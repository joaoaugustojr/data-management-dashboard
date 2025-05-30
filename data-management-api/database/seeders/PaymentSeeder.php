<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory(30)->create();

        foreach ($users as $user) {
            Payment::factory()->create([
                'user_id' => $user->id
            ]);
        }
    }
}
