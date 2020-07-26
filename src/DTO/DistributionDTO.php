<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

class DistributionDTO
{
    /** @var string */
    protected $filename;

    /** @var string */
    protected $directory;

    /** @var string */
    protected $type;

    /** @var string */
    protected $qualityKey;

    /**
     * @param string $filename
     * @param string $directory
     * @param string $type
     * @param string $qualityKey
     */
    public function __construct(string $filename, string $directory, string $type, string $qualityKey)
    {
        $this->filename = $filename;
        $this->directory = $directory;
        $this->type = $type;
        $this->qualityKey = $qualityKey;
    }

    public static function fromRequest(Request $request)
    {
        $filename = $request->query->get('file');
        $directory = $request->query->get('directory');
        $type = $request->query->get('type');
        $qualityKey = $request->query->get('quality') ?? '';

        return new self($filename, $directory, $type, $qualityKey);
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getQualityKey(): string
    {
        return $this->qualityKey;
    }
}
