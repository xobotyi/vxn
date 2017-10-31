<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Http;

    use Vxn\Helper\Arr;

    final class Cookie
    {
        private static $data   = [];
        private static $dataCI = [];

        private static $dataRaw   = [];
        private static $dataRawCI = [];

        private static $inited;

        public static function SafeGet(string $name, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();

            return strip_tags(self::Get($name, $default, $caseInsensitive, $raw));
        }

        private static function Init()
        {
            if (self::$inited) {
                return;
            }

            self::$inited = true;

            self::$data    = Request::Cookie(null, null, false, false);
            self::$dataRaw = Request::Cookie(null, null, false, true);

            $crawler = function (array &$array, array &$result) use (&$crawler) {
                foreach ($array as $key => &$value) {
                    $keyLower = strtolower($key);

                    if (is_array($value)) {
                        $result[$keyLower] = [];
                        $crawler($value, $result[$keyLower]);
                    }
                    else {
                        $result[$keyLower] = &$value;
                    }
                }
            };

            $crawler(self::$data, self::$dataCI);
            $crawler(self::$dataRaw, self::$dataRawCI);
        }

        public static function Get(string $name, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();

            if ($caseInsensitive) {
                return Arr::Get($name, $raw ? self::$dataRawCI : self::$dataCI, $default);
            }
            else {
                return Arr::Get($name, $raw ? self::$dataRaw : self::$data, $default);
            }
        }

        public static function Set(string $name, string $value, int $ttl = 0, ?string $path = null, ?string $domain = null, bool $secure = false, $httpOnly = true) :bool
        {
            self::Init();

            if (is_null($value)) {
                return false;
            }

            self::$data[$name]               = $value;
            self::$dataCI[strtolower($name)] = &self::$data[$name];

            return setcookie($name,
                             $value,
                             $ttl ? time() + $ttl : 0,
                             $path ?: '/',
                             $domain ?: Request::Domain(),
                             $secure,
                             $httpOnly
            );
        }

        public static function Delete(string $name, ?string $path = null, ?string $domain = null)
        {
            self::Init();

            unset(self::$data[$name]);
            unset(self::$dataCI[strtolower($name)]);

            return setcookie($name,
                             null,
                             time() - 3600,
                             $path ?: '/',
                             $domain ?: Request::Domain());
        }
    }