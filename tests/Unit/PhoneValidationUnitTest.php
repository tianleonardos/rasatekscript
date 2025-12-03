<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PhoneValidationUnitTest extends TestCase
{
    /** @test */
    public function phone_validation_accepts_valid_08_format()
    {
        $validator = Validator::make([
            'phone' => '081234567890'
        ], [
            'phone' => ['required', 'string', 'regex:/^(08[0-9]{8,11}|\+62[0-9]{9,10})$/', 'min:10', 'max:13']
        ]);

        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function phone_validation_accepts_valid_plus62_format()
    {
        $validator = Validator::make([
            'phone' => '+6281234567890'
        ], [
            'phone' => ['required', 'string', 'regex:/^(08[0-9]{8,11}|\+62[0-9]{9,10})$/', 'min:10', 'max:13']
        ]);

        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function phone_validation_rejects_invalid_prefix()
    {
        $validator = Validator::make([
            'phone' => '071234567890'
        ], [
            'phone' => ['required', 'string', 'regex:/^(08[0-9]{8,11}|\+62[0-9]{8,10})$/', 'min:10', 'max:13']
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    /** @test */
    public function phone_validation_rejects_too_short()
    {
        $validator = Validator::make([
            'phone' => '08123'
        ], [
            'phone' => ['required', 'string', 'regex:/^(08[0-9]{8,11}|\+62[0-9]{8,10})$/', 'min:10', 'max:13']
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    /** @test */
    public function phone_validation_rejects_too_long()
    {
        $validator = Validator::make([
            'phone' => '0812345678901234'
        ], [
            'phone' => ['required', 'string', 'regex:/^(08[0-9]{8,11}|\+62[0-9]{8,10})$/', 'min:10', 'max:13']
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    /** @test */
    public function phone_validation_rejects_invalid_characters()
    {
        $validator = Validator::make([
            'phone' => '08123456789a'
        ], [
            'phone' => ['required', 'string', 'regex:/^(08[0-9]{8,11}|\+62[0-9]{8,10})$/', 'min:10', 'max:13']
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }
}
