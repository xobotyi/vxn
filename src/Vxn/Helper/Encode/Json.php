<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper\Encode;


    class Json implements EncoderInterface
    {
        public static function encode($data, ?int $options = null, int $depth = 512) :string
        {
            return json_encode($data,
                               is_null($options)
                                   ? (JSON_NUMERIC_CHECK
                                      | JSON_HEX_TAG
                                      | JSON_HEX_AMP
                                      | JSON_HEX_APOS
                                      | JSON_HEX_QUOT
                                      | JSON_UNESCAPED_UNICODE)
                                   : $options,
                               $depth);
        }

        public static function decode(string $jsonStr, bool $assoc = true, ?int $options = null, int $depth = 512)
        {
            return json_decode($jsonStr,
                               $assoc,
                               $depth,
                               $options);
        }
    }