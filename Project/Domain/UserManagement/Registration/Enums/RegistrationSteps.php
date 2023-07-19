<?php

namespace Project\Domain\UserManagement\Registration\Enums;

use Project\UserInterface\Enums\IntEnum;

/**
 * @method static static StepOne()
 * @method static static StepOneFive()
 * @method static static StepTwo()
 * @method static static StepThree()
 * @method static static StepFour()
 * @method static static StepFive()
 * @method static static StepSix()
 * @method static static StepSeven()
 * @method static static StepEight()
 * @method static static StepNine()
 * @method static static StepTen()
 * @method static static StepEleven()
 * @method static static StepTwelve()
 * @method static static StepThirteenth()
 * @method static static StepOneThirtyFive()
 * @method static static StepFourteenth()
 * @method static static StepFifteenth()
 * @method static static StepSixteenth()
 */
final class RegistrationSteps extends IntEnum
{
    const StepZero = 0.0;
    const StepOne = 1.0;
    const StepOneFive = 1.5;
    const StepTwo = 2.0;
    const StepThree = 3.0;
    const StepFour = 4.0;
    const StepFive = 5.0;
    const StepSix = 6.0;
    const StepSeven = 7.0;
    const StepEight = 8.0;
    const StepNine = 9.0;
    const StepTen = 10.0;
    const StepEleven = 11.0;
    const StepTwelve = 12.0;
    const StepThirteenth = 13.0;
    const StepOneThirtyFive = 13.5;
    const StepFourteenth = 14.0;
    const StepFifteenth = 15.0;
    const StepSixteenth = 16.0;

    const StepBefore1stParticipateMain = 7.0;
    const StepBeforeFinal = 15.0;
    const StepFinal = 16.0;


    public static function getNextStep(int $currentStep): int|null
    {
        $allSteps = [
            self::StepZero,
            self::StepOne,
            self::StepOneFive,
            self::StepTwo,
            self::StepThree,
            self::StepFour,
            self::StepFive,
            self::StepSix,
            self::StepSeven,
            self::StepEight,
            self::StepNine,
            self::StepTen,
            self::StepEleven,
            self::StepTwelve,
            self::StepThirteenth,
            self::StepOneThirtyFive,
            self::StepFourteenth,
            self::StepFifteenth,
            self::StepSixteenth,
        ];
        $keyOfCurrentStep = array_search($currentStep, $allSteps);

        return $allSteps[$keyOfCurrentStep] != self::StepSixteenth ? $allSteps[$keyOfCurrentStep + 1] : null;
    }
}
