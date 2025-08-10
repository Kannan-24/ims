<?php

namespace App\Models\ims;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryChallan extends Model
{
    use HasFactory;

    protected $fillable = [
        'dc_no',
        'invoice_id',
        'delivery_date',
        'status',
        'generated_at'
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'generated_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Generate next DC number
    public static function generateDcNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastDc = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastDc ? (int)substr($lastDc->dc_no, -3) + 1 : 1;

        return 'DC/' . $year . '-' . ($year + 1) . '/' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
