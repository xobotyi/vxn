<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Core;

    final class Engine
    {
        private static $inited;

        private static $shutdowns        = [];
        private static $shutdownsDelayed = [];
        private static $shutdownsFinal   = [];

        public static function Init() :void
        {
            if (self::$inited) {
                return;
            }

            self::$inited = true;

            if (version_compare(PHP_VERSION, '7.1.0') < 0) {
                throw new \Exception("Required PHP 7.1.0 or newer!");
            }

            date_default_timezone_set('UTC');

            register_shutdown_function(function () {
                foreach (self::$shutdowns as $cb) {
                    $cb();
                }

                if (self::$shutdownsDelayed) {
                    if (PHP_SAPI == 'fpm-fcgi' && function_exists('fastcgi_finish_request')) {
                        fastcgi_finish_request();
                    }
                    else {
                        flush();
                        while (ob_get_level()) {
                            ob_end_flush();
                        }
                    }

                    foreach (self::$shutdownsDelayed as $cb) {
                        $cb();
                    }
                }

                if (self::$shutdownsFinal) {
                    foreach (self::$shutdownsFinal as $cb) {
                        $cb();
                    }
                }
            });
        }

        public static function RegisterShutdown(callable $callback) :void
        {
            self::$shutdowns[] = $callback;
        }

        public static function RegisterDelayedShutdown(callable $callback) :void
        {
            self::$shutdownsDelayed[] = $callback;
        }

        public static function RegisterFinalShutdown(callable $callback) :void
        {
            self::$shutdownsFinal[] = $callback;
        }

        public static function IsCLI() :bool
        {
            return PHP_SAPI == 'cli';
        }
    }