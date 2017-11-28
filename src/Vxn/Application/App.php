<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Application;


    use Vxn\Core\Cfg;
    use Vxn\Core\FS;

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

            FS::Load(Cfg::Get('App.path.app') . '/middleware.php');
            FS::Load(Cfg::Get('App.path.app') . '/routes.php');
        }
    }