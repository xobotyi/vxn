<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Cache;

    use Vxn\Core\Cfg;
    use Vxn\Core\Engine;
    use Vxn\Core\Log;

    class Memcache
    {
        private static $inited;

        /**
         * @var \Memcache
         */
        private static $instance;
        private static $ttlDefault = 60;
        private static $ttlMax     = 2592000;
        private static $ttlTags    = 604800;

        public static function Init(?string $host = null, ?int $port = null, ?int $ttlDefault = null, ?int $ttlMax = null) :void
        {
            if (!self::IsSupported()) {
                throw new \Exception('Memcache is not supported!');
            }

            if (self::$inited) {
                return;
            }

            self::$inited = true;

            $host             = $host ?: Cfg::Get('Vxn.cache.memcache.host');
            $port             = $port ?: Cfg::Get('Vxn.cache.memcache.port');
            self::$ttlDefault = $ttlDefault ?: Cfg::Get('Vxn.cache.memcache.ttl.default');
            self::$ttlMax     = $ttlMax ?: Cfg::Get('Vxn.cache.memcache.ttl.max');

            self::$instance = new \Memcache();

            if (!self::$instance->connect($host, $port)) {
                Log::Write('error',
                           "Unable to connect to memcache server at {$host}:{$port}",
                           "memcache",
                           Log::LEVEL_CRITICAL);
                throw new \Exception("Unable to connect to memcache server at {$host}:{$port}");
            }

            Engine::RegisterFinalShutdown(function () {
                self::CloseConnection();
            });
        }

        public static function IsSupported() :bool
        {
            return class_exists('Memcache');
        }

        public static function CloseConnection() :bool
        {
            return self::$instance ? self::$instance->close() : true;
        }

        public static function GetInstance() :?\Memcache
        {
            self::Init();

            return self::$instance;
        }

        public static function Flush() :bool
        {
            return self::GetInstance()->flush();
        }

        public static function tagGetVer($tag) :?int
        {
            return self::GetInstance()->get("\$vxnTag:{$tag}") ?: self::tagIncrementVer($tag);
        }

        public static function tagIncrementVer($tag)
        {
            if (!$tag) {
                return;
            }

            $time = microtime(true) * 10000;

            if (is_array($tag)) {
                foreach ($tag as $tagName) {
                    self::GetInstance()->set("\$vxnTag:{$tagName}", $time, null, self::$ttlTags);
                }
            }
            else if (is_string($tag) || is_numeric($tag)) {
                self::GetInstance()->set("\$vxnTag:{$tag}", $time, null, self::$ttlTags);

                return $time;
            }

            throw new \TypeError("'\$tag' expected to be an array, string or numeric, got " . gettype($tag));
        }

        public static function set(string $key, $data, ?int $ttl = null, array $tags = []) :bool
        {
            if ($tags) {
                foreach ($tags as $arrKey => $tagName) {
                    unset($tags[$arrKey]);

                    $tags[$tagName] = self::tagGetVer($tagName);
                }
            }

            $ttl    = $ttl === null ? self::$ttlDefault : ($ttl > self::$ttlMax ? self::$ttlMax : $ttl);
            $expire = $ttl ? time() + $ttl : 0;

            return self::GetInstance()->set("\$vxnKey:{$key}",
                                            [
                                                '$_data'   => $data,
                                                '$_tags'   => $tags,
                                                '$_ttl'    => $ttl,
                                                '$_expire' => $expire,
                                            ],
                                            null,
                                            $expire);
        }

        public static function add(string $key, $data, ?int $ttl = null, array $tags = []) :bool
        {
            if ($tags) {
                foreach ($tags as $arrKey => $tagName) {
                    unset($tags[$arrKey]);

                    $tags[$tagName] = self::tagGetVer($tagName);
                }
            }

            $ttl    = $ttl === null ? self::$ttlDefault : ($ttl > self::$ttlMax ? self::$ttlMax : $ttl);
            $expire = $ttl ? time() + $ttl : 0;

            return self::GetInstance()->add("\$vxnKey:{$key}",
                                            [
                                                '$_data'   => $data,
                                                '$_tags'   => $tags,
                                                '$_ttl'    => $ttl,
                                                '$_expire' => $expire,
                                            ],
                                            null,
                                            $expire);
        }

        public static function replace(string $key, $data, ?int $ttl = null, array $tags = []) :bool
        {
            if ($tags) {
                foreach ($tags as $arrKey => $tagName) {
                    unset($tags[$arrKey]);

                    $tags[$tagName] = self::tagGetVer($tagName);
                }
            }

            $ttl    = $ttl === null ? self::$ttlDefault : ($ttl > self::$ttlMax ? self::$ttlMax : $ttl);
            $expire = $ttl ? time() + $ttl : 0;

            return self::GetInstance()->replace("\$vxnKey:{$key}",
                                                [
                                                    '$_data'   => $data,
                                                    '$_tags'   => $tags,
                                                    '$_ttl'    => $ttl,
                                                    '$_expire' => $expire,
                                                ],
                                                null,
                                                $expire);
        }

        public static function prolong($key) :bool
        {
            if (($cacheValue = self::GetInstance()->get("\$vxnKey:{$key}")) === false) {
                return false;
            }

            foreach ($cacheValue['$_tags'] as $tagName => $ver) {
                if ($ver && $ver != self::tagGetVer($tagName)) {
                    self::GetInstance()->delete("\$vxnKey:{$key}");

                    return false;
                }
            }

            if (!$cacheValue['$_ttl']) {
                return true;
            }

            $cacheValue['$_expire'] = time() + $cacheValue['$_ttl'];

            return self::GetInstance()->set("\$vxnKey:{$key}",
                                            [
                                                '$_data'   => $cacheValue['$_data'],
                                                '$_tags'   => $cacheValue['$_tags'],
                                                '$_ttl'    => $cacheValue['$_ttl'],
                                                '$_expire' => $cacheValue['$_expire'],
                                            ],
                                            null,
                                            $cacheValue['$_expire']);
        }

        public static function delete($key)
        {
            if (is_array($key)) {
                foreach ($key as $item) {
                    self::GetInstance()->delete("\$vxnKey:{$item}");
                }

                return true;
            }
            else if (is_string($key) || is_numeric($key)) {
                return self::GetInstance()->delete("\$vxnKey:{$key}");
            }

            throw new \TypeError("'\$key' expected to be an array, string or numeric, got " . gettype($key));
        }

        public static function get(string $key, $default = false)
        {
            if (($cacheValue = self::GetInstance()->get("\$vxnKey:{$key}")) === false) {
                return $default;
            }

            foreach ($cacheValue['$_tags'] as $tagName => $ver) {
                if ($ver && $ver != self::tagGetVer($tagName)) {
                    self::GetInstance()->delete("\$vxnKey:{$key}");

                    return $default;
                }
            }

            return $cacheValue['$_data'];
        }

        public static function expire(string $key)
        {
            if (($cacheValue = self::GetInstance()->get("\$vxnKey:{$key}")) === false) {
                return false;
            }

            return $cacheValue['$_expire'];
        }

        public static function increment(string $key, int $amount = 1, bool $prolong = true) :bool
        {
            if (($cacheValue = self::GetInstance()->get("\$vxnKey:{$key}")) === false) {
                return false;
            }

            foreach ($cacheValue['$_tags'] as $tagName => $ver) {
                if ($ver && $ver != self::tagGetVer($tagName)) {
                    self::GetInstance()->delete("\$vxnKey:{$key}");

                    return false;
                }
            }

            $cacheValue['$_expire'] = ($prolong && $cacheValue['$_ttl']) ? (time() + $cacheValue['$_ttl']) : $cacheValue['$_expire'];

            return self::GetInstance()->set("\$vxnKey:{$key}",
                                            [
                                                '$_data'   => $cacheValue['$_data'] + $amount,
                                                '$_tags'   => $cacheValue['$_tags'],
                                                '$_ttl'    => $cacheValue['$_ttl'],
                                                '$_expire' => $cacheValue['$_expire'],
                                            ],
                                            null,
                                            $cacheValue['$_expire']);
        }

        public static function decrement(string $key, int $amount = 1, bool $prolong = true)
        {
            if (($cacheValue = self::GetInstance()->get("\$vxnKey:{$key}")) === false) {
                return false;
            }

            foreach ($cacheValue['$_tags'] as $tagName => $ver) {
                if ($ver && $ver != self::tagGetVer($tagName)) {
                    self::GetInstance()->delete("\$vxnKey:{$key}");

                    return false;
                }
            }

            $cacheValue['$_expire'] = ($prolong && $cacheValue['$_ttl']) ? (time() + $cacheValue['$_ttl']) : $cacheValue['$_expire'];

            return self::GetInstance()->set("\$vxnKey:{$key}",
                                            [
                                                '$_data'   => $cacheValue['$_data'] - $amount,
                                                '$_tags'   => $cacheValue['$_tags'],
                                                '$_ttl'    => $cacheValue['$_ttl'],
                                                '$_expire' => $cacheValue['$_expire'],
                                            ],
                                            null,
                                            $cacheValue['$_expire']);
        }

        public static function fill(string $key, callable $callback, ?int $ttl = null, array $tags = [], bool $prolong = false)
        {
            if (($data = self::get($key)) !== false) {
                if ($prolong) {
                    self::prolong($key);
                }

                return $data;
            }

            $data = null;

            $callback($data);

            self::set($key, $data, $ttl, $tags);

            return $data;
        }
    }