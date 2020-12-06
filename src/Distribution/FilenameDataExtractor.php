<?php

namespace App\Distribution;

use App\Exception\FilenameParseException;

class FilenameDataExtractor
{
    public static function getIdFromFilename(string $filename, string $regex): int
    {
        $matches = [];
        preg_match($regex, $filename, $matches);

        if (!isset($matches['id'])) {
            throw new FilenameParseException("Could not extract id from {$filename} with {$regex}");
        }

        return (int)$matches['id'];
    }

    /**
     * @param array $parameters
     * @param string $filename
     * @param string $regex
     * @return array
     */
    public static function getParametersFromRegex(array $parameters, string $filename, string $regex): array
    {
        $matches = [];
        preg_match($regex, $filename, $matches);

        $result = [];
        foreach ($parameters as $parameter) {
            if (!isset($matches[$parameter])) {
                throw new \InvalidArgumentException("Unable to extract {$parameter} from filename: {$filename}");
            }

            $result[] = $matches[$parameter];
        }

        return $result;
    }
}
