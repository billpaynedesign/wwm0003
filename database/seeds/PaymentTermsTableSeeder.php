<?php

use Illuminate\Database\Seeder;
use App\PaymentTerm;

class PaymentTermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\PaymentTerm::truncate();
    	DB::table('payment_terms')->insert([
            [
                'name' => 'Due on receipt',
                'days' => 0
            ],
            [
                'name' => 'Net 15',
                'days' => 15
            ],
            [
                'name' => 'Net 30',
                'days' => 30
            ],
            [
                'name' => 'Net 45',
                'days' => 45
            ],
            [
                'name' => 'Net 60',
                'days' => 60
            ],
            [
                'name' => 'Net 75',
                'days' => 75
            ],
            [
                'name' => 'Net 90',
                'days' => 90
            ]
        ]);
    }
}
