<?php

namespace Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao;

use Project\Domain\UserManagement\User\Models\User as UserDomainModel;
use Project\Infrastructure\Secondary\Database\Base\BaseModel;
use Project\Infrastructure\Secondary\Database\UserManagement\User\Traits\HasFactory;
use Project\Infrastructure\Secondary\Database\UserManagement\User\Traits\UserRelationshipTrait;
use Illuminate\Notifications\Notifiable;

class User extends BaseModel
{
    use Notifiable, UserRelationshipTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /*
     * The columns that should be searched.
     *
     * @var array
     */
    public static array $search = ['id', 'name', 'email'];

    /**
     * @var string[]
     */
    public $casts = [

    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [

    ];

    /*
    * Filer that's applicable for the model
    *
    * @var array
    */
    protected array $filter = [
        'status' => [
            // TODO mention all statuses
        ]
    ];

    /**
     * Get the search params
     *
     * @param $search
     * @return array
     */
    public static function getSortedSearch(array $search): array
    {
        // Initialize the search params
        $finalSearchKeys = [
            'id' => [],
            'name' => [],
            'email' => []
        ];
        foreach ($search as $eachSearchKey)

            if (preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $eachSearchKey))
                array_push($finalSearchKeys['email'], $eachSearchKey);
            elseif (preg_match('/([0-9]+)/', $eachSearchKey))
                array_push($finalSearchKeys['id'], (int) $eachSearchKey);
            else
                array_push($finalSearchKeys['name'], $eachSearchKey);

        return $finalSearchKeys;
    }

    public function toDomainEntity(): UserDomainModel
    {
        $user = new UserDomainModel(
            $this->name,
            $this->email,
            $this->password,
            $this->emailVerifiedAt,
            $this->rememberToken,
        );

        $user->setId($this->getKey());

        $user->setOriginal(
            array_merge($user->getOriginal(), [
                'id' => $this->id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]),
        );

        if ($this->relationLoaded('userPreference')) {
            $user->setUserPreference($this->userPreference?->toDomainEntity());
        }

        return $user;
    }

    /**
     * @param UserDomainModel $user
     * @return User
     */
    protected function fromDomainEntity($user)
    {
        $this->name = $user->getName();
        $this->email = $user->getEmail();
        $this->password = $user->getPassword();
        $this->email_verified_at = $user->getEmailVerifiedAt();
        $this->remember_token = $user->getRememberToken();

        return $this;
    }
}
