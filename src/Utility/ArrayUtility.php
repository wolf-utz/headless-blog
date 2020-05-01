<?php declare(strict_types=1);

namespace App\Utility;

class ArrayUtility
{
    public static function snakeCaseKeysToCamelCaseKeys(array $subject): array
    {
        foreach ($subject as $key => $value) {
            $newKey = StringUtility::snakeCaseToCamelCase($key);
            if ($newKey !== $key) {
                $subject[StringUtility::snakeCaseToCamelCase($key)] = $value;
                unset($subject[$key]);
            }
        }

        return $subject;
    }
}
