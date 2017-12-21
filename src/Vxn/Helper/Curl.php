<?php
    /**
     * @Author  Anton Zinovyev
     */

    declare(strict_types = 1);

    namespace Vxn\Helper;


    use Vxn\Core\Cfg;

    class Curl
    {
        private static $inited = false;

        private static $defaultOptions = [];

        private static function Init()
        {
            if (self::$inited) {
                return;
            }

            self::$inited = true;

            self::$defaultOptions[CURLOPT_USERAGENT]      = Cfg::Get('App.curl.userAgent', 'VXN/cURLClient');
            self::$defaultOptions[CURLOPT_SSL_VERIFYPEER] = Cfg::Get('App.curl.sslVerifyPeer', false);
            self::$defaultOptions[CURLOPT_SSL_VERIFYHOST] = Cfg::Get('App.curl.sslVerifyHost', false);
        }

        public static function IsSupported() :bool
        {
            return function_exists('\curl_init()');
        }

        public static function POST(string $uri, $data, array $headers = [], array $options = []) :array
        {
            self::Init();

            $curl = curl_init();

            $options[CURLOPT_URL] = $uri;

            $options[CURLOPT_RETURNTRANSFER] = 1;
            $options[CURLOPT_VERBOSE]        = 1;
            $options[CURLOPT_HEADER]         = 1;
            $options[CURLOPT_FOLLOWLOCATION] = 1;
            $options[CURLOPT_POST]           = 1;

            if (!empty($headers)) {
                $options[CURLOPT_HTTPHEADER] = [];
                foreach ($headers as $header => &$value) {
                    $options[CURLOPT_HTTPHEADER][] = $header . ': ' . $value;
                }
            }

            if (!empty($data)) {
                if (is_array($data)) {
                    $options[CURLOPT_POSTFIELDS] = http_build_query($data);
                }
                else if (is_string($data)) {
                    $options[CURLOPT_POSTFIELDS] = $data;
                }
                else {
                    throw new \TypeError("Parameter 2 expected to be array or string, got " . gettype($data));
                }
            }

            curl_setopt_array($curl, array_replace(self::$defaultOptions, $options));

            $result      = curl_exec($curl);
            $headersSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

            return [
                'status'  => curl_getinfo($curl, CURLINFO_HTTP_CODE),
                'headers' => $result ? substr($result, 0, $headersSize) : '',
                'body'    => $result ? substr($result, $headersSize) : '',
            ];
        }

        public static function GET(string $uri, array $headers = [], array $options = [])
        {
            self::Init();

            $curl = curl_init();

            $options[CURLOPT_URL] = $uri;

            $options[CURLOPT_RETURNTRANSFER] = 1;
            $options[CURLOPT_VERBOSE]        = 1;
            $options[CURLOPT_HEADER]         = 1;
            $options[CURLOPT_FOLLOWLOCATION] = 1;

            if (!empty($headers)) {
                $options[CURLOPT_HTTPHEADER] = [];
                foreach ($headers as $header => &$value) {
                    $options[CURLOPT_HTTPHEADER][] = $header . ': ' . $value;
                }
            }

            curl_setopt_array($curl, array_merge(self::$defaultOptions, $options));

            $result      = curl_exec($curl);
            $headersSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

            return [
                'status'  => curl_getinfo($curl, CURLINFO_HTTP_CODE),
                'headers' => $result ? substr($result, 0, $headersSize) : '',
                'body'    => $result ? substr($result, $headersSize) : '',
            ];
        }
    }