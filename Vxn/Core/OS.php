<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Core;

    final class OS
    {
        private static $inited;
        private static $osName;

        public static function GetOS()
        {
            self::Init();

            return self::$osName;
        }

        private static function Init()
        {
            if (self::$inited) {
                return;
            }

            self::$inited = true;

            self::$osName = substr(php_uname(), 0, strpos(php_uname(), ' '));
        }

        public static function Exec($cmd, bool $inBackground = false)
        {
            self::Init();

            if ($inBackground) {
                if (self::$osName == 'Windows') {
                    pclose(popen("start /B " . $cmd, "r"));
                }
                else {
                    $cmd .= " > /dev/null &";
                }
            }

            return exec($cmd);
        }

        public static function RealExec(string $cmd, $cwd = './', $env = [])
        {
            $result = [
                "exit"   => 1,       // exit 0 on ok
                "stdout" => "",      // output of the command
                "stderr" => "",      // errors during execution
            ];

            $descriptor = [
                0 => ["pipe", "r"],    // stdin is a pipe that the child will read from
                1 => ["pipe", "w"],    // stdout is a pipe that the child will write to
                2 => ["pipe", "w"]     // stderr is a pipe
            ];

            $proc = proc_open($cmd, $descriptor, $pipes, $cwd, $env);

            if (is_resource($proc) !== false) {
                $result['stdout'] = trim(stream_get_contents($pipes[1]));
                $result['stderr'] = trim(stream_get_contents($pipes[2]));

                fclose($pipes[0]);
                fclose($pipes[1]);
                fclose($pipes[2]);

                $result['exit'] = proc_close($proc);
            }

            return $result;
        }
    }