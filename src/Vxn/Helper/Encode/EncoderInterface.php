<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper\Encode;


    interface EncoderInterface
    {
        public static function encode($var);

        public static function decode(string $var);
    }