<?php


namespace Team1\Api\Data\Request;

/**
 * Class DeleteRequest
 * @package Team1\Api\Data\Request
 */
class DeleteRequest
{
    /**
     * @var int
     */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
