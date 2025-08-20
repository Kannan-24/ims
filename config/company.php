<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Company Information
    |--------------------------------------------------------------------------
    |
    | This file contains the company information that will be used
    | in PDF generation, invoices, quotations, and other documents.
    |
    */

    'name' => env('COMPANY_NAME', 'Your Company Name'),
    'tagline' => env('COMPANY_TAGLINE', 'Professional Business Solutions'),
    'address' => env('COMPANY_ADDRESS', 'Your Company Address'),
    'phone' => env('COMPANY_PHONE', 'Your Phone Number'),
    'phone_2' => env('COMPANY_PHONE_2', null),
    'email' => env('COMPANY_EMAIL', 'email@company.com'),
    'website' => env('COMPANY_WEBSITE', 'www.company.com'),
    'abuse_email' => env('COMPANY_ABUSE_EMAIL', 'report@skm.in'),
    'gst_number' => env('COMPANY_GST', 'GST Number'),
    'udyam_number' => env('COMPANY_UDYAM', 'UDYAM Number'),
    
    /*
    |--------------------------------------------------------------------------
    | Banking Information
    |--------------------------------------------------------------------------
    |
    | Bank details for payment instructions in invoices
    |
    */
    
    'bank' => [
        'name' => env('COMPANY_BANK_NAME', 'State Bank of India'),
        'account_name' => env('COMPANY_BANK_ACCOUNT_NAME', env('COMPANY_NAME', 'Your Company Name')),
        'account_number' => env('COMPANY_BANK_ACCOUNT', 'XXXX-XXXX-XXXX'),
        'ifsc_code' => env('COMPANY_BANK_IFSC', 'SBIN0XXXXXX'),
        'branch' => env('COMPANY_BANK_BRANCH', 'Main Branch'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Document Settings
    |--------------------------------------------------------------------------
    |
    | Settings for PDF generation and document formatting
    |
    */
    
    'invoice' => [
        'payment_terms' => env('INVOICE_PAYMENT_TERMS', 'Net 30 Days'),
        'late_fee_rate' => env('INVOICE_LATE_FEE_RATE', '2% per month'),
        'notes' => env('INVOICE_NOTES', 'Thank you for your business!'),
    ],
    
    'quotation' => [
        'validity_days' => env('QUOTATION_VALIDITY_DAYS', 30),
        'payment_terms' => env('QUOTATION_PAYMENT_TERMS', '50% advance, 50% on delivery'),
        'notes' => env('QUOTATION_NOTES', 'We look forward to doing business with you!'),
    ],
];
