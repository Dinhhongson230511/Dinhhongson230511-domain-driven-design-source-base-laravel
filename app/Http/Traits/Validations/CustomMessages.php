<?php

namespace App\Http\Traits\Validations;

trait CustomMessages
{
    /**
     * Custom Message for step one
     *
     * @return array
     */
    protected static function messagesStepZero(): array
    {
        return [
            'prefectureId' => __('api_messages.registration.error_prefecture_required'),
            'gender' => __('api_messages.registration.error_prefecture_required'),
            'year' => __('api_messages.registration.error_year_required'),
            'month' => __('api_messages.registration.error_month_required'),
            'date' => __('api_messages.registration.error_require_date'),
            'email' => __('api_messages.registration.error_require_email')
        ];
    }

    /**
     * Custom Message for step one
     *
     * @return array
     */
    protected static function messagesStepOne(): array
    {
        return [
            'facePreferences' => __('api_messages.registration.face_preferences_is_required'),
        ];
    }

    /**
     * Custom Message for Step Between One Two
     *
     * @return array
     */
    protected static function messagesStepOneFive(): array
    {
        return [
            'preferredCharacters.required' => __('api_messages.registration.error_preferred_character_required'),
        ];
    }

    /**
     * Custom Message for step two
     *
     * @return array
     */
    protected static function messagesStepTwo(): array
    {
        return [
            'minAge' => __('api_messages.registration.min_age_is_required'),
            'maxAge' => __('api_messages.registration.max_age_is_required'),
            'minHeight' => __('api_messages.registration.min_height_is_required'),
            'maxHeight' => __('api_messages.registration.max_height_is_required'),
            'job' => __('api_messages.registration.job_is_required'),
        ];
    }

    /**
     * Custom Message for step three
     *
     * @return array
     */
    protected static function messagesStepThree(): array
    {
        return [
            'bodyType1.required' => __('api_messages.registration.bodyType1_is_required'),
            'bodyType2.required' => __('api_messages.registration.bodyType2_is_required'),
            'education.required' => __('api_messages.registration.preferred_education_is_required')
        ];
    }

    /**
     * Custom Message for step four
     *
     * @return array
     */
    protected static function messagesStepFour(): array
    {
        return [
            'alcohol.required' => __('api_messages.registration.alcohol_is_required'),
            'divorce.required' => __('api_messages.registration.divorce_is_required')
        ];
    }

    /**
     * Custom Message for step five
     *
     * @return array
     */
    protected static function messagesStepFive(): array
    {
        return [
            'annualIncome.required' => __('api_messages.registration.annual_income_is_required'),
            'smoking.required' => __('api_messages.registration.smoking_is_required'),
        ];
    }

    /**
     * Custom Message for step six
     *
     * @return array
     */
    protected static function messagesStepSix(): array
    {
        return [
            'userName.required' => __('api_messages.registration.error_user_name_required'),
        ];
    }

    /**
     * Custom Message for step six
     *
     * @return array
     */
    protected static function messagesStepSeven(): array
    {
        return [
            'images' => __('api_messages.registration.images_is_required')
        ];
    }

    /**
     * Custom Message for step Eight
     *
     * @return array
     */
    protected static function messagesStepEight(): array
    {
        return [
            'height' => __('api_messages.registration.height_is_required'),
            'bodyType' => __('api_messages.registration.body_type_is_required'),
            'education' => __('api_messages.registration.education_is_required'),
        ];
    }

    /**
     * Custom Message for step Nine
     *
     * @return array
     */
    protected static function messagesStepNine(): array
    {
        return ['appearanceStrength' => __('api_messages.registration.appearance_strength_is_required')];
    }

    /**
     * Custom Message for step Ten
     *
     * @return array
     */
    protected static function messagesStepTen(): array
    {
        return [
            'character' => __('api_messages.registration.character_is_required')
        ];
    }

    /**
     * Custom Message for step Eleven
     *
     * @return array
     */
    protected static function messagesStepEleven(): array
    {
        return [
            'hobby' => __('api_messages.registration.hobby_is_required')
        ];
    }

    /**
     * Custom Message for step Twelve
     *
     * @return array
     */
    protected static function messagesStepTwelve(): array
    {
        return [
            'alcohol' => __('api_messages.registration.alcohol_is_required'),
            'smoking' => __('api_messages.registration.smoking_is_required'),
        ];
    }

    /**
     * Custom Message for step Thirteenth
     *
     * @return array
     */
    protected static function messagesStepThirteenth(): array
    {
        return [
            'viewsOnLoveFirstAnswer' => __('api_messages.registration.views_on_love_first_answer_is_required'),
            'viewsOnLoveSecondAnswer' => __('api_messages.registration.views_on_love_second_answer_is_required'),
            'viewsOnLoveThirdAnswer' => __('api_messages.registration.views_on_love_third_answer_is_required'),
        ];
    }

    protected static function messagesStepOneThirtyFive(): array
    {
        return [
            'divorce' => __('api_messages.registration.divorce_is_required'),
            'willingnessForMarriage' => __('api_messages.registration.willingness_for_marriage_is_required'),
            'viewsOnMarriageFirstAnswer' => __('api_messages.registration.views_on_marriage_first_answer_is_required'),
            'viewsOnMarriageSecondAnswer' => __('api_messages.registration.views_on_marriage_second_answer_is_required'),
            'viewsOnMarriageThirdAnswer' => __('api_messages.registration.views_on_marriage_third_answer_is_required'),
        ];
    }

    /**
     * Custom Message for step Fourteenth
     *
     * @return array
     */
    protected static function messagesStepFourteenth(): array
    {
        return [
            'importantPreferences' => __('api_messages.registration.important_preferences_is_required'),
            'importanceOfLookValue' => __('api_messages.registration.importance_of_look_value_is_required'),
            'hobbyCategories' => __('api_messages.registration.hobby_category_priority_is_required'),
            'preferredCharacters' => __('api_messages.registration.error_preferred_character_required'),
        ];
    }

    /**
     * Custom Message for step Fifteenth
     *
     * @return array
     */
    protected static function messagesStepFifteenth(): array
    {
        return [
            'userPreferredAreas' => __('api_messages.registration.user_preferred_areas_is_required')
        ];
    }
}
