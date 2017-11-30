<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper;

    final class Str
    {
        private const BASE62_POOL = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        private const BASE62_BASE = 62;

        /**
         * Random string generator, based on mt_rand() function
         *
         * @param int $length
         * @param int $poolBase
         *
         * @return string
         * @throws \Error
         */
        public static function Generate(int $length = 8, int $poolBase = 62) :string
        {
            if ($length <= 0) {
                throw new \Error("GenerateString \$length mint be positive, non-zero integer");
            }
            else if ($poolBase <= 0 || $poolBase > 62) {
                throw new \Error("GenerateString supports \$poolBase values only from 1 to 62");
            }

            $poolBase -= 1;
            $str      = '';

            while ($length) {
                $str = self::BASE62_POOL[mt_rand(0, $poolBase)] . $str;

                $length--;
            }

            return $str;
        }


        /**
         * Pseudo-random string generator can provide 62^12 unique strings
         * Generated string has length of 12, "z" char uses to fill generated char to 12 chars length
         *
         * @param int|float $num
         * @param int       $seed
         *
         * @return string
         */
        public static function HashFromInt($num, int $seed = 5421087) :string
        {
            $scrambled = (324863748635 * $num + $seed) % 2654348974297586158321;

            $res = '';

            while ($scrambled) {
                $res       = self::BASE62_POOL[$scrambled % self::BASE62_BASE] . $res;
                $scrambled = intdiv($scrambled, self::BASE62_BASE);
            }

            $res = str_repeat('0', 12 - strlen($res)) . $res;

            return $res;
        }

        public static function UUID4() :string
        {
            return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                           mt_rand(0, 65535), mt_rand(0, 65535),
                           mt_rand(0, 65535),
                           mt_rand(0, 4095) | 0x4000,
                           mt_rand(0, 0x3fff) | 0x8000,
                           mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)
            );
        }

        public static function PathFormat(string $path) :string
        {
            return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        }

        public static function PathJoin(...$args) :string
        {
            return implode($args, DIRECTORY_SEPARATOR);
        }

        public static function UpFirstSymbol(string $string, bool $lowerOthers = true) :string
        {
            return $lowerOthers ? ucfirst(mb_strtolower($string)) : ucfirst($string);
        }

        public static function Replace(string $search, string $replace, string $string, int $count = -1) :string
        {
            return preg_replace('~' . quotemeta($search) . '~su', $replace, $string, $count);
        }
    }