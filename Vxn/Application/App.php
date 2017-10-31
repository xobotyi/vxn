<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Application;


    class App
    {
        /**
         * @var bool
         */
        private static $inited = false;

        public static function Init()
        {
            if (self::$inited) {
                return;
            }

            self::$inited = true;
        }
    }