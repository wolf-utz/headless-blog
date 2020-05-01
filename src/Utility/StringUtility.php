<?php declare(strict_types=1);

namespace App\Utility;

class StringUtility
{
    public static function snakeCaseToCamelCase(string $subject): string
    {
        $subject = str_replace(' ', '', ucwords(str_replace('_', ' ', $subject)));
        $subject[0] = strtolower($subject[0]);

        return $subject;
    }
}
