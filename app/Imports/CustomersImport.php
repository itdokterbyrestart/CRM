<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CustomersImport implements ToModel, WithHeadingRow
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Customer([
            'name' => $row['name'],
            'company' => $row['company'],
            'email' => $row['email'],
            'email_2' => $row['email_2'],
            'email_3' => $row['email_3'],
            'phone' => $row['phone'],
            'phone_2' => $row['phone_2'],
            'phone_3' => $row['phone_3'],
            'street' => $row['street'],
            'number' => $row['number'],
            'postal_code' => $row['postal_code'],
            'place_name' => $row['place_name'],
            'comment' => $row['comment'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'discount' => 0,
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
