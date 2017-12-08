<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Core;

    final class I18n
    {
        private static $inited = false;

        private static $localesPath;

        private static $langDefault;
        private static $langActive;

        private static $langSupported = [];
        private static $langSlaves    = [];

        private static $langPlurals      = [];
        private static $langTranslations = [];

        public static function Init()
        {
            if (self::$inited) {
                return;
            }

            self::$inited = true;

            self::$localesPath   = Cfg::Get('App.path.i18n');
            self::$langSupported = Cfg::Get('App.i18n.supported', ['en']);
            self::SetActiveLang(self::$langDefault = Cfg::Get('App.i18n.default', 'en'));
        }

        public static function SetActiveLang(string $lang) :bool
        {
            $lang = self::GetMasterLang($lang);

            if (!self::IsSupported($lang)) {
                return false;
            }

            self::$langActive = $lang;
            self::LoadLocale($lang);

            return true;
        }

        public static function GetMasterLang(string $lang) :string
        {
            if (self::IsSupported($lang)) {
                return $lang;
            }

            foreach (self::$langSlaves as $master => $slaves) {
                if (in_array($lang, $slaves)) {
                    $lang = $master;
                    break;
                }
            }

            return $lang;
        }

        public static function IsSupported(string $lang) :bool
        {
            return in_array($lang, self::$langSupported);
        }

        public static function LoadLocale(string $lang) :bool
        {
            if (self::$langTranslations[$lang] ?? false) {
                return true;
            }
            else if (!self::IsSupported($lang)) {
                return false;
            }

            $path = self::$localesPath . "/{$lang}.php";

            if (!FS::IsFile($path)) {
                Log::Write('error',
                           "Unable to load locale [{$lang}] from {$path}",
                           'templates',
                           Log::LEVEL_CRITICAL);

                throw new \Error("Unable to load locale [{$lang}] from {$path}");
            }

            $loc = FS::Load($path, true);

            self::$langPlurals[$lang]      = $loc['plural'];
            self::$langTranslations[$lang] = $loc['translations'];

            return true;
        }

        public static function GetSupported() :array
        {
            return self::$langSupported;
        }

        public static function Translate(string $str, array $data = [], ?string $lang = null) :string
        {
            if ($lang) {
                $lang = self::GetMasterLang($lang);

                if (!self::IsSupported($lang)) {
                    $lang = self::GetActiveLang();
                }
            }
            else {
                $lang = self::$langActive;
            }

            self::LoadLocale($lang);

            $str = self::$langTranslations[$lang] ? (self::$langTranslations[$lang][$str] ?? $str) : $str;

            return self::ProcessString($str, $data, $lang);
        }

        public static function GetActiveLang() :string
        {
            return self::$langActive;
        }

        public static function ProcessString(string $str, array $data = [], ?string $lang = null) :string
        {
            return preg_replace_callback(
                '~\:(?<var>[^\)])\:(?:(?<pluralSpacer>[ \t]*)\((?<plurals>[^\|]+(?:\|[^\|]+)*)\))?~ui',
                function (&$match) use (&$data, &$lang) {
                    $replacement = $data[$match['var']] ?? null;

                    if (is_null($replacement)) {
                        return $match[0];
                    }

                    if (!$match['plurals']) {
                        return $replacement;
                    }

                    $match['plurals'] = explode('|', trim($match['plurals'], '|'));

                    if ($lang) {
                        $plural = isset(self::$langPlurals[$lang]) && is_callable(self::$langPlurals[$lang])
                            ? self::$langPlurals[$lang]($replacement, $match['plurals'])
                            : $match['plurals'][0];
                    }
                    else {
                        $plural = $match['plurals'][0];
                    }

                    return $replacement . $match['pluralSpacer'] . $plural;
                },
                $str);
        }
    }