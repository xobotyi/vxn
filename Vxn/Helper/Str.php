<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper;

    final class Str
    {
        public const WIDE_STR_GENERATOR_POOL      = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        public const WIDE_STR_GENERATOR_POOL_BASE = 62;
        public const STR_GENERATOR_POOL           = '0123456789abcdefghijklmnopqrstuvwxyz';
        public const STR_GENERATOR_POOL_BASE      = 36;
        public const HASH_GENERATOR_POOL          = '0123456789abcdef';
        public const HASH_GENERATOR_POOL_BASE     = 16;

        public static function Explode(string $delimiter, string $string) :array
        {
            return ($string == '') ? [] : explode($delimiter, $string);
        }

        public static function GenerateString(int $length = 8, string $pool = self::STR_GENERATOR_POOL) :string
        {
            $str      = '';
            $poolSize = strlen($pool);

            for ($i = 0; $i < $length; $i++) {
                $str .= $pool[random_int(0, $poolSize - 1)];
            }

            return $str;
        }

        public static function PathFormat(string $path) :string
        {
            return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        }

        public static function PathJoin(...$args) :string
        {
            return implode($args, DIRECTORY_SEPARATOR);
        }

        public static function UUID4() :string
        {
            $data = openssl_random_pseudo_bytes(16);

            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        public static function Replace(string $search, string $replace, string $string, int $count = -1) :string
        {
            return preg_replace('~' . quotemeta($search) . '~su', $replace, $string, $count);
        }

        public static function UpFirstSymbol(string $string, bool $lowerOthers = true) :string
        {
            return $lowerOthers ? ucfirst(mb_strtolower($string)) : ucfirst($string);
        }

        public static function HashFromInt(int $num, int $seed = 5421087) :string
        {
            $l1 = ($num >> 16) & 0xffff;
            $r1 = $num & 0xffff;

            for ($i = 0; $i < 3; $i++) {
                $l2 = $r1;
                $r2 = $l1 ^ round((((3354245 * $r1 + 1352123418) % 37545514) / 0.357) * $seed);
                $l1 = $l2;
                $r1 = $r2;
            }

            $num = abs(($r1 << 16) + $l1);

            $res = '';

            while ($num) {
                $res .= substr(self::WIDE_STR_GENERATOR_POOL, ($num % self::WIDE_STR_GENERATOR_POOL_BASE) - 1, 1);
                $num = round($num / self::WIDE_STR_GENERATOR_POOL_BASE);
            }

            return $res;
        }
    }