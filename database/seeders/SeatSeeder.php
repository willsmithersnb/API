<?php

namespace Database\Seeders;

use App\Models\NBAI\Seat;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where(function ($query) {
            $query->doesntHave('seat')->orWhereHas('seat', function ($query) {
                $query->whereNotIn('status', ['pending', 'suspended']);
            });
        })->where(function ($query) {
            $query->where('email', 'LIKE', '%@atlaslabs.com.au')->orWhere('email', 'LIKE', '%@atlaslabs.lk')->orWhere('email', 'LIKE', '%@nucleusbiologics.com');
        })->get();

        foreach ($users as $user) {
            $fields = [
                'status' => 'active',
                'payment_type' => 'N/A',
                'uuid' => Str::uuid(),
                'expires_at' => Carbon::now()->addYear()
            ];

            $seat = Seat::updateOrCreate([
                'user_id' => $user->id
            ], $fields);
        }
    }
}
