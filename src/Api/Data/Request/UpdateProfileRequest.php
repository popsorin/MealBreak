<?php


namespace Team1\Api\Data\Request;


class UpdateProfileRequest implements Request
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $age;

    /**
     * UpdateProfileRequest constructor.
     * @param string $name
     * @param string $description
     * @param int $age
     */
    public function __construct(string $name, string $description, int $age)
    {
        $this->name = $name;
        $this->description = $description;
        $this->age = $age;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }
}
