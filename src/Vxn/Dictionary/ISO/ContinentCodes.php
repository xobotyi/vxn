<?php

    namespace Vxn\Dictionary\ISO;

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

        public static function GetName(string $code) :?string
        {
            return self::$codes[$code] ?? null;
        }

        public static function IsSupported(string $code) :bool
        {
            return isset(self::$codes[$code]);
        }

        public static function GetList() :array
        {
            return self::$codes ?: [];
        }
    }