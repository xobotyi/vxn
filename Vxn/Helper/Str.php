<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper;

    final class Str
    {
        public static function PathFormat(string $path) :string
        {
            return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        }
    }