<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $primary_accounts = collect([]);
            $sub_accounts = collect([]);

            // Index corporate customer accounts and sub accounts
            $file = fopen('database/seeders/data/multiaccount.csv', 'r');
            while (($line = fgetcsv($file)) !== FALSE) {
                $super_account = (string)$line[0];
                $sub_account = (string)$line[1];
                // Push to sub account index
                $sub_accounts->push($sub_account);

                // Put or push to primary account relation index
                if ($primary_accounts->has($super_account)) {
                    $primary_accounts->get($super_account)->push($sub_account);
                } else {
                    $primary_accounts->put($super_account, collect([$sub_account]));
                }
            }

            // Create customer accounts 
            $file = fopen('database/seeders/data/customer.csv', 'r');
            while (($line = fgetcsv($file)) !== FALSE) {
                if ((bool)$line[28]) {
                    continue;
                }

                // Create a user
                $user = User::firstOrCreate(
                    [
                        'email' => $line[12]
                    ],
                    [
                        'password' => $line[13],
                        'first_name' => $line[10],
                        'last_name' => $line[11],
                        'department' => $line[33],
                        'field_of_work' => $line[34],
                        'job_title' => $line[35],
                        'cell_type_interests' => json_encode(explode(',', $line[36])),
                        'profile_picture' => '',
                        'company_name' => $line[7],
                        'prestashop_id' => $line[0]
                    ]
                );

                // Update Created and Updated at
                $user->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $line[29]);
                $user->updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $line[30]);

                // Email not verified
                if ((bool)$line[26]) {
                    $user->email_verified_at = Carbon::createFromFormat('Y-m-d H:i:s', $line[29]);
                }

                // Update T&C flag
                $user->has_accepted_terms = (bool)$line[37];
                $user->save();

                if ($primary_accounts->has($user->prestashop_id)) {
                    Customer::firstOrCreate(
                        [
                            'prestashop_id' => $user->prestashop_id
                        ],
                        [
                            'name' => $user->company_name,
                            'customer_type' => 'corporate'
                        ]
                    );
                    $user->assignRole('corporate-customer');
                } else if (!$sub_accounts->contains($user->prestashop_id)) {
                    Customer::firstOrCreate(
                        [
                            'prestashop_id' => $user->prestashop_id
                        ],
                        [
                            'name' => $user->first_name . " " . $user->last_name,
                            'customer_type' => 'individual'
                        ]
                    );
                    $user->assignRole('individual-customer');
                }
            }

            // Set sub users customer id (Set Later to prevent insertion order issues)
            foreach ($primary_accounts as $primary_account_id => $sub_user_ids) {
                // Make sure half Deleted Users don't mess the script
                $primary_user = User::where('prestashop_id', $primary_account_id)->first();
                if (!is_null($primary_user)) {
                    // Make sure half Deleted Users don't mess the script
                    $customer = Customer::where('prestashop_id', $primary_account_id)->first();
                    if (!is_null($customer)) {
                        foreach ($sub_user_ids as $sub_user_id) {
                            $user = User::where('prestashop_id', $sub_user_id)->first();
                            if(!is_null($user)){
                                $user->customer_id = $customer->id;
                                $user->save();
                                $user->assignRole('corporate-customer');
                            }
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            echo "[Error 500]: Seeder Failed on Line " . $th->getLine() . "\nWith message " . $th->getMessage() . "\n";
        }
    }
}
