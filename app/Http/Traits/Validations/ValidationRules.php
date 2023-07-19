<?php

namespace App\Http\Traits\Validations;

use Project\Domain\UserManagement\User\Interfaces\SendableEmailRepositoryInterface;
use Project\Domain\UserManagement\User\Rules\SendableEmail;
use Illuminate\Support\Facades\Auth;

trait ValidationRules
{
    private SendableEmailRepositoryInterface $sendableEmailRepository;
    /**
     * Validation rules for step one
     *
     * @return array
     */
    public static function rulesStepZero(): array
    {
        return [
            'prefectureId' => 'required|integer',
            'gender' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'date' => 'required|integer',
            'code' => 'required|string|nullable',
            'email' => ['required', new SendableEmail],
            'livingPrefectureId' => 'nullable|integer',
            'livingResidenceId' => 'nullable|integer',
        ];
    }
    /**
     * Validation rules for step one
     *
     * @return array
     */
    public static function rulesStepOne(): array
    {
        return [
            'facePreferences' => 'required|string|min:3',
        ];
    }

    /**
     * Validation rules for step two
     *
     * @return array
     */
    protected static function rulesStepTwo(): array
    {
        return [
            'minAge' => 'required|int',
            'maxAge' => 'required|int',
            'minHeight' => 'required|int',
            'maxHeight' => 'required|int',
            'job' => 'nullable|string',
        ];
    }

    /**
     * Validation rules for step two
     *
     * @return array
     */
    protected static function rulesStepThree(): array
    {
        return [
            'bodyType1' => 'required|int',
            'bodyType2' => 'required|int',
            'education' => 'required|int',
        ];
    }

    /**
     * Validation rules for step three
     *
     * @return array
     */
    protected static function rulesStepFour(): array
    {
        return [
            'alcohol' => 'required|int',
            'divorce' => 'required|int',
        ];
    }

    /**
     * Validation rules for step four
     *
     * @return array
     */
    protected static function rulesStepFive(): array
    {
        return [
            'smoking' => 'required|int',
            'annualIncome' => 'required|int',
        ];
    }

    /**
     * Validation rules for step five
     *
     * @return array
     */
    protected static function rulesStepSix(): array
    {
        return [
            'userName' => 'required|string',
            'job' => 'required|int',
            'annualIncome' => 'nullable|int',
            'education' => 'nullable|int',
            'schoolId' => 'nullable|int',
        ];
    }

    /**
     * Validation rules for step six
     *
     * @return array
     */
    protected static function rulesStepSeven(): array
    {
        return [
            'images' => 'required|string'
        ];
    }

    /**
     * Step eight rules
     *
     * @return array
     */
    private static function rulesStepEight(): array
    {
        return [
            'height' => 'required|int|gt:0',
            'bodyType' => 'required|int',
            'education' => 'required|int|gt:0',
        ];
    }

    /**
     * Step Nine rules
     *
     * @return array
     */
    private static function rulesStepNine(): array
    {
        return [
            'appearanceStrength' => 'required|array',
            'appearanceStrength.*' => 'required',
        ];
    }

    /**
     * Step Ten rules
     *
     * @return array
     */
    private static function rulesStepTen(): array
    {
        return [
            'character' => 'required|string'
        ];
    }

    /**
     * Step Eleven rules
     *
     * @return array
     */
    private static function rulesStepEleven(): array
    {
        return [
            '*.hobby_category_id' => 'required|integer',
            '*.hobbies' => 'required'
        ];
    }

    /**
     * Step Twelve rules
     *
     * @return array
     */
    private static function rulesStepTwelve(): array
    {
        return [
            'alcohol' => 'required|string',
            'smoking' => 'nullable|string',
        ];
    }

    private static function rulesStepThirteenth(): array
    {
        return [
            'viewsOnLoveFirstAnswer' => 'required',
            'viewsOnLoveSecondAnswer' => 'required',
            'viewsOnLoveThirdAnswer' => 'required',
        ];
    }

    /**
     * Step Thirteenth the second rules
     *
     * @return array
     */
    private static function rulesStepOneThirtyFive(): array
    {
        return [
            'divorce' => 'required|int',
            'willingnessForMarriage' => 'required|int',
            'viewsOnMarriageFirstAnswer' => 'required',
            'viewsOnMarriageSecondAnswer' => 'required',
            'viewsOnMarriageThirdAnswer' => 'required',
        ];
    }

    /**
     * Step Fourteenth rules
     *
     * @return array
     */
    private static function rulesStepFourteenth(): array
    {
        return [
            'importantPreferences' => 'required|array',
            'importanceOfLookValue' => 'required|int',
            'hobbyCategories' => 'present|array',
            'preferredCharacters' => 'present|array',
        ];
    }

    /**
     * Step Fifteenth rules
     *
     * @return array
     */
    private static function rulesStepFifteenth(): array
    {
        return [
            'userPreferredAreas' => 'required|array'
        ];
    }

    /**
     * Validation rules for step Step Between One and Two
     *
     * @return array
     */
    public static function rulesStepOneFive(): array
    {
        return [
            'preferredCharacters' => 'required|array',
        ];
    }
}
