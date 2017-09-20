<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Http;

    use Vxn\Core\FS;
    use Vxn\Helper\Arr;

    final class Request
    {
        private static $server = [];

        private static $headers = [];

        private static $body = '';

        private static $dataRaw = [];

        private static $dataEncoded = [];

        private static $CIKeys = [];

        private static $dataSafe = [];

        private static $inited;

        private static function Init()
        {
            if (self::$inited) {
                return;
            }

            self::$inited = true;

            self::$server = $_SERVER;

            self::$dataEncoded['get']     = $_GET;
            self::$dataEncoded['post']    = $_POST;
            self::$dataEncoded['request'] = $_COOKIE;
            self::$dataEncoded['cookie']  = array_replace(self::$dataEncoded['get'], self::$dataEncoded['post']);

            self::$dataRaw['get']     = [];
            self::$dataRaw['post']    = [];
            self::$dataRaw['request'] = [];
            self::$dataRaw['cookie']  = [];

            if (self::IsPostMethod()) {
                self::$body = FS::Read('php://input');
                parse_str(self::$body, self::$dataRaw['post']);
            }
        }

        public static function Body() :string
        {
            self::Init();

            return self::$body;
        }

        public static function Uri() :string
        {
            self::Init();

            return self::$server['REQUEST_URI'] ?? '';
        }

        public static function Path() :string
        {
            self::Init();

            return str_replace("?" . self::Query(), '', self::Uri());
        }

        public static function Query() :string
        {
            self::Init();

            return self::$server['QUERY_STRING'] ?? '';
        }

        public static function AcceptTypes() :string
        {
            self::Init();

            return self::$server['HTTP_ACCEPT'] ?? '';
        }

        public static function Referrer() :string
        {
            return self::$server['HTTP_REFERER'] ?? '';
        }

        public static function IsAjax() :bool
        {
            self::Init();

            return (self::$server['HTTP_X_REQUESTED_WITH'] ?? false) === 'XMLHttpRequest';
        }

        public static function GetRequestMethod() :string
        {
            self::Init();

            return strtoupper(self::$server['REQUEST_METHOD'] ?? 'GET');
        }

        public static function HostName(bool $port = true) :string
        {
            self::Init();

            $host = self::$server['HTTP_HOST'] ?? '';

            return $port ? $host : str_replace(':' . (self::$server['SERVER_PORT'] ?? '80'), '', $host);
        }

        public static function IsPostMethod() :bool
        {
            self::Init();

            return self::GetRequestMethod() === 'POST';
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

        public static function Server(?string $name = null)
        {
            self::Init();

            return Arr::Get($name, self::$server);
        }
    }