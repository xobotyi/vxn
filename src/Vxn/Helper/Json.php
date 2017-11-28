<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper;

    final class Json
    {
        public static function Decode(string $json, bool $assoc = true, int $depth = 512, ?int $options = 0)
        {
            return json_decode($json, $assoc, $depth, $options);
        }

        public static function Encode(array $data, ?int $options = null, int $depth = 512) :string
        {
            $options = is_null($options) ? (JSON_NUMERIC_CHECK | JSON_HEX_APOS | JSON_HEX_QUOT) : $options;

            return $data ? json_encode($data, $options, $depth) : '[]';
        }

    }