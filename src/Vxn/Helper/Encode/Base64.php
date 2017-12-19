<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper\Encode;


    class Base64 implements EncoderInterface
    {
        public static function encode($var, bool $urlSafe = false) :string
        {
            return $urlSafe ? strtr(base64_encode($var), '+/=', '-_~') : base64_encode($var);
        }

        public static function decode(string $var)
        {
            return base64_decode(strtr($var, '- _~', '++/='));
        }
    }