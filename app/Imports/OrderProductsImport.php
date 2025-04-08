<?php

namespace App\Imports;

use App\Models\{
    OrderProduct,
    Order,
};
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class OrderProductsImport implements ToModel, WithHeadingRow
{
    private $orders;

    public function __construct()
    {
        $this->orders = Order::select('id','created_at')->get();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $order = $this->orders->where('id', $row['order_id'])->first();
        if ($order === NULL) {
            return;
        }
        return new OrderProduct([
            'name' => $row['name'],
            'purchase_price_excluding_tax' => $row['purchase_price_excluding_tax'] ?? 0,
            'purchase_price_including_tax' => $row['purchase_price_including_tax'] ?? 0,
            'price_customer_excluding_tax' => $row['price_customer_excluding_tax'] ?? 0,
            'price_customer_including_tax' => $row['price_customer_including_tax'] ?? 0,
            'amount' => $row['amount'] ?? 0,
            'revenue' => $row['revenue'] ?? 0,
            'profit' => $row['profit'] ?? 0,
            'order_id' => $order->id ?? NULL,
            'updated_at' => $order->created_at,
            'created_at' => $order->created_at,
            'tax_percentage' => $row['tax_percentage'] ?? 0,
            'total_price_customer_including_tax' => $row['price_customer_including_tax'],
            'total_purchase_price_excluding_tax' => ($row['purchase_price_excluding_tax'] ?? 0) * ($row['amount'] ?? 0),
        ]);
    }

    public function batchSize(): int
    {
        return 250;
    }

    public function chunkSize(): int
    {
        return 250;
    }
}
