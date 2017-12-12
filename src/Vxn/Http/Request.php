<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Http;

    use Vxn\Core\FS;
    use xobotyi\A;

    final class Request
    {
        private static $headers = [];

        private static $body = '';

        private static $dataRaw   = [];
        private static $dataRawCI = [];

        private static $dataDecoded   = [];
        private static $dataEncodedCI = [];

        private static $dataSafe = [];

        private static $uriData = [
            'protocol' => '',
            'domain'   => '',
            'port'     => 80,
            'path'     => '',
            'pathArr'  => [],
            'query'    => '',
            'queryArr' => [],
        ];

        private static $inited;

        public static function SafeGet(?string $name = null, $default = null, bool $caseInsensitive = false, bool $raw = false)
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

            self::$uriData['protocol'] = $_SERVER['HTTPS'] ?? false ? 'https://' : 'http://';
            self::$uriData['port']     = (int)($_SERVER['SERVER_PORT'] ?? 80);
            self::$uriData['domain']   = str_replace(':' . self::$uriData['port'], '', $_SERVER['HTTP_HOST'] ?? '');
            self::$uriData['query']    = $_SERVER['QUERY_STRING'] ?? '';
            self::$uriData['path']     = rtrim(str_replace("?" . self::$uriData['query'], '', $_SERVER['REQUEST_URI'] ?? ''), '/');

            parse_str(self::$uriData['query'], self::$uriData['queryArr']);
            self::$uriData['pathArr'] = explode('/', trim(self::$uriData['path'], '/'));

            // Encoded data fill
            self::$dataDecoded['get']     = $_GET;
            self::$dataDecoded['post']    = $_POST;
            self::$dataDecoded['request'] = array_replace(self::$dataDecoded['get'], self::$dataDecoded['post']);
            self::$dataDecoded['server']  = $_SERVER;
            self::$dataDecoded['cookie']  = $_COOKIE;

            // Raw data fill
            self::$dataRaw['get'] =
            self::$dataRaw['post'] =
            self::$dataRaw['request'] =
            self::$dataRaw['server'] =
            self::$dataRaw['cookie'] = [];

            foreach (self::$dataDecoded['server'] as $key => &$value) {
                if (substr($key, 0, 5) == 'HTTP_') {
                    self::$dataDecoded['headers'][substr($key, 5)] = &$value;
                }
            }

            if (self::IsPostMethod()) {
                self::$body = FS::Read('php://input');
                parse_str(self::$body, self::$dataRaw['post']);
            }

            if (self::$uriData['queryArr']) {
                self::$dataRaw['get'] = self::$uriData['queryArr'];
            }

            self::$dataRaw['request'] = array_merge(self::$dataRaw['get'], self::$dataRaw['post']);

            if (preg_match_all('/(\b[^;=]+)=([^;]+)?/i', self::$dataDecoded['server']['HTTP_COOKIE'] ?? '', self::$dataRaw['cookie'])) {
                self::$dataRaw['cookie'] = array_combine(self::$dataRaw['cookie'][1], self::$dataRaw['cookie'][2]);
            }

            self::$dataRawCI     = A::changeKeyCase(self::$dataRaw, CASE_LOWER, true);
            self::$dataEncodedCI = A::changeKeyCase(self::$dataDecoded, CASE_LOWER, true);
        }

        public static function IsPostMethod() :bool
        {
            self::Init();

            return self::GetRequestMethod() === 'POST';
        }

        public static function GetRequestMethod() :string
        {
            self::Init();

            return strtoupper(self::$dataDecoded['server']['REQUEST_METHOD'] ?? 'GET');
        }

        public static function Get(?string $name = null, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();

            if ($caseInsensitive) {
                return ($raw ? self::$dataRawCI['get'] : self::$dataEncodedCI['get'])[$name] ?? $default;
            }
            else {
                return ($raw ? self::$dataRaw['get'] : self::$dataDecoded['get'])[$name] ?? $default;
            }
        }

        public static function SafePost(?string $name = null, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();

            return strip_tags(self::Post($name, $default, $caseInsensitive, $raw));
        }

        public static function Post(?string $name = null, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();

            if ($caseInsensitive) {
                return ($raw ? self::$dataRawCI['post'] : self::$dataEncodedCI['post'])[$name] ?? $default;
            }
            else {
                return ($raw ? self::$dataRaw['post'] : self::$dataDecoded['post'])[$name] ?? $default;
            }
        }

        public static function SafeRequest(?string $name = null, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();

            return strip_tags(self::Request($name, $default, $caseInsensitive, $raw));
        }

        public static function Request(?string $name = null, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();

            if ($caseInsensitive) {
                return ($raw ? self::$dataRawCI['request'] : self::$dataEncodedCI['request'])[$name] ?? $default;
            }
            else {
                return ($raw ? self::$dataRaw['request'] : self::$dataDecoded['request'])[$name] ?? $default;
            }
        }

        public static function SafeServer(?string $name = null, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();

            return strip_tags(self::Server($name, $default, $caseInsensitive, $raw));
        }

        public static function Server(?string $name = null, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();


            if ($caseInsensitive) {
                return ($raw ? self::$dataRawCI['server'] : self::$dataEncodedCI['server'])[$name] ?? $default;
            }
            else {
                return ($raw ? self::$dataRaw['server'] : self::$dataDecoded['server'])[$name] ?? $default;
            }
        }

        public static function SafeCookie(?string $name = null, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();

            return strip_tags(self::Cookie($name, $default, $caseInsensitive, $raw));
        }

        public static function Cookie(?string $name = null, $default = null, bool $caseInsensitive = false, bool $raw = false)
        {
            self::Init();


            if ($caseInsensitive) {
                return ($raw ? self::$dataRawCI['cookie'] : self::$dataEncodedCI['cookie'])[$name] ?? $default;
            }
            else {
                return ($raw ? self::$dataRaw['cookie'] : self::$dataDecoded['cookie'])[$name] ?? $default;
            }
        }

        public static function SafeHeader(?string $name = null, $default = null, bool $caseInsensitive = false)
        {
            self::Init();

            return strip_tags(self::Header($name, $default, $caseInsensitive));
        }

        public static function Header(?string $name = null, $default = null, bool $caseInsensitive = false)
        {
            self::Init();

            return ($caseInsensitive ? self::$dataEncodedCI['headers'] : self::$dataDecoded['headers'])[$name] ?? $default;
        }

        public static function Body() :string
        {
            self::Init();

            return self::$body;
        }

        public static function IsGetMethod() :bool
        {
            self::Init();

            return self::GetRequestMethod() === 'GET';
        }

        public static function IsDeleteMethod() :bool
        {
            self::Init();

            return self::GetRequestMethod() === 'DELETE';
        }

        public static function IsPutMethod() :bool
        {
            self::Init();

            return self::GetRequestMethod() === 'PUT';
        }

        public static function IsAjax() :bool
        {
            self::Init();

            return (bool)(self::Server('HTTP_X_REQUESTED_WITH', '') === 'XMLHttpRequest');
        }

        public static function Path(bool $array = false)
        {
            self::Init();

            return $array ? self::$uriData['pathArr'] ?? [] : self::$uriData['path'] ?? '';
        }

        public static function Query(bool $array = false)
        {
            self::Init();

            return $array ? self::$uriData['queryArr'] ?? [] : self::$uriData['query'] ?? '';
        }

        public static function Uri(bool $port = false, bool $trailSlash = false) :string
        {
            self::Init();

            return self::Protocol()
                   . self::Domain()
                   . ($port ? self::Port() : '')
                   . self::$uriData['path'] . ($trailSlash ? '/' : '')
                   . (self::$uriData['query'] ? '?' . self::$uriData['query'] : '');
        }

        public static function Protocol() :string
        {
            self::Init();

            return self::$uriData['protocol'] ?? 'http://';
        }

        public static function Domain() :string
        {
            self::Init();

            return self::$uriData['domain'] ?? 'localhost';
        }

        public static function Port() :int
        {
            self::Init();

            return self::$uriData['port'] ?? 80;
        }

        public static function Referrer() :string
        {
            return self::Server('HTTP_REFERER', '');
        }

        public static function UserHost() :string
        {
            return self::Server('REMOTE_HOST', '');
        }

        public static function UserAgent() :string
        {
            return self::Server('HTTP_USER_AGENT', '');
        }

        public static function UserIp() :string
        {
            return self::Server('REMOTE_ADDR', '127.0.0.1');
        }
    }