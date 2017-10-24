<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Cache;

    use Vxn\Core\Cfg;
    use Vxn\Core\Engine;
    use Vxn\Core\Log;

    /**
     * Class Memcache
     *
     * @package Vxn\Cache
     */
    class Memcache
    {
        /**
         * @var array
         */
        private static $tagsBuffer = [];

        /**
         * @param string $tag
         *
         * @return int|null
         * @throws \Exception
         * @throws \TypeError
         */
        public static function tagGetVer(string $tag) :?int
        {
            if (!self::$useTagsBuffer || !($ver = (self::$tagsBuffer[$tag] ?? null))) {
                $ver = self::$tagsBuffer[$tag] = self::GetInstance()
                                                     ->get("\$vxnTag:{$tag}") ?: self::tagIncrementVer($tag);
            }

            return $ver ?: null;
        }

        /**
         * @param $tag
         *
         * @return bool|int|mixed
         * @throws \Exception
         * @throws \TypeError
         */
        public static function tagIncrementVer($tag)
        {
            if (!$tag) {
                return false;
            }

            $time = microtime(true) * 10000;

            if (is_array($tag)) {
                foreach ($tag as $tagName) {
                    self::GetInstance()->set("\$vxnTag:{$tagName}", $time, null, self::$ttlTags);
                    if (self::$useTagsBuffer) {
                        self::$tagsBuffer[$tagName] = $time;
                    }
                }

                return true;
            }
            else if (is_string($tag) || is_numeric($tag)) {
                self::GetInstance()->set("\$vxnTag:{$tag}", $time, null, self::$ttlTags);
                if (self::$useTagsBuffer) {
                    self::$tagsBuffer[$tag] = $time;
                }

                return $time;
            }

            throw new \TypeError("'\$tag' expected to be an array, string or numeric, got " . gettype($tag));
        }

        /**
         * @var bool
         */
        private static $inited = false;

        /**
         * @var bool
         */
        private static $useTagsBuffer = true;

        /**
         * @var \Memcache
         */
        private static $instance;

        /**
         * @var int
         */
        private static $ttlDefault = 60;
        /**
         * @var int
         */
        private static $ttlMax = 2592000;
        /**
         * @var int
         */
        private static $ttlTags = 604800;

        /**
         * @param null|string $host
         * @param int|null    $port
         * @param int|null    $ttlDefault
         * @param int|null    $ttlMax
         * @param int|null    $ttlTags
         * @param bool|null   $useTagsBuffer
         *
         * @throws \Exception
         */
        public static function Init(?string $host = null, ?int $port = null, ?int $ttlDefault = null, ?int $ttlMax = null, ?int $ttlTags = null, ?bool $useTagsBuffer = null) :void
        {
            if (!self::IsSupported()) {
                throw new \Exception('Memcache is not supported!');
            }

            if (self::$inited) {
                return;
            }

            self::$inited = true;

            $host = $host ?: Cfg::Get('Vxn.cache.memcache.host');
            $port = $port ?: Cfg::Get('Vxn.cache.memcache.port');

            self::$ttlDefault    = $ttlDefault ?: Cfg::Get('Vxn.cache.memcache.ttl.default');
            self::$ttlMax        = $ttlMax ?: Cfg::Get('Vxn.cache.memcache.ttl.max');
            self::$ttlTags       = $ttlTags ?: Cfg::Get('Vxn.cache.memcache.ttl.tags');
            self::$useTagsBuffer = $useTagsBuffer ?: Cfg::Get('Vxn.cache.memcache.useTagsBuffer');

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

        /**
         * @return bool
         */
        public static function IsSupported() :bool
        {
            return class_exists('Memcache');
        }

        /**
         * @return bool
         */
        public static function CloseConnection() :bool
        {
            if (self::$instance) {
                self::$instance->close();
                self::$instance = null;
            }

            return true;
        }

        /**
         * @return \Memcache|null
         * @throws \Exception
         */
        public static function GetInstance() :?\Memcache
        {
            self::Init();

            return self::$instance;
        }

        /**
         * @return bool
         * @throws \Exception
         */
        public static function Flush() :bool
        {
            return self::GetInstance()->flush();
        }

        /**
         * @param string   $key
         * @param          $data
         * @param int|null $ttl
         * @param array    $tags
         *
         * @return bool
         * @throws \Exception
         * @throws \TypeError
         */
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

        /**
         * @param string   $key
         * @param          $data
         * @param int|null $ttl
         * @param array    $tags
         *
         * @return bool
         * @throws \Exception
         * @throws \TypeError
         */
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

        /**
         * @param string   $key
         * @param          $data
         * @param int|null $ttl
         * @param array    $tags
         *
         * @return bool
         * @throws \Exception
         * @throws \TypeError
         */
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

        /**
         * @param $key
         *
         * @return bool
         * @throws \Exception
         * @throws \TypeError
         */
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

        /**
         * @param $key
         *
         * @return bool
         * @throws \Exception
         * @throws \TypeError
         */
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

        /**
         * @param string $key
         * @param bool   $default
         *
         * @return bool
         * @throws \Exception
         * @throws \TypeError
         */
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

        /**
         * @param string $key
         *
         * @return bool
         * @throws \Exception
         */
        public static function expire(string $key)
        {
            if (($cacheValue = self::GetInstance()->get("\$vxnKey:{$key}")) === false) {
                return false;
            }

            return $cacheValue['$_expire'];
        }

        /**
         * @param string $key
         * @param int    $amount
         * @param bool   $prolong
         *
         * @return bool
         * @throws \Exception
         * @throws \TypeError
         */
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

        /**
         * @param string $key
         * @param int    $amount
         * @param bool   $prolong
         *
         * @return bool
         * @throws \Exception
         * @throws \TypeError
         */
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

        /**
         * @param string   $key
         * @param callable $callback
         * @param int|null $ttl
         * @param array    $tags
         * @param bool     $prolong
         *
         * @return bool|null
         * @throws \Exception
         * @throws \TypeError
         */
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