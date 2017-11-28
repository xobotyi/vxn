<?php

    namespace Vxn\Dictionary\ISO;

    use Vxn\Helper\Arr;

    final class ContinentCodes
    {
        private static $codes = [
            'as' => 'Asia',
            'eu' => 'Europe',
            'na' => 'North America',
            'sa' => 'South America',
            'af' => 'Africa',
            'au' => 'Australia',
            'an' => 'Antarctica',
        ];

        public static function Get(string $code) : ?string
        {
            return Arr::Get($code, self::$codes);
        }

        public static function GetList() :array
        {
            return self::$codes ?: [];
        }

        public static function IsSupported(string $code) :bool
        {
            return Arr::Check($code, self::$codes);
        }
    }