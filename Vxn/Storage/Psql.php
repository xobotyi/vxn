<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Storage;

    use Vxn\Cache\Memcache;
    use Vxn\Core\Cfg;
    use Vxn\Core\Engine;
    use Vxn\Core\Log;
    use Vxn\Helper\Json;

    final class Psql
    {
        /**
         * @var bool
         */
        private static $inited = false;

        /**
         * @var bool
         */
        private static $useCache = true;

        /**
         * @var \resource
         */
        private static $connection;

        /**
         * @var int
         */
        private static $queriesCnt = 0;

        /**
         * @param null|string $host
         * @param int|null    $port
         * @param null|string $dbname
         * @param null|string $user
         * @param null|string $pass
         * @param null|string $encoding
         *
         * @throws \Exception
         */
        public static function Init(?string $host = null, ?int $port = null, ?string $dbname = null, ?string $user = null, ?string $pass = null, ?string $encoding = 'UTF8') :void
        {
            if (!self::IsSupported()) {
                throw new \Exception('PostgreSQL is not supported!');
            }

            if (self::$inited) {
                return;
            }

            self::$inited   = true;
            self::$useCache = Cfg::Get('Vxn.storage.postgres.useCache', false);

            $host     = $host ?: Cfg::Get('Vxn.storage.postgres.host', '127.0.0.1');
            $port     = $port ?: Cfg::Get('Vxn.storage.postgres.port', '5432');
            $dbname   = $dbname ?: Cfg::Get('Vxn.storage.postgres.dbname', '');
            $user     = $user ?: Cfg::Get('Vxn.storage.postgres.user', '');
            $pass     = $pass ?: Cfg::Get('Vxn.storage.postgres.pass', '');
            $encoding = $encoding ?: Cfg::Get('Vxn.storage.postgres.encoding', 'UTF8');

            self::$connection = pg_connect("host={$host} port={$port} dbname={$dbname} user={$user} password={$pass} options='--client_encoding={$encoding}'");

            if (!self::$connection) {
                Log::Write('error',
                           "Unable to connect to PostgreSQL database." .
                           "\n\tConnection string: [host={$host} port={$port} dbname={$dbname} user={$user} password={$pass} options='--client_encoding={$encoding}']",
                           'PostgreSQL',
                           Log::LEVEL_EMERGENCY);
                throw new \Exception('Unable to connect to PostgreSQL database');
            }

            Engine::RegisterFinalShutdown(function () {
                self::CloseConnection();
            });
        }

        /**
         * @return bool
         */
        public static function IsSupported() :bool
        {
            return function_exists('pg_connect');
        }

        /**
         * @return resource
         * @throws \Exception
         */
        public static function GetConnection()
        {
            self::Init();

            return self::$connection;
        }

        /**
         * @return bool
         */
        public static function CloseConnection() :bool
        {
            if (!self::$connection) {
                return true;
            }

            return pg_close(self::$connection);
        }

        /**
         * @return int
         */
        public static function QueriesPerformed() :int
        {
            return self::$queriesCnt;
        }

        /**
         * @param string $query
         * @param bool   $cached
         * @param int    $ttl
         * @param array  $tags
         * @param bool   $prolong
         *
         * @return array|bool
         * @throws \Exception
         * @throws \TypeError
         */
        public static function GetCol(string $query, bool $cached = false, int $ttl = 60, array $tags = [], bool $prolong = true)
        {
            if ($md5 = self::$useCache && $cached ? md5($query) : "") {
                if (($data = Memcache::get("pgsql:col:{$md5}")) !== false) {
                    if ($prolong) {
                        Memcache::set("pgsql:col:{$md5}", $data, $ttl, $tags);
                    }

                    return $data;
                }
            }

            $res = self::Query($query);

            if (!$res || !pg_num_rows($res)) {
                return [];
            }

            $data = pg_fetch_all_columns($res, 0);
            foreach ($data as &$field) {
                self::TranspileFieldTypes($res, $field, 0);
            }

            if ($md5) {
                Memcache::set("pgsql:col:{$md5}", $data, $ttl, $tags);
            }

            return $data ?: [];
        }

        /**
         * @param string $query
         * @param bool   $cached
         * @param int    $ttl
         * @param array  $tags
         * @param bool   $prolong
         *
         * @return array|bool
         * @throws \Exception
         * @throws \TypeError
         */
        public static function GetRow(string $query, bool $cached = false, int $ttl = 60, array $tags = [], bool $prolong = true)
        {
            if ($md5 = self::$useCache && $cached ? md5($query) : "") {
                if (($data = Memcache::get("pgsql:row:{$md5}")) !== false) {
                    if ($prolong) {
                        Memcache::set("pgsql:row:{$md5}", $data, $ttl, $tags);
                    }

                    return $data;
                }
            }

            $res = self::Query($query);

            if (!$res || !pg_num_rows($res)) {
                return [];
            }

            $data = pg_fetch_assoc($res);
            $i    = 0;
            foreach ($data as &$field) {
                self::TranspileFieldTypes($res, $field, $i++);
            }

            if ($md5) {
                Memcache::set("pgsql:row:{$md5}", $data, $ttl, $tags);
            }

            return $data ?: [];
        }

        /**
         * @param string $query
         * @param bool   $cached
         * @param int    $ttl
         * @param array  $tags
         * @param bool   $prolong
         *
         * @return array|bool
         * @throws \Exception
         * @throws \TypeError
         */
        public static function GetArr(string $query, bool $cached = false, int $ttl = 60, array $tags = [], bool $prolong = true)
        {
            if ($md5 = self::$useCache && $cached ? md5($query) : "") {
                if (($data = Memcache::get("pgsql:arr:{$md5}")) !== false) {
                    if ($prolong) {
                        Memcache::set("pgsql:arr:{$md5}", $data, $ttl, $tags);
                    }

                    return $data;
                }
            }

            $res = self::Query($query);

            if (!$res || !pg_num_rows($res)) {
                return [];
            }

            $data = pg_fetch_all($res);
            foreach ($data as &$row) {
                $i = 0;
                foreach ($row as &$field) {
                    self::TranspileFieldTypes($res, $field, $i++);
                }
            }

            if ($md5) {
                Memcache::set("pgsql:arr:{$md5}", $data, $ttl, $tags);
            }

            return $data ?: [];
        }

        /**
         * @param string $query
         * @param bool   $cached
         * @param int    $ttl
         * @param array  $tags
         * @param bool   $prolong
         *
         * @return bool|null
         * @throws \Exception
         * @throws \TypeError
         */
        public static function GetVal(string $query, bool $cached = false, int $ttl = 60, array $tags = [], bool $prolong = true)
        {
            if ($md5 = self::$useCache && $cached ? md5($query) : "") {
                if (($data = Memcache::get("pgsql:val:{$md5}")) !== false) {
                    if ($prolong) {
                        Memcache::set("pgsql:val:{$md5}", $data, $ttl, $tags);
                    }

                    return $data;
                }
            }

            $res = self::Query($query);

            if (!$res || !pg_num_rows($res)) {
                return null;
            }

            $data = pg_fetch_row($res, 0)[0];
            self::TranspileFieldTypes($res, $data, 0);

            if ($md5) {
                Memcache::set("pgsql:val:{$md5}", $data, $ttl, $tags);
            }

            return $data ?: null;
        }

        /**
         * @param $result
         * @param $value
         * @param $fieldNum
         */
        private static function TranspileFieldTypes(&$result, &$value, $fieldNum)
        {
            switch (pg_field_type_oid($result, $fieldNum)) {
                // int
                case 20:
                case 21:
                case 23:
                case 1005:
                case 1007:
                case 1016:
                    $value = (int)$value;
                break;

                // float
                case 700:
                case 701:
                case 1021:
                case 1022:
                    $value = (float)$value;
                break;

                // boolean
                case 16:
                case 1000:
                    $value = $value == 't';
                break;

                // json
                case 114:
                case 199:
                case 3807:
                case 3802:
                    $value = Json::Decode($value);
                break;
            }
        }

        /**
         * @param string $query
         *
         * @return bool|resource
         * @throws \Exception
         */
        public static function Query(string $query)
        {
            if (!$query) {
                return false;
            }

            self::$queriesCnt++;
            $res = pg_query(self::GetConnection(), $query);

            if (!$res) {
                $backtrace = debug_backtrace();

                Log::Write('db',
                           "Error occurred while performing PostgreSQL query." .
                           "\n\tError: " . pg_last_error(self::GetConnection()) .
                           "\n\tCalled in {$backtrace[1]['file']} at line {$backtrace[1]['line']}",
                           'PostgreSQL',
                           Log::LEVEL_CRITICAL);
            }

            return $res;
        }

        /**
         * @param string $query
         *
         * @return bool
         * @throws \Exception
         */
        public static function QueryAsync(string $query)
        {
            if (!$query) {
                return false;
            }

            self::$queriesCnt++;
            $res = pg_send_query(self::GetConnection(), $query);

            if (!$res) {
                $backtrace = debug_backtrace();

                Log::Write('db',
                           "Error occurred while performing PostgreSQL asynchronous query." .
                           "\n\tError: " . pg_last_error(self::GetConnection()) .
                           "\n\tCalled in {$backtrace[1]['file']} at line {$backtrace[1]['line']}",
                           'PostgreSQL',
                           Log::LEVEL_CRITICAL);
            }

            return $res;
        }

        /**
         * @param string $query
         * @param array  ...$vars
         *
         * @return mixed
         */
        public static function Format(string $query, ...$vars)
        {
            return preg_replace_callback(
                "~(%%?)(\d+)~i",
                function ($match) use (&$vars) {
                    $idx = $match[2] - 1;
                    if (!isset($vars[$idx])) {
                        return $match[0];
                    }

                    $val = &$vars[$match[2] - 1];

                    if ($match[1] === '%%') {
                        return "'{$val}'";
                    }
                    else if (is_null($val)) {
                        return "NULL";
                    }
                    else if (is_bool($val)) {
                        return $val ? 'true' : 'false';
                    }

                    return "'" . pg_escape_string($vars[$idx]) . "'";
                },
                $query
            );
        }

        /**
         * @param array $array
         *
         * @return string
         */
        public static function EncodePgArray(array $array) :string
        {
            $array = array_map(function (&$item) {
                if (is_array($item)) {
                    $item = self::EncodePgArray($item);
                }
                else if (is_null($item)) {
                    $item = "NULL";
                }
                else if (is_bool($item)) {
                    $item = $item ? 'true' : 'false';
                }
                else if (!is_integer($item) && !is_float($item)) {
                    $item = '"' . pg_escape_string($item) . '"';
                }

                return $item;
            }, $array);

            return "{" . implode(',', $array) . "}";
        }

        /**
         * @param string $str
         *
         * @return array
         */
        public static function DecodePgArray(string $str) :array
        {
            if ($str == '{}' || $str[0] != '{' || $str[-1] != '}') {
                return [];
            }

            $nestLevel = -1;
            $res       = [];
            $len       = strlen($str);
            $item      = "";

            for ($i = 0; $i < $len; $i++) {
                if ($str[$i] == "{") {
                    if (++$nestLevel > 0) {
                        $item .= $str[$i];
                    }
                }
                else if ($str[$i] == "}") {
                    if ($nestLevel-- > 0) {
                        $item .= $str[$i];
                    }

                    if ($nestLevel == -1) {
                        if (is_numeric($item)) {
                            $item = $item * 1;
                        }
                        else if ($item[0] == "{") {
                            $item = self::DecodePgArray($item);
                        }
                        else {
                            $item = trim($item, "\"");
                        }

                        $res[] = $item;
                        $item  = '';
                    }
                }
                else if ($str[$i] == ',' && !$nestLevel) {
                    if (is_numeric($item)) {
                        $item = $item * 1;
                    }
                    else if ($item[0] == "{") {
                        $item = self::DecodePgArray($item);
                    }
                    else {
                        $item = trim($item, "\"");
                    }

                    $res[] = $item;
                    $item  = '';
                }
                else {
                    $item .= $str[$i];
                }
            }

            return $res;
        }
    }