<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper;

    class Timer
    {
        private $tStart;
        private $tEnd;

        private static function getTime(?int $time = null)
        {
            list($usec, $sec) = explode(" ", ((!is_null($time)) ? $time : microtime()));

            return ((float)$usec + (float)$sec);
        }

        public function start()
        {
            $this->tStart = self::getTime();
        }

        public function stop()
        {
            if ($this->tStart) {
                $this->tEnd = self::getTime();
            }
        }

        public function elapsed(int $precision = 0)
        {
            if ($this->tStart) {
                if ($this->tEnd) {
                    return round(($this->tEnd - $this->tStart), $precision);
                }
                else {
                    return round(($this->getTime() - $this->tStart), $precision);
                }
            }
            else {
                return round(0, $precision);
            }
        }
    }