<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Dictionary;

    use Vxn\Helper\Arr;

    final class HttpStatusCode
    {
        // 2xx Success
        public const STATUS_200 = '200';
        public const STATUS_201 = '201';
        public const STATUS_202 = '202';
        public const STATUS_204 = '204';

        // 3xx Redirection
        public const STATUS_301 = '301';
        public const STATUS_302 = '302';
        public const STATUS_303 = '303';
        public const STATUS_304 = '304';
        public const STATUS_307 = '307';

        // 4xx Client errors
        public const STATUS_400 = '400';
        public const STATUS_401 = '401';
        public const STATUS_403 = '403';
        public const STATUS_404 = '404';
        public const STATUS_410 = '410';

        // 5xx Server errors
        public const STATUS_500 = '500';
        public const STATUS_503 = '503';

        private static $statusList = [
            self::STATUS_200,
            self::STATUS_201,
            self::STATUS_202,
            self::STATUS_204,
            self::STATUS_301,
            self::STATUS_302,
            self::STATUS_303,
            self::STATUS_304,
            self::STATUS_307,
            self::STATUS_400,
            self::STATUS_401,
            self::STATUS_403,
            self::STATUS_404,
            self::STATUS_500,
            self::STATUS_503,
        ];

        private static $statusName = [
            #1xx: Informational
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',

            #2xx: Success
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            226 => 'IM Used',

            #3xx: Redirection
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '', #Reserved
            307 => 'Temporary Redirect',

            #4xx: Client Error
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed ',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            425 => 'Unordered Collection',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            434 => 'Requested host unavailable',
            449 => 'Retry With',
            451 => 'Unavailable For Legal Reasons',

            #5xx: Server Error
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended',
            511 => 'Network Authentication Required',
        ];

        public static function GetStatusName($status) :?string
        {
            return Arr::Get($status, self::$statusName);
        }

        public static function GetList() :array
        {
            return self::$statusList ?: [];
        }

        public static function IsSupported($type) :bool
        {
            return in_array($type, self::$statusList);
        }
    }