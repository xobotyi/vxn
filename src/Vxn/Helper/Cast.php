<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper;


    final class Cast
    {
        public static function ToArray($var) :array
        {
            return is_array($var) ? $var : [$var];
        }

        public static function ToFloat($value, int $precision = 0) :float
        {
            $float = 0;
            $value = settype($value, 'float');

            if ($value !== false) {
                $float = $value;
            }

            $float = round($float, self::ToInt($precision));

            return (float)$float;
        }

        public static function ToInt($value) :int
        {
            if (!is_numeric($value) && !is_bool($value)) {
                return 0;
            }

            return (int)$value;
        }

        public static function ToString($value) :string
        {
            return (string)$value;
        }
    }