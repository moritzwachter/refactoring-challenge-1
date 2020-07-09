<?php

namespace App\Mocks;

class Video
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $filename;

    /** @var string */
    protected $directory;

    /** @var Quality */
    protected $quality;

    /** @var bool */
    protected $isDistributed;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory): void
    {
        $this->directory = $directory;
    }

    /**
     * @return bool
     */
    public function isDistributed(): bool
    {
        return $this->isDistributed;
    }

    /**
     * @param bool $isDistributed
     */
    public function setIsDistributed(bool $isDistributed): void
    {
        $this->isDistributed = $isDistributed;
    }

    /**
     * @return Quality
     */
    public function getQuality(): Quality
    {
        return $this->quality;
    }

    /**
     * @param Quality $quality
     */
    public function setQuality(Quality $quality): void
    {
        $this->quality = $quality;
    }
}
