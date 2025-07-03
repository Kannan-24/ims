<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GstService
{
    /**
     * GST State Code Mapping
     */
    private const GST_STATE_CODES = [
        '01' => 'Jammu and Kashmir',
        '02' => 'Himachal Pradesh',
        '03' => 'Punjab',
        '04' => 'Chandigarh',
        '05' => 'Uttarakhand',
        '06' => 'Haryana',
        '07' => 'Delhi',
        '08' => 'Rajasthan',
        '09' => 'Uttar Pradesh',
        '10' => 'Bihar',
        '11' => 'Sikkim',
        '12' => 'Arunachal Pradesh',
        '13' => 'Nagaland',
        '14' => 'Manipur',
        '15' => 'Mizoram',
        '16' => 'Tripura',
        '17' => 'Meghalaya',
        '18' => 'Assam',
        '19' => 'West Bengal',
        '20' => 'Jharkhand',
        '21' => 'Odisha',
        '22' => 'Chhattisgarh',
        '23' => 'Madhya Pradesh',
        '24' => 'Gujarat',
        '25' => 'Daman and Diu',
        '26' => 'Dadra and Nagar Haveli',
        '27' => 'Maharashtra',
        '28' => 'Andhra Pradesh',
        '29' => 'Karnataka',
        '30' => 'Goa',
        '31' => 'Lakshadweep',
        '32' => 'Kerala',
        '33' => 'Tamil Nadu',
        '34' => 'Puducherry',
        '35' => 'Andaman and Nicobar Islands',
        '36' => 'Telangana',
        '37' => 'Andhra Pradesh (New)',
        '38' => 'Ladakh'
    ];

    /**
     * Validate GST number format
     */
    public function validateGstFormat(string $gstNumber): array
    {
        $gstNumber = strtoupper(trim($gstNumber));

        // Check if GST number is exactly 15 characters
        if (strlen($gstNumber) !== 15) {
            return [
                'valid' => false,
                'message' => 'GST number must be exactly 15 characters long.'
            ];
        }

        // Check format: 2 digits + 5 letters + 4 digits + 1 letter + 1 digit + 1 letter + 1 digit
        if (!preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9]{1}[A-Z]{1}[0-9]{1}$/', $gstNumber)) {
            return [
                'valid' => false,
                'message' => 'Invalid GST number format.'
            ];
        }

        // Validate state code
        $stateCode = substr($gstNumber, 0, 2);
        if (!isset(self::GST_STATE_CODES[$stateCode])) {
            return [
                'valid' => false,
                'message' => 'Invalid state code in GST number.'
            ];
        }

        return [
            'valid' => true,
            'message' => 'Valid GST number format.',
            'state_code' => $stateCode,
            'state_name' => self::GST_STATE_CODES[$stateCode]
        ];
    }

    /**
     * Extract PAN number from GST number
     */
    public function extractPanFromGst(string $gstNumber): ?string
    {
        $formatValidation = $this->validateGstFormat($gstNumber);
        
        if (!$formatValidation['valid']) {
            return null;
        }

        return substr($gstNumber, 2, 10);
    }

    /**
     * Get state name from GST number
     */
    public function getStateFromGst(string $gstNumber): ?string
    {
        $formatValidation = $this->validateGstFormat($gstNumber);
        
        if (!$formatValidation['valid']) {
            return null;
        }

        return $formatValidation['state_name'];
    }

    /**
     * Validate GST number with mock API (simulating GST portal integration)
     * In a real implementation, this would connect to actual GST APIs
     */
    public function validateGstWithApi(string $gstNumber): array
    {
        try {
            // First validate format
            $formatValidation = $this->validateGstFormat($gstNumber);
            
            if (!$formatValidation['valid']) {
                return $formatValidation;
            }

            // Mock API validation (in real implementation, call actual GST API)
            // For now, we'll simulate different responses based on GST number patterns
            $gstNumber = strtoupper(trim($gstNumber));
            
            // Simulate API call delay
            usleep(500000); // 0.5 second delay

            // Mock different responses for testing
            // Check the 14th character (index 13) for different response types
            $checkChar = substr($gstNumber, 13, 1);
            
            if ($checkChar === 'Z') {
                // Simulate active GST
                return [
                    'valid' => true,
                    'status' => 'Active',
                    'company_name' => 'Mock Company Pvt Ltd',
                    'address' => 'Mock Address Line 1, Mock City',
                    'city' => 'Mock City',
                    'state' => $formatValidation['state_name'],
                    'business_type' => 'Private Limited Company',
                    'registration_date' => '2020-04-01',
                    'pan_number' => $this->extractPanFromGst($gstNumber),
                    'message' => 'GST number is valid and active.'
                ];
            } elseif ($checkChar === 'X') {
                // Simulate cancelled GST
                return [
                    'valid' => false,
                    'status' => 'Cancelled',
                    'message' => 'GST number is cancelled.'
                ];
            } elseif ($checkChar === 'Y') {
                // Simulate suspended GST
                return [
                    'valid' => false,
                    'status' => 'Suspended',
                    'message' => 'GST number is suspended.'
                ];
            } else {
                // Simulate not found
                return [
                    'valid' => false,
                    'status' => 'Not Found',
                    'message' => 'GST number not found in records.'
                ];
            }

        } catch (Exception $e) {
            Log::error('GST API validation error: ' . $e->getMessage());
            
            return [
                'valid' => false,
                'message' => 'Unable to validate GST number. Please try again later.',
                'error' => 'api_error'
            ];
        }
    }

    /**
     * Get all GST state codes
     */
    public function getStateCodes(): array
    {
        return self::GST_STATE_CODES;
    }

    /**
     * Check if GST number already exists for another customer
     */
    public function checkDuplicateGst(string $gstNumber, ?int $excludeCustomerId = null): bool
    {
        $query = \App\Models\ims\Customer::where('gst_number', strtoupper(trim($gstNumber)));
        
        if ($excludeCustomerId) {
            $query->where('id', '!=', $excludeCustomerId);
        }
        
        return $query->exists();
    }
}