<?php

namespace App\Imports;

use App\Models\{
    Order,
    Customer,
    OrderStatus,
};
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class OrdersImport implements ToModel, WithHeadingRow
{
    private $customers, $statuses;
    
    public function __construct()
    {
        $this->customers = Customer::select('id','name')->get();
        $this->statuses = OrderStatus::select('id','name')->get();
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $customer = $this->customers->where('name', $row['customer'])->first();
        $status = $this->statuses->where('name', $row['status'])->first();
        return new Order([
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'customer_id' => $customer->id ?? NULL,
            'order_status_id' => $status->id ?? NULL,
            'updated_at' => $row['created_at'],
            'created_at' => $row['created_at'],
            'total_price_customer_excluding_tax' => 0,
            'total_price_customer_including_tax' => 0,
            'total_tax_amount' => 0,
            'total_purchase_price_excluding_tax' => 0,
            'total_profit' => 0,

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
