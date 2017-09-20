<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper;


    class Cast
    {
        public static function ToArray($var) :array
        {
            return is_array($var) ? $var : [$var];
        }
    }