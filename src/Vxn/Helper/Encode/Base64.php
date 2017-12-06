<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper\Encode;


    class Base64 implements EncoderInterface
    {
        public static function encode($var, bool $urlUnsafe = false) :string
        {
            return $urlUnsafe ? base64_encode($var) : strtr(base64_encode($var), '+/=', '-_~');
        }

        public static function decode(string $var)
        {
            return base64_decode(strtr($var, '- _~', '++/='));
        }
    }