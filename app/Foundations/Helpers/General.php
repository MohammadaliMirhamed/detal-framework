<?php

namespace App\Foundations\Helpers;

class General
{
    /**
     * Get storage path
     * 
     * @param string $added
     * @return string
     */
    public static function getStoragePath(string $added = ''): string
    {
        return realpath("..") . '/storage' . $added;
    }

    /**
     * Get public path
     
     * @param string $added 
     * @return string
     */
    public static function getPublicPath(string $added = ''): string
    {
        return realpath("..") . '/public' . $added;
    }

    /**
     * Meta key generator
     
     * @param string $key 
     * @return string
     */
    public static function metaKeyGenerator(string $key): string
    {
        return str_replace(' ', '_', strtolower(trim($key)));
    }

    /**
     * Put a content to a file even the dir does not exist
     * 
     * @param string $fullPathWithFileName 
     * @param string $fileContents 
     * @return bool
     */
    public static function forceFilePutContents(string $fullPathWithFileName, string $fileContents): bool
    {
        $exploded = explode(DIRECTORY_SEPARATOR,$fullPathWithFileName);

        array_pop($exploded);

        $directoryPathOnly = implode(DIRECTORY_SEPARATOR,$exploded);

        if (!file_exists($directoryPathOnly)) 
        {
            mkdir($directoryPathOnly,0775,true);
        }

        file_put_contents($fullPathWithFileName, $fileContents);    

        return true;
    }

    /**
     * Convert jalali date to gregorian date
     * 
     * @param string|int jalaliYear
     * @param string|int jalaliMonth
     * @param string|int jalaliDay
     * @param string splitter
     * @return mixed
     */
    public static function jalaliToGregorian(string|int $jalaliYear, string|int $jalaliMonth, string|int $jalaliDay, string $splitter = ''): mixed
    {
        if ($jalaliYear > 979) {
            $gregorianYear = 1600;
            $jalaliYear -= 979;
        } else {
            $gregorianYear = 621;
        }

        $days = (365 * $jalaliYear) + (((int)($jalaliYear / 33)) * 8) + ((int)((($jalaliYear % 33) + 3) / 4)) + 78 + $jalaliDay + (($jalaliMonth < 7) ? ($jalaliMonth - 1) * 31 : (($jalaliMonth - 7) * 30) + 186);
        $gregorianYear += 400 * ((int)($days / 146097));
        $days %= 146097;

        if ($days > 36524) {
            $gregorianYear += 100 * ((int)(--$days / 36524));
            $days %= 36524;
            if ($days >= 365) $days++;
        }

        $gregorianYear += 4 * ((int)(($days) / 1461));
        $days %= 1461;
        $gregorianYear += (int)(($days - 1) / 365);
        
        if ($days > 365) $days = ($days - 1) % 365;
        
        $gregorianDay = $days + 1;

        foreach (array(0, 31, ((($gregorianYear % 4 == 0) and ($gregorianYear % 100 != 0)) or ($gregorianYear % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31) as $gregorianMonth => $v) {
            if ($gregorianDay <= $v) break;
            $gregorianDay -= $v;
        }

        return ($splitter === '') ? array($gregorianYear, $gregorianMonth, $gregorianDay) : $gregorianYear . $splitter . $gregorianMonth . $splitter . $gregorianDay;
    }

    /**
     * Hash the password
     * 
     * @param string password
     * @return string
     */
    public static function hash(string $password): string
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        
        return password_hash($password, PASSWORD_DEFAULT, array('cost' => 9));
    }
}