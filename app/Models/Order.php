<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = ['id'];

    /**
     * Mengambil detail lengkap pesanan berdasarkan ID
     */
    public static function getPaymentDetails($orderId)
    {
        return DB::table('orders')
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->where('orders.id', $orderId)
            ->select(
                'orders.id',
                'orders.order_code',
                'orders.grand_total',
                'orders.customer_name',
                'orders.customer_email',
                'orders.customer_phone',
                'events.title as event_title'
            )
            ->first();
    }
}
