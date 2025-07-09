<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\ShippingInfo;

class FixShippingInfoSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua order yang tidak memiliki shipping info
        $ordersWithoutShipping = Order::doesntHave('shippingInfo')->get();
        
        foreach ($ordersWithoutShipping as $order) {
            ShippingInfo::create([
                'order_id' => $order->id,
                'recipient_name' => $order->customer_name,
                'phone' => $order->customer_phone,
                'address' => 'Alamat belum diisi',
                'city' => 'Kota belum diisi',
                'province' => 'Provinsi belum diisi',
                'postal_code' => '12345',
                'shipping_method' => 'Standard',
                'tracking_number' => null,
                'shipping_cost' => 0
            ]);
        }
        
        $this->command->info('Fixed ' . $ordersWithoutShipping->count() . ' orders without shipping info.');
    }
}