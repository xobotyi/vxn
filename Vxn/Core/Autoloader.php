<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Core;

    use Vxn\Helper\Str;

    define('VXN_RESERVED_NAMESPACE', 'Vxn');

    final class Autoloader
    {
        private static $autoloaders = [];

        private static $inited;

        public static function Init() :void
        {
            if (self::$inited) {
                return;
            }

            self::$inited = true;

            mb_internal_encoding("UTF-8");

            $vxn_path = realpath(__DIR__ . '/../../');

            include_once $vxn_path . '/Vxn/Helper/Str.php';
            include_once $vxn_path . '/Vxn/Core/FS.php';

            self::$autoloaders[VXN_RESERVED_NAMESPACE] = $vxn_path;

            spl_autoload_register('\\Vxn\\Core\\Autoloader::Load', true, true);
        }

        public static function Load(string $ns) :bool
        {
            if (!$ns || !is_string($ns)) {
                return false;
            }

            $mainNS = explode('\\', trim($ns, "\\ "))[0];
            if (!($mainPath = self::GetAutoloaderPath($mainNS))) {
                return false;
            }

            return FS::Load($mainPath . DIRECTORY_SEPARATOR . trim(Str::PathFormat($ns), "\\/") . '.php');
        }

        public static function GetAutoloaderPath(string $ns)
        {
            return self::$autoloaders[$ns] ?? false;
        }

        public static function Register(string $ns, string $path)
        {
            if (is_string($ns)) {
                $ns = [$ns];
            }

            if (is_array($ns)) {
                $path = realpath($path);
                foreach ($ns as $item) {
                    if ($item != VXN_RESERVED_NAMESPACE) {
                        self::$autoloaders[$ns] = $path;
                    }
                }
            }

            throw new \TypeError('Argument 1 must be of type array or integer, ' . gettype($ns) . 'given');
        }
    }