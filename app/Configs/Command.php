<?php

namespace App\Configs;

use App\Foundations\Helpers\General;

class Command
{
    protected static array $file;

    /**
     * Command line print/echo format
     * 
     * @param array $format
     * @param string $text
     * @return void
     */
    public static function formatPrint(array $format = [], string $text = ''): void
    {
        $codes = [
            'bold'=>1, 'italic'=>3, 'underline'=>4, 'strikethrough'=>9, 'magenta'=>35, 'cyan'=>36, 'white'=>37,
            'black'=>30, 'red'=>31, 'green'=>32, 'yellow'=>33,'blue'=>34, 'magentabg'=>45, 'cyanbg'=>46, 
            'blackbg'=>40, 'redbg'=>41, 'greenbg'=>42, 'yellowbg'=>44,'bluebg'=>44, 'lightgreybg'=>47
        ];

        $formatMap = array_map(function ($v) use ($codes) { return $codes[$v]; }, $format);
        
        echo "\e[".implode(';',$formatMap).'m'.$text."\e[0m";
    }

    /**
     * Command line print/echo format with brack line
     * 
     * @param array $format
     * @param string $text
     * @return void
     */
    public static function formatPrintLn(array $format=[], string $text=''): void
    {
        self::formatPrint($format, $text);
        echo "\r\n";
    }

    /**
     * Publish the class requested
     * 
     * @param string $type
     * @param string $filenme
     * @param array $dataPattern
     * @return int
     */
    public static function publisher(string $type, string $fileName, array $dataPattern = []): int
    {
        (new self)->init();

        $filePath = self::$file[$type]['path'] . $fileName . self::$file[$type]['extenstion'];
        
        if (!is_file($filePath)) {

            $content = file_get_contents(self::$file[$type]['template']);
            
            if (count($dataPattern) > 0) {
                foreach ($dataPattern as $find => $replace) {
                    $content = str_replace($find, $replace, $content);
                }
            }

            General::forceFilePutContents($filePath, $content);

            return 1;
        }

        return 0;
    }


    /**
     * init the vars and paths
     * 
     * @return void
     */
    private function init(): void
    {
        $realPath = realpath('.');

        self::$file = [
            'model'      => [
                'template'   => $realPath . '/app/Vendor/Command/Model.tpl',
                'path'       => $realPath . '/app/Models/',
                'extenstion' => '.php'
            ],
            'controller' => [
                'template'   => $realPath . '/app/Vendor/Command/Controller.tpl',
                'path'       => $realPath . '/app/Controllers/',
                'extenstion' => '.php'
            ],
            'service'    => [
                'template'   => $realPath . '/app/Vendor/Command/Service.tpl',
                'path'       => $realPath . '/app/Services/',
                'extenstion' => '.php'
            ],
            'interface'  => [
                'template'   => $realPath . '/app/Vendor/Command/Interface.tpl',
                'path'       => $realPath . '/app/Foundations/Contracts/',
                'extenstion' => '.php'
            ],
        ];
    }
}

