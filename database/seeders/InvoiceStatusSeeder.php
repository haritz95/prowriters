<?php

namespace Database\Seeders;

use App\Models\Accounting\InvoiceStatus;
use Illuminate\Database\Seeder;

class InvoiceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InvoiceStatus::insert([
            [
                'name'        => 'Unpaid',
                'description' => 'No payments have been made against them',
                'color'       => '#fff',
                'bg_color'    => '#ee2b8b',
            ],
            [
                'name'        => 'Paid',
                'description' => 'Invoice has been paid by the customer in full',
                'color'       => '#fff',
                'bg_color'    => '#b4bd00',
            ],
            [
                'name'        => 'Partial',
                'description' => 'If the balance is not paid in full but a partial payment has been received, it will be marked as partially paid.',
                'color'       => '#fff',
                'bg_color'    => '#fa7901',
            ],

            [
                'name'        => 'Overdue',
                'description' => 'When the full total balance due of an invoice has not been received by its due date, the invoice will be marked as Overdue.',
                'color'       => '#fff',
                'bg_color'    => '#ed1b24',
            ],
            [
                'name'        => 'Forwarded',
                'description' => 'An invoice will be marked as forward when any past due balances has been added to the new invoice.',
                'color'       => '#fff',
                'bg_color'    => '#00adee',
            ],

        ]);

    }
}
