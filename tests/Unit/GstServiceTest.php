<?php

namespace Tests\Unit;

use App\Services\GstService;
use PHPUnit\Framework\TestCase;

class GstServiceTest extends TestCase
{
    private GstService $gstService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gstService = new GstService();
    }

    /**
     * Test valid GST number format validation
     */
    public function test_validates_correct_gst_format(): void
    {
        $validGstNumber = '29ABCDE1234L1Z5';
        $result = $this->gstService->validateGstFormat($validGstNumber);
        
        $this->assertTrue($result['valid']);
        $this->assertEquals('Valid GST number format.', $result['message']);
        $this->assertEquals('29', $result['state_code']);
        $this->assertEquals('Karnataka', $result['state_name']);
    }

    /**
     * Test invalid GST number format validation
     */
    public function test_rejects_invalid_gst_format(): void
    {
        $invalidGstNumber = '12345INVALID123';
        $result = $this->gstService->validateGstFormat($invalidGstNumber);
        
        $this->assertFalse($result['valid']);
        $this->assertEquals('Invalid GST number format.', $result['message']);
    }

    /**
     * Test GST number with wrong length
     */
    public function test_rejects_wrong_length_gst(): void
    {
        $shortGstNumber = '12345';
        $result = $this->gstService->validateGstFormat($shortGstNumber);
        
        $this->assertFalse($result['valid']);
        $this->assertEquals('GST number must be exactly 15 characters long.', $result['message']);
    }

    /**
     * Test invalid state code in GST number
     */
    public function test_rejects_invalid_state_code(): void
    {
        $invalidStateGst = '99ABCDE1234L1Z5'; // 99 is not a valid state code
        $result = $this->gstService->validateGstFormat($invalidStateGst);
        
        $this->assertFalse($result['valid']);
        $this->assertEquals('Invalid state code in GST number.', $result['message']);
    }

    /**
     * Test PAN extraction from GST number
     */
    public function test_extracts_pan_from_valid_gst(): void
    {
        $gstNumber = '29ABCDE1234L1Z5';
        $expectedPan = 'ABCDE1234L';
        
        $result = $this->gstService->extractPanFromGst($gstNumber);
        
        $this->assertEquals($expectedPan, $result);
    }

    /**
     * Test PAN extraction from invalid GST number
     */
    public function test_returns_null_for_invalid_gst_pan_extraction(): void
    {
        $invalidGstNumber = 'INVALID';
        
        $result = $this->gstService->extractPanFromGst($invalidGstNumber);
        
        $this->assertNull($result);
    }

    /**
     * Test state extraction from GST number
     */
    public function test_extracts_state_from_valid_gst(): void
    {
        $gstNumber = '07ABCDE1234L1Z5'; // 07 is Delhi
        $expectedState = 'Delhi';
        
        $result = $this->gstService->getStateFromGst($gstNumber);
        
        $this->assertEquals($expectedState, $result);
    }

    /**
     * Test state extraction from invalid GST number
     */
    public function test_returns_null_for_invalid_gst_state_extraction(): void
    {
        $invalidGstNumber = 'INVALID';
        
        $result = $this->gstService->getStateFromGst($invalidGstNumber);
        
        $this->assertNull($result);
    }

    /**
     * Test getting all state codes
     */
    public function test_returns_all_state_codes(): void
    {
        $stateCodes = $this->gstService->getStateCodes();
        
        $this->assertIsArray($stateCodes);
        $this->assertArrayHasKey('29', $stateCodes);
        $this->assertEquals('Karnataka', $stateCodes['29']);
        $this->assertArrayHasKey('07', $stateCodes);
        $this->assertEquals('Delhi', $stateCodes['07']);
    }

    /**
     * Test mock API validation for active GST
     */
    public function test_mock_api_validation_for_active_gst(): void
    {
        $activeGstNumber = '29ABCDE1234L1Z5'; // 14th char (index 13) is Z, should be active
        $result = $this->gstService->validateGstWithApi($activeGstNumber);
        
        $this->assertTrue($result['valid']);
        $this->assertEquals('Active', $result['status']);
        $this->assertArrayHasKey('company_name', $result);
        $this->assertArrayHasKey('pan_number', $result);
    }

    /**
     * Test mock API validation for cancelled GST
     */
    public function test_mock_api_validation_for_cancelled_gst(): void
    {
        $cancelledGstNumber = '29ABCDE1234L1X5'; // 14th char (index 13) is X, should be cancelled
        $result = $this->gstService->validateGstWithApi($cancelledGstNumber);
        
        $this->assertFalse($result['valid']);
        $this->assertEquals('Cancelled', $result['status']);
    }

    /**
     * Test mock API validation for suspended GST
     */
    public function test_mock_api_validation_for_suspended_gst(): void
    {
        $suspendedGstNumber = '29ABCDE1234L1Y5'; // 14th char (index 13) is Y, should be suspended
        $result = $this->gstService->validateGstWithApi($suspendedGstNumber);
        
        $this->assertFalse($result['valid']);
        $this->assertEquals('Suspended', $result['status']);
    }
}
