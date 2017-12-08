<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Application\Routing;


    class Middleware
    {
        public const STAGE_PRE  = 'pre';
        public const STAGE_POST = 'post';
        private static $global = [
            'pre'  => [],
            'post' => [],
        ];
        private static $alias  = [];

        public static function RegisterPre($middleware) :bool
        {
            if (!is_array($middleware) && !is_callable($middleware)) {
                throw new \TypeError('$middleware must be a callable or variable referring to method or function');
            }

            self::$global['pre'][] = $middleware;

            return true;
        }

        public static function RegisterPost($middleware) :bool
        {
            if (!is_array($middleware) && !is_callable($middleware)) {
                throw new \TypeError('$middleware must be a callable or variable referring to method or function');
            }

            self::$global['post'][] = $middleware;

            return true;
        }

        public static function FireGlobal(string $stage) :bool
        {
            foreach (self::$global[$stage] as $middleware) {
                if (!call_user_func($middleware)) {
                    return false;
                }
            }

            return true;
        }

        public static function RegisterAlias(string $aliasName, array $middlewares) :bool
        {
            if (self::$alias[$aliasName]) {
                throw new \Error("alias '{$aliasName}' already exists");
            }

            self::$alias[$aliasName] = [];

            if (!$middlewares['pre'] && !$middlewares['post']) {
                foreach ($middlewares as $middleware) {
                    if (!is_array($middleware) && !is_callable($middleware)) {
                        throw new \TypeError('middleware must be a callable or variable referring to method or function');
                    }

                    self::$alias[$aliasName]['pre'][] = $middleware;
                }
            }
            else {
                if ($middlewares['pre']) {
                    foreach ($middlewares['pre'] as $middleware) {
                        if (!is_array($middleware) && !is_callable($middleware)) {
                            throw new \TypeError('middleware must be a callable or variable referring to method or function');
                        }

                        self::$alias[$aliasName]['pre'][] = $middleware;
                    }
                }
                if ($middlewares['post']) {
                    foreach ($middlewares['post'] as $middleware) {
                        if (!is_array($middleware) && !is_callable($middleware)) {
                            throw new \TypeError('middleware must be a callable or variable referring to method or function');
                        }

                        self::$alias[$aliasName]['post'][] = $middleware;
                    }
                }
            }

            return true;
        }

        public static function FireAlias(string $aliasName, string $stage)
        {
            $aliasName = explode(' ', trim($aliasName));

            foreach ($aliasName as $name) {
                if (!self::$alias[$name]) {
                    throw new \TypeError("Unknown middleware alias '{$name}'");
                }

                if (self::$alias[$name][$stage]) {
                    foreach (self::$alias[$name][$stage] as $middleware) {
                        call_user_func($middleware);
                    }
                }
            }

            return true;
        }
    }