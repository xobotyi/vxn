<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Core;

    use Vxn\Helper\Str;
    use Vxn\Http\Request;

    final class Log
    {
        public const LEVEL_WARNING   = 'WARNING';
        public const LEVEL_EMERGENCY = 'EMERGENCY';
        public const LEVEL_CRITICAL  = 'CRITICAL';
        public const LEVEL_ALERT     = 'ALERT';
        public const LEVEL_INFO      = 'INFO';
        public const LEVEL_DEBUG     = 'DEBUG';
        public const LEVEL_NOTICE    = 'NOTICE';

        private static $logPath;

        public static function Write(string $logName, string $message, string $category = 'general', string $level = self::LEVEL_INFO) :bool
        {
            $path = self::$logPath ?: self::$logPath = Cfg::Get('App.path.log',
                                                                Autoloader::GetAutoloaderPath(VXN_RESERVED_NAMESPACE) . '/Log');

            FS::MkDir($path);

            return FS::Write(Str::PathJoin($path, "{$logName}.log"), self::Format($message, $category, $level), true);
        }

        public static function Format(string $message, string $category = 'general', string $level = self::LEVEL_INFO) :string
        {
            return date('Y-m-d H:i:s') . " [{$level}] [{$category}] " .
                   "[" . Request::UserIp() . "] " .
                   "[" . Request::Path() . "] " .
                   rtrim($message) . "\n";
        }

        public static function Clear(string $logName) :bool
        {
            $path = self::$logPath ?: self::$logPath = Cfg::Get('App.path.log',
                                                                Autoloader::GetAutoloaderPath(VXN_RESERVED_NAMESPACE) . '/Log');

            return FS::Delete(Str::PathJoin($path, "{$logName}.log"));
        }
    }