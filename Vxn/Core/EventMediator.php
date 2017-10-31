<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Core;


    class EventMediator
    {
        private static $listeners = [];

        private static $inited;

        public static function Init() :void
        {
            if (self::$inited) {
                return;
            }

            self::$inited = true;

            foreach (Cfg::Get('App.events.events', []) as $eventName => &$listeners) {
                foreach ($listeners as &$listener) {
                    if ($listener['once'] ?? false) {
                        self::Once($eventName, $listener['listener']);
                    }
                    else {
                        self::On($eventName, $listener['listener']);
                    }
                }
            }
        }

        public static function Emit(string $evtName, array $payload)
        {
            if (!isset(self::$listeners[$evtName]) || !self::$listeners[$evtName]) {
                return;
            }

            self::$listeners[$evtName] = array_filter(self::$listeners[$evtName], function (&$entry) use ($payload) {
                if (is_callable($entry['listener'])) {
                    $entry['listener']($payload);
                }
                else {
                    call_user_func_array($entry['listener'], [$payload]);
                }

                return !$entry['once'];
            });

            if (!self::$listeners[$evtName]) {
                unset(self::$listeners[$evtName]);
            }
        }

        public static function On(string $evtName, $listener)
        {
            if (isset(self::$listeners[$evtName]) && count(self::$listeners[$evtName]) >= Cfg::Get('App.events.maxListeners', 10)) {
                trigger_error("Maximum listeners count [" . Cfg::Get('App.events.maxListeners', 10) . "] exceeded for event {$evtName}", E_NOTICE);
            }

            self::$listeners[$evtName][] = ['listener' => &$listener, 'once' => false];
        }

        public static function PrependListener(string $evtName, $listener)
        {
            if (!isset(self::$listeners[$evtName])) {
                self::$listeners[$evtName] = [];
            }
            else if (count(self::$listeners[$evtName]) >= Cfg::Get('App.events.maxListeners', 10)) {
                trigger_error("Maximum listeners count [" . Cfg::Get('App.events.maxListeners', 10) . "] exceeded for event {$evtName}", E_NOTICE);
            }

            array_unshift(self::$listeners[$evtName], ['listener' => &$listener, 'once' => false]);
        }

        public static function Once(string $evtName, $listener)
        {
            if (isset(self::$listeners[$evtName]) && count(self::$listeners[$evtName]) >= Cfg::Get('App.events.maxListeners', 10)) {
                trigger_error("Maximum listeners count [" . Cfg::Get('App.events.maxListeners', 10) . "] exceeded for event {$evtName}", E_NOTICE);
            }

            self::$listeners[$evtName][] = ['listener' => &$listener, 'once' => true];
        }

        public static function PrependOnceListener(string $evtName, $listener)
        {
            if (!isset(self::$listeners[$evtName])) {
                self::$listeners[$evtName] = [];
            }
            else if (count(self::$listeners[$evtName]) >= Cfg::Get('App.events.maxListeners', 10)) {
                trigger_error("Maximum listeners count [" . Cfg::Get('App.events.maxListeners', 10) . "] exceeded for event {$evtName}", E_NOTICE);
            }

            array_unshift(self::$listeners[$evtName], ['listener' => &$listener, 'once' => true]);
        }

        public static function RemoveListener(string $evtName, $listener)
        {
            if (!isset(self::$listeners[$evtName])) {
                return;
            }

            self::$listeners[$evtName] = array_filter(self::$listeners[$evtName], function (&$entry) use (&$listener) {
                if ($entry['listener'] == $listener) {
                    return false;
                }

                return true;
            });

            if (!self::$listeners[$evtName]) {
                unset(self::$listeners[$evtName]);
            }
        }

        public static function RemoveAllListeners(string $evtName) :void
        {
            if (isset(self::$listeners[$evtName])) {
                unset(self::$listeners[$evtName]);
            }
        }

        public static function EventNames() :array
        {
            return array_keys(self::$listeners);
        }
    }