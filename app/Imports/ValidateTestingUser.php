<?php


namespace App\Imports;

use Project\UserInterface\Helpers\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Validators\Failure;


class ValidateTestingUser implements WithStartRow, OnEachRow, WithValidation, SkipsOnFailure, WithHeadingRow
{
    /**
     * @var array
     */
    private array $failures = [];

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param Row $row
     */
    public function onRow(Row $row)
    {

    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required'],
            'coupon_type' => ['required'],
            'expiry_days_after' => ['required']
        ];
    }

    /**
     * @return string[]
     */
    public function customValidationMessages()
    {
        return [
            'user_id.required' => 'User id is required.',
            'coupon_type.required' => 'Coupon type is required.',
            'expiry_days_after.required' => 'Expiry days after is required.',
        ];
    }

    /**
     * @param Failure ...$failures
     */
    public function onFailure(Failure ...$failures)
    {
        Log::error("Error from upload file issue coupons", $failures);
        array_push($this->failures, $failures);
    }

    /**
     * @return array
     */
    public function getFailures(): array
    {
        return $this->failures;
    }
}
