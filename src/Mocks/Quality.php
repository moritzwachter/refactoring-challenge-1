<?php

namespace App\Mocks;

class Quality
{
    const Q1A = 'Niedrig';
    const Q4A = 'Mittel';
    const Q6A = 'Hoch';
    const Q8C = 'Sehr hoch';

    /** @var int */
    protected $id;

    /** @var string */
    protected $key;

    /** @var string */
    protected $name;

    public function __construct(string $key, string $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
