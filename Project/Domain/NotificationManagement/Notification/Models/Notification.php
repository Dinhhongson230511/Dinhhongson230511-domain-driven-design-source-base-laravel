<?php

namespace Project\Domain\NotificationManagement\Notification\Models;

use Project\Domain\Base\BaseDomainModel;
use Project\Domain\NotificationManagement\Notification\Enums\NotificationStatus;

class Notification extends BaseDomainModel
{
    private string $key;
    private string $type;
    private string $title;
    private string $content;
    private int $status;
    private array $variables;
    private ?string $label;
    private ?string $eligibleUserKey;
    private array $prefectureIds;
    private ?int $followInterval;
    private ?string $redirectTo;

    private array $mappedVariables = [];

    public function __construct(
        string $key,
        string $type,
        string $title,
        string $content,
        int $status = NotificationStatus::Active,
        array $variables = [],
        string $label = '',
        string $eligibleUserKey = '',
        array $prefectureIds = [],
        int $followInterval = 0,
        ?string $redirectTo = null
    )
    {
        $this->setKey($key);
        $this->setType($type);
        $this->setTitle($title);
        $this->setContent($content);
        $this->setStatus($status);
        $this->setVariables($variables);
        $this->setLabel($label);
        $this->setEligibleUserKey($eligibleUserKey);
        $this->setPrefectureIds($prefectureIds);
        $this->setFollowInterval($followInterval);
        $this->setRedirectTo($redirectTo);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return Notification
     */
    public function setKey(string $key): Notification
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Notification
     */
    public function setType(string $type): Notification
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Notification
     */
    public function setTitle(string $title): Notification
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get unmapped variables content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Notification
     */
    public function setContent(string $content): Notification
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Notification
     */
    public function setStatus(int $status): Notification
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param array $variables
     * @return Notification
     */
    public function setVariables(array $variables): Notification
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Notification
     */
    public function setLabel(string $label): Notification
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEligibleUserKey(): ?string
    {
        return $this->eligibleUserKey;
    }

    /**
     * @param string $eligibleUserKey
     * @return Notification
     */
    public function setEligibleUserKey(string $eligibleUserKey): Notification
    {
        $this->eligibleUserKey = $eligibleUserKey;

        return $this;
    }

    /**
     * @return array
     */
    public function getPrefectureIds(): array
    {
        return $this->prefectureIds;
    }

    /**
     * @param array $prefectureIds
     * @return Notification
     */
    public function setPrefectureIds(array $prefectureIds): Notification
    {
        $this->prefectureIds = $prefectureIds;

        return $this;
    }

    /**
     * @return int
     */
    public function getFollowInterval(): ?int
    {
        return $this->followInterval;
    }

    /**
     * @param int $followInterval
     * @return Notification
     */
    public function setFollowInterval(int $followInterval): Notification
    {
        $this->followInterval = $followInterval;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return Notification
     */
    public function mapVariable($key, $value): Notification
    {
        $this->mappedVariables[$key] = $value;

        return $this;
    }

    /**
     * @param array $mappedKeyValue
     * @return Notification
     */
    public function mapVariables(array $mappedKeyValue): Notification
    {
        $this->mappedVariables = $mappedKeyValue;

        return $this;
    }

    /**
     * Generate full content after mapping variables
     *
     * @return string
     */
    public function generateContent(): string
    {
        $content = $this->getContent();

        foreach ($this->getVariables() as $variable) {
            $content = str_replace(':' . $variable, $this->getMappedVariableValue($variable), $content);
        }
        return $content;
    }

    /**
     * Generate full title after mapping variables
     *
     * @return string
     */
    public function generateTitle(): string
    {
        $title = $this->getTitle();

        foreach ($this->getVariables() as $variable) {
            $title = str_replace(':' . $variable, $this->getMappedVariableValue($variable), $title);
        }
        return $title;
    }

    /**
     * @param string $key
     * @return string
     */
    private function getMappedVariableValue(string $key): string
    {
        return isset($this->mappedVariables[$key]) ? $this->mappedVariables[$key] : '';
    }


    /**
     * @return array
     */
    public function getMappedVariables(): array
    {
        return $this->mappedVariables;
    }

    /**
     * @return string|null
     */
    public function getRedirectTo(): ?string
    {
        return $this->redirectTo;
    }

    /**
     * @param string|null $redirectTo
     * @return Notification
     */
    public function setRedirectTo(?string $redirectTo = null): Notification
    {
        $this->redirectTo = $redirectTo;
        return $this;
    }
}
