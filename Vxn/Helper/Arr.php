<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper;

    final class Arr
    {
        public static function Check($key, $array) :bool
        {
            if (!is_array($array)) {
                return false;
            }
            else if ($key === null || $key === false || $key === '') {
                return false;
            }
            else if (!$array) {
                return false;
            }

            $path = preg_split("~(?<!\\\\)\.~", $key);

            if (!isset($path[1])) {
                return isset($array[stripslashes($path[0])]) || array_key_exists(stripslashes($path[0]), $array);
            }

            $lastKey = array_pop($path);
            $scope   = &$array;

            while (!is_null($nodeName = array_shift($path))) {
                $nodeName = stripslashes($nodeName);

                if (!isset($scope[$nodeName]) || !is_array($scope[$nodeName])) {
                    return false;
                }

                $scope = &$scope[$nodeName];
            }

            if ($lastKey === '*') {
                return (bool)count($scope);
            }

            return isset($scope[stripslashes($lastKey)]) || array_key_exists(stripslashes($lastKey), $scope);
        }

        public static function Get($key, $array, $default = null)
        {
            if (!is_array($array)) {
                return $default;
            }
            else if ($key === null || $key === false || $key === '') {
                return $array;
            }
            else if (!$array) {
                return $default;
            }

            $path = preg_split("~(?<!\\\\)\.~", $key);

            if (!isset($path[1])) {
                return $array[stripslashes($path[0])] ?? $default;
            }

            $lastKey = array_pop($path);
            $scope   = &$array;

            while (!is_null($nodeName = array_shift($path))) {
                $trueNodeName = stripslashes($nodeName);

                if ($nodeName === '*') {
                    $result = [];

                    $restPart = implode('.', $path);
                    $restPart .= ($restPart ? '.' : '') . $lastKey;

                    foreach ($scope as &$item) {
                        if (($val = self::Get($restPart, $item)) !== null) {
                            $result[] = $val;
                        }
                    }

                    return $result ?: $default;
                }
                else if (!isset($scope[$trueNodeName]) || !is_array($scope[$trueNodeName])) {
                    return $default;
                }

                $scope = &$scope[$trueNodeName];
            }

            if ($lastKey === '*') {
                return $scope;
            }

            return $scope[stripslashes($lastKey)] ?? $default;
        }

        public static function Set($key, &$array, $value) :bool
        {
            if (!is_array($array)) {
                return false;
            }
            else if ($key === null || $key === false || $key === '') {
                return false;
            }

            $path = preg_split("~(?<!\\\\)\.~", $key);

            if (!isset($path[1])) {
                $array[stripslashes($path[0])] = $value;

                return true;
            }

            $lastKey = array_pop($path);
            $scope   = &$array;

            while (!is_null($nodeName = array_shift($path))) {
                $trueNodeName = stripslashes($nodeName);

                if ($nodeName === '*') {
                    $restPart = implode('.', $path);
                    $restPart .= ($restPart ? '.' : '') . $lastKey;

                    foreach ($scope as &$item) {
                        self::Set($restPart, $scope, $value);
                    }

                    return true;
                }
                else if (isset($scope[$trueNodeName]) && !is_array($scope[$trueNodeName])) {
                    return false;
                }

                $scope = &$scope[$trueNodeName];
            }

            if ($lastKey === '*') {
                foreach ($scope as &$item) {
                    $item = $value;
                }
            }
            else {
                $scope[stripslashes($lastKey)] = $value;
            }

            return true;
        }

        public static function RemoveValue($value, &$array) :void
        {
            if (!is_array($array)) {
                return;
            }

            $array = array_diff($array, Cast::ToArray($value));
        }

        public static function Has($value, &$array) :bool
        {
            return is_array($array) ? in_array($value, $array) : false;
        }

        public static function IsAssoc(&$array) :bool
        {
            if (!is_array($array)) {
                return false;
            }

            $keys = array_keys($array);

            return $keys == array_keys($keys);
        }

        public static function Every(&$array, callable $callback) :bool
        {
            if (!is_array($array)) {
                return false;
            }

            foreach ($array as $key => &$item) {
                if (!$callback($item, $key)) {
                    return false;
                }
            }

            return true;
        }

        public static function Any(&$array, callable $callback) :bool
        {
            if (!is_array($array)) {
                return false;
            }

            foreach ($array as $key => &$item) {
                if ($callback($item, $key)) {
                    return true;
                }
            }

            return false;
        }

        public static function ChangeKeyCase(&$array, int $case = CASE_LOWER, bool $recursive = false) :void
        {
            if (!is_array($array)) {
                return;
            }

            $array = array_change_key_case($array, $case);

            if ($recursive) {
                array_walk($array, function (&$item) use (&$case) {
                    if (is_array($item)) {
                        self::ChangeKeyCase($item, $case, true);
                    }
                });
            }
        }
    }