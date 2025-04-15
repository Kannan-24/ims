<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class CustomerExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $customers = Customer::with('contactPersons')->get();
        $data = [];

        foreach ($customers as $customer) {
            $contacts = $customer->contactPersons->map(function ($person) {
                return "{$person->name} ({$person->email}, {$person->phone_no})";
            })->implode("\n");

            $data[] = [
                count($data) + 1,
                $customer->cid,
                $customer->company_name,
                $customer->address,
                $customer->city,
                $customer->state,
                $customer->zip_code,
                $customer->country,
                $customer->gst_number,
                $contacts
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            '#',
            'Customer ID',
            'Company Name',
            'Address',
            'City',
            'State',
            'Zip Code',
            'Country',
            'GST Number',
            'Contact Persons'
        ];
    }
}
