<?php


namespace App\Imports;

use Project\Domain\Base\Exception\BaseValidationException;
use Project\Domain\DatingManagement\ParticipantMainMatch\Model\ParticipantMainMatch;
use Project\Domain\DatingManagement\ParticipantMainMatch\Services\ParticipantMainMatchService;
use Project\Domain\DatingManagement\ParticipantMainMatch\Services\ParticipantMainMatchValidatorService;
use Project\Infrastructure\Secondary\Database\DatingManagement\DatingDay\Repository\EloquentDatingDayRepository;
use Project\Infrastructure\Secondary\Database\DatingManagement\ParticipantMainMatch\Repository\EloquentParticipantMainMatchRepository;
use Project\PorInfrastructuret\Secondary\Database\UserManagement\User\Repository\UserRepository;
use Project\UserInterface\Helpers\Log;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Validators\Failure;


class ValidateUserAdminExample implements WithStartRow, OnEachRow, WithValidation, SkipsOnFailure, WithHeadingRow
{
    /**
     * @var array
     */
    private array $failures = [];

    /**
     * @var array
     */
    private array $existedRecords = [];

    /**
     * @var EloquentParticipantMainMatchRepository
     */
    private EloquentParticipantMainMatchRepository $participantMainMatchRepository;

    /**
     * @var EloquentDatingDayRepository
     */
    private EloquentDatingDayRepository $datingDayRepository;

    private ParticipantMainMatchValidatorService $participantMainMatchValidatorService;
    private ParticipantMainMatchService $participantMainMatchService;
    private UserRepository $userRepository;

    /**
     * ValidateParticipantMainMatchTesting constructor.
     */
    public function __construct() {
        $this->participantMainMatchRepository = resolve(EloquentParticipantMainMatchRepository::class);
        $this->datingDayRepository = resolve(EloquentDatingDayRepository::class);
        $this->userRepository = resolve(UserRepository::class);
        $this->participantMainMatchService = resolve(ParticipantMainMatchService::class);
        $this->participantMainMatchValidatorService = resolve(ParticipantMainMatchValidatorService::class);
    }
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
        try {
            $this->validateDuplicateRow($row);
            $this->validateExisted($row);
            $this->validateBeforeParticipate($row);
        } catch (\Exception $e) {
            Log::error("Error from upload file MainMatchParticipantImport " . $e->getMessage(), $row->toArray());
            $newFailure = new Failure($row->getIndex(), 'user_id', [$e->getMessage()], $row->toArray());
            array_push($this->failures, $newFailure);
        }
    }

    public function validateExisted(Row $row)
    {
        $datingDate = $row['dating_date'];
        $datingDay = $this->datingDayRepository->getByDate($datingDate);

        if ($this->participantMainMatchRepository->getLatestNotExpiredByUserAndDay($row['user_id'], $datingDay)) {
            $msg[] = 'User id and dating day be existed';
            $newFailure = new Failure($row->getIndex(), 'user_id', $msg, $row->toArray());
            array_push($this->failures, [$newFailure]);
        }
    }

    /**
     * @param Row $row
     */
    public function validateDuplicateRow(Row $row)
    {
        $rowUniqueData = $row['user_id'] . '/' . $row['dating_date'];
        $existedIndex = array_search($rowUniqueData, $this->existedRecords);
        if (is_numeric($existedIndex)) {
            $msg[] = 'User id and dating day be duplicated with row ' . $existedIndex + $this->startRow();
            $newFailure = new Failure($row->getIndex(), 'user_id', $msg, $row->toArray());
            array_push($this->failures, [$newFailure]);
        }
        array_push($this->existedRecords, $rowUniqueData);
    }

    public function validateBeforeParticipate(Row $row)
    {
        $datingDate = $row['dating_date'];
        $userId = $row['user_id'];
        $user = $this->userRepository->getById($userId, ['userInfoUpdatedTime']);
        $datingDay = $this->datingDayRepository->getDatingDaysByDate($datingDate);

        $weekStartDate = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $pDaysForMaxW = $this->participantMainMatchRepository->getParticipatedHistoryForUser(
            $user,
            $weekStartDate,
            $weekStartDate->addWeeks(config('matching.max_weeks'))
        )->transform(function ($item) {
            /* @var ParticipantMainMatch $item */
            return $item->getDatingDay();
        });
        try {
            $this->participantMainMatchValidatorService->validate($user, $datingDay, $pDaysForMaxW);
        } catch (BaseValidationException $e) {
            $msg = $e->errors()[array_key_first($e->errors())][0];
            Log::error("Error from upload file MainMatchParticipantImport" . $msg, $row->toArray());
            $newFailure = new Failure($row->getIndex(), 'user_id', [$msg], $row->toArray());
            array_push($this->failures, [$newFailure]);
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        // return [
        //     'user_id' => ['required', Rule::exists('users', 'id')],
        //     'dating_date' => ['required', 'date_format:Y-m-d', Rule::exists('dating_days', 'dating_date')],
        //     'coupon_enabled' => ['required']
        // ];
    }

    /**
     * @return string[]
     */
    public function customValidationMessages()
    {
        return [
            'user_id.required' => 'User id is required.',
            'user_id.exists' => 'User id does not exist in database.',
            'dating_date.required' => 'Dating date is required.',
            'dating_date.date_format' => 'Dating date must be format Y-m-d.',
            'dating_date.exists' => 'Dating date does not exist in database.',
            'coupon_enabled' => 'coupon is required.',
        ];
    }

    /**
     * @param Failure ...$failures
     */
    public function onFailure(Failure ...$failures)
    {
        Log::error("Error from upload file ParticipantMainMatchForTestingImport", $failures);
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
