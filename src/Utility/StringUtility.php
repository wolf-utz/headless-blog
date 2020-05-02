<?php declare(strict_types=1);

namespace App\Utility;

class StringUtility
{
    public static function camelCaseToSnakeCase(string $subject): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $subject, $matches);
        $result = $matches[0];
        foreach ($result as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $result);
    }
}
