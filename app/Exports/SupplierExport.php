<?php

namespace App\Exports;

use App\Models\ims\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupplierExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Supplier::select([
            DB::raw('ROW_NUMBER() OVER(ORDER BY supplier_id) as row_number'),
            'supplier_id',
            'name',
            'contact_person',
            'phone_number',
            'email',
            'address',
            'city',
            'state',
            'country',
            'postal_code',
            'gst'
        ])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Supplier ID',
            'Name',
            'Contact Person',
            'Phone Number',
            'Email',
            'Address',
            'City',
            'State',
            'Country',
            'Postal Code',
            'GST'
        ];
    }
}
