<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Core;

    use Vxn\Helper\Cast;

    final class SigHandler
    {
        /**
         * @var callable[]
         */
        private static $handlers = [];

        /**
         * @var int[]
         */
        private static $bindedSignals = [];

        public static function Start() :void
        {
            pcntl_async_signals(true);
        }

        public static function AddHandler($signal, callable $handler, bool $replace = true) :void
        {
            $signal = Cast::ToArray($signal);

            foreach ($signal as $signalNumber) {
                if ($replace) {
                    self::$handlers[$signalNumber] = [$handler];
                }
                else {
                    self::$handlers[$signalNumber][] = $handler;
                }

                if (!in_array($signalNumber, self::$bindedSignals)) {
                    self::$bindedSignals[] = $signalNumber;
                    pcntl_signal($signalNumber, ["\\Vxn\\Core\\SigHandler", 'Handle']);
                }
            }
        }

        public static function Handle(int $signalNumber) :void
        {
            if (self::$handlers[$signalNumber]) {
                foreach (self::$handlers[$signalNumber] as $handler) {
                    $handler($signalNumber);
                }
            }
        }
    }