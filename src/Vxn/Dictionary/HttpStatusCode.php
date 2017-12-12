<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Dictionary;

    final class HttpStatusCode
    {
        // 2xx Information
        public const STATUS_100 = 100;
        public const STATUS_101 = 101;

        // 2xx Success
        public const STATUS_200 = 200;
        public const STATUS_201 = 201;
        public const STATUS_202 = 202;
        public const STATUS_203 = 203;
        public const STATUS_204 = 204;
        public const STATUS_205 = 205;
        public const STATUS_206 = 206;
        public const STATUS_207 = 207;
        public const STATUS_226 = 226;

        // 3xx Redirection
        public const STATUS_300 = 300;
        public const STATUS_301 = 301;
        public const STATUS_302 = 302;
        public const STATUS_303 = 303;
        public const STATUS_304 = 304;
        public const STATUS_305 = 305;
        public const STATUS_306 = 306;
        public const STATUS_307 = 307;

        // 4xx Client errors
        public const STATUS_400 = 400;
        public const STATUS_401 = 401;
        public const STATUS_402 = 402;
        public const STATUS_403 = 403;
        public const STATUS_404 = 404;
        public const STATUS_405 = 405;
        public const STATUS_406 = 406;
        public const STATUS_407 = 407;
        public const STATUS_408 = 408;
        public const STATUS_409 = 409;
        public const STATUS_410 = 410;
        public const STATUS_411 = 411;
        public const STATUS_412 = 412;
        public const STATUS_413 = 413;
        public const STATUS_414 = 414;
        public const STATUS_415 = 415;
        public const STATUS_416 = 416;
        public const STATUS_417 = 417;
        public const STATUS_422 = 422;
        public const STATUS_423 = 423;
        public const STATUS_424 = 424;
        public const STATUS_425 = 425;
        public const STATUS_426 = 426;
        public const STATUS_428 = 428;
        public const STATUS_429 = 429;
        public const STATUS_431 = 431;
        public const STATUS_434 = 434;
        public const STATUS_449 = 449;
        public const STATUS_451 = 451;

        // 5xx Server errors
        public const STATUS_500 = 500;
        public const STATUS_501 = 501;
        public const STATUS_502 = 502;
        public const STATUS_503 = 503;
        public const STATUS_504 = 504;
        public const STATUS_505 = 505;
        public const STATUS_506 = 506;
        public const STATUS_507 = 507;
        public const STATUS_508 = 508;
        public const STATUS_509 = 509;
        public const STATUS_510 = 510;
        public const STATUS_511 = 511;

        private static $statusList = [
            self::STATUS_100,
            self::STATUS_101,
            self::STATUS_200,
            self::STATUS_201,
            self::STATUS_202,
            self::STATUS_203,
            self::STATUS_204,
            self::STATUS_205,
            self::STATUS_206,
            self::STATUS_207,
            self::STATUS_226,
            self::STATUS_300,
            self::STATUS_301,
            self::STATUS_302,
            self::STATUS_303,
            self::STATUS_304,
            self::STATUS_305,
            self::STATUS_306,
            self::STATUS_307,
            self::STATUS_400,
            self::STATUS_401,
            self::STATUS_402,
            self::STATUS_403,
            self::STATUS_404,
            self::STATUS_405,
            self::STATUS_406,
            self::STATUS_407,
            self::STATUS_408,
            self::STATUS_409,
            self::STATUS_410,
            self::STATUS_411,
            self::STATUS_412,
            self::STATUS_413,
            self::STATUS_414,
            self::STATUS_415,
            self::STATUS_416,
            self::STATUS_417,
            self::STATUS_422,
            self::STATUS_423,
            self::STATUS_424,
            self::STATUS_425,
            self::STATUS_426,
            self::STATUS_428,
            self::STATUS_429,
            self::STATUS_431,
            self::STATUS_434,
            self::STATUS_449,
            self::STATUS_451,
            self::STATUS_500,
            self::STATUS_501,
            self::STATUS_502,
            self::STATUS_503,
            self::STATUS_504,
            self::STATUS_505,
            self::STATUS_506,
            self::STATUS_507,
            self::STATUS_508,
            self::STATUS_509,
            self::STATUS_510,
            self::STATUS_511,
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

        public static function GetName($status) :?string
        {
            return self::$statusName[$status] ?? null;
        }

        public static function IsSupported($status) :bool
        {
            return in_array($status, self::$statusList);
        }

        public static function GetList() :array
        {
            return self::$statusList;
        }
    }