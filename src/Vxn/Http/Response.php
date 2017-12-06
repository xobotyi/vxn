<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Http;

    use Vxn\Dictionary\HttpStatusCode;

    final class Response
    {
        /**
         * @var string
         */
        private static $status = HttpStatusCode::STATUS_200;

        /**
         * @var array
         */
        private static $headers = [];

        /**
         * @return int
         */
        public static function GetStatus() :int
        {
            return self::$status;
        }

        /**
         * @param int  $status
         * @param bool $exit
         *
         * @throws \Error
         */
        public static function SetStatus(int $status, bool $exit = false) :void
        {
            if (!HttpStatusCode::IsSupported($status)) {
                throw new \Error("Status code {$status} is not supported");
            }

            self::$status = $status;
            self::AddHeader('Status', $status);

            if ($exit) {
                self::ApplyHeaders();
                exit();
            }
        }

        /**
         * @param array $names
         * @param bool  $immediately
         *
         * @throws \Error
         */
        public static function RemoveGetParameter(array $names = [], bool $immediately = false) :void
        {
            if (!$names || !($query = Request::Get())) {
                return;
            }

            $path           = Request::Path();
            $redirectNeeded = false;
            foreach ($names as $param) {
                if (isset($query[$param])) {
                    $redirectNeeded = true;
                    unset($query[$param]);
                }
            }

            if ($redirectNeeded) {
                self::Redirect($query ? "{$path}?" . http_build_query($query) : $path, $immediately);
            }
        }

        /**
         * @param string $uri
         * @param bool   $immediately
         *
         * @throws \Error
         */
        public static function Redirect(string $uri = '/', bool $immediately = false) :void
        {
            self::AddNoCacheHeaders();
            self::SetStatus(HttpStatusCode::STATUS_302);
            self::AddHeader('Location', $uri);

            if ($immediately) {
                self::ApplyHeaders();
                exit();
            }
        }

        /**
         *
         */
        public static function AddNoCacheHeaders() :void
        {
            self::AddHeader('Cache-Control', 'no-cache,no-store,max-age=0,must-revalidate');
            self::AddHeader('Expires', date('r', time() - 3600));
            self::AddHeader('Pragma', 'no-cache');
        }

        /**
         * @param string $name
         * @param string $value
         * @param bool   $replace
         */
        public static function AddHeader(string $name, string $value, bool $replace = true) :void
        {
            if ($replace) {
                self::$headers[] = ['name' => $name, 'value' => $value, 'replace' => $replace];
            }
        }

        /**
         * @param string $name
         */
        public static function RemoveHeader(string $name) :void
        {
            self::$headers = array_filter(self::$headers, function ($header) use (&$name) {
                return $header['name'] !== $name;
            });
            header_remove($name);
        }

        /**
         *
         */
        public static function ApplyHeaders() :void
        {
            foreach (self::$headers as $header) {
                header("{$header['name']}: {$header['value']}", $header['replace']);
            }
        }

        /**
         * @param $type
         */
        public static function SetContentType($type)
        {
            self::AddHeader('Content-Type', $type . '; charset=UTF-8');
        }

        /**
         * @param string $uri
         * @param bool   $immediately
         *
         * @throws \Error
         */
        public static function RedirectPermanently(string $uri = '/', bool $immediately = false) :void
        {
            self::SetStatus(HttpStatusCode::STATUS_301);
            self::AddHeader('Location', urlencode($uri));

            if ($immediately) {
                self::ApplyHeaders();
                exit();
            }
        }
    }