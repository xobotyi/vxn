<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Core;

    use Vxn\Helper\Str;
    use xobotyi\A;

    final class Cfg
    {
        private static $configs = [];

        public static function Get($name, $default = null)
        {
            return A::get(self::$configs, $name, $default);
        }

        public static function LoadPHP(string $cfgPath) :bool
        {
            $cfgPath = Str::PathFormat($cfgPath);
            if (!FS::IsFile($cfgPath)) {
                throw new \Error("{$cfgPath} not found");
            }

            $cfg = FS::Load($cfgPath, true, false);
            $cfg = is_array($cfg) ? $cfg : [];

            self::$configs = array_replace_recursive(self::$configs, $cfg);

            return true;
        }

        public static function LoadINI(string $cfgPath) :bool
        {
            $cfgPath = Str::PathFormat($cfgPath);
            if (!FS::IsFile($cfgPath)) {
                throw new \Error("{$cfgPath} not found");
            }

            $cfg = parse_ini_file($cfgPath);
            $cfg = is_array($cfg) ? $cfg : [];

            self::$configs = array_replace_recursive(self::$configs, $cfg);

            return true;
        }
    }