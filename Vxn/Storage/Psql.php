<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Storage;

    use Vxn\Core\Cfg;
    use Vxn\Core\Log;

    final class Psql
    {
        /**
         * @var bool
         */
        private static $inited;

        /**
         * @var resource
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
            if (self::$inited) {
                return;
            }

            self::$inited = true;

            $host     = $host ?: Cfg::Get('Vxn.storage.postgres.host', '127.0.0.1');
            $port     = $port ?: Cfg::Get('Vxn.storage.postgres.port', '5432');
            $dbname   = $dbname ?: Cfg::Get('Vxn.storage.postgres.dbname', '');
            $user     = $user ?: Cfg::Get('Vxn.storage.postgres.user', '');
            $pass     = $pass ?: Cfg::Get('Vxn.storage.postgres.pass', '');
            $encoding = $encoding ?: Cfg::Get('Vxn.storage.postgres.encoding', 'UTF8');

            self::$connection = pg_connect("host={$host} port={$port} dbname={$dbname} user={$user} password={$pass} options='--client_encoding={$encoding}'");

            if (!self::$connection) {
                Log::Write('db',
                           "Unable to connect to PostgreSQL database." .
                           "\n\tConnection string: [host={$host} port={$port} dbname={$dbname} user={$user} password={$pass} options='--client_encoding={$encoding}']",
                           'PostgreSQL',
                           Log::LEVEL_EMERGENCY);
                throw new \Exception('Unable to connect to PostgreSQL database');
            }
        }

        /**
         * @return resource
         */
        public static function GetConnection()
        {
            if (self::$connection) {
                return self::$connection;
            }

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

        public static function GetCol($query) :array
        {
            return [];
        }

        public static function GetArr($query) :array
        {
            return [];
        }

        public static function GetRow($query) :array
        {
            return [];
        }

        public static function GetVal($query)
        {
            return null;
        }

        /**
         * @param string $query
         *
         * @return bool|resource
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
         * @return bool|resource
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

        public static function Format(string $query, ...$vars)
        {
            return preg_replace_callback(
                "~(%%?)(\d+)~i",
                function ($match) use (&$vars) {
                    $idx = $match[2] - 1;
                    if (!isset($vars[$idx])) {
                        return $match[0];
                    }

                    return $match[1] === '%%' ? $vars[$idx] : pg_escape_string($vars[$idx]);
                },
                $query
            );
        }
    }