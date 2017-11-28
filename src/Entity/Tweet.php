<?php
declare(strict_types=1);

namespace App\Entity;

class Tweet
{

    /** @var int */
    private $id;

    /** @var \DateTimeImmutable */
    private $createdAt;


    /**
     * Tweet constructor.
     *
     * @param int                $id
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(int $id, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

}