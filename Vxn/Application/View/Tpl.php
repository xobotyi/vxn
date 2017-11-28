<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Application\View;


    use Vxn\Cache\Memcache;
    use Vxn\Core\Cfg;
    use Vxn\Core\FS;
    use Vxn\Core\Log;

    class Tpl
    {
        private static $inited;

        private static $tplPath;
        private static $useCache;
        private static $cacheTTL = 60;

        private static $globalData = [];

        public static function Init(string $tplPath = null, bool $useCache = false, int $cacheTTL = 60) :bool
        {
            if (self::$inited) {
                return true;
            }

            self::$inited = true;

            self::$tplPath  = $tplPath = $tplPath ?: Cfg::Get('App.path.tpl');
            self::$useCache = $useCache ?: Cfg::Get('App.templates.cache.enabled', false);
            self::$cacheTTL = $cacheTTL ?: Cfg::Get('App.templates.cache.ttl', 60);

            if (!FS::IsDir($tplPath)) {
                throw new \Error("Templates dir {$tplPath} not exists");
            }

            return true;
        }

        public static function ProcessImports(string &$template) :int
        {
            $template = preg_replace_callback(
                '~\{\@[ \t]*include[ \t]*"([A-Za-z0-9_/]+)"[ \t]*\@\}~',
                function ($match) {
                    return self::Get($match[1]) ?: $match[0];
                },
                $template,
                -1,
                $importsCount
            );

            return $importsCount;
        }

        public static function Get(string $name) :?string
        {
            $tpl = self::$useCache ? Memcache::get('tpl:' . addslashes($name)) : false;

            if ($tpl === false) {
                $tplPath = self::$tplPath . "/{$name}.tpl_";

                if (!FS::IsFile($tplPath) || !($tpl = FS::Read($tplPath))) {
                    Log::Write('error',
                               "Requested template [{$name}] not exists or empty at path {$tplPath}",
                               'templates',
                               Log::LEVEL_CRITICAL);

                    throw new \Error("Requested template [{$name}] not exists or empty at path {$tplPath}");
                }

                self::ProcessComments($tpl);
                self::ProcessVariables($tpl);
                self::ProcessImports($tpl);
            }

            !self::$useCache ?: Memcache::set('tpl:' . addslashes($name), $tpl, self::$cacheTTL);

            return $tpl ?: null;
        }

        public static function ProcessComments(string &$template) :int
        {
            $template = preg_replace(
                '~\{\#.*\#\}~musi',
                "",
                $template,
                -1,
                $commentsCount
            );

            return $commentsCount;
        }

        public static function ProcessVariables(string &$template)
        {
            $template = preg_replace_callback(
                '~\{(?<unesOpen>\!)?[ \t]*' .
                '(?:(?<mainScopeVar>\$[a-z_][a-z0-9_]*)|(?<mainGlobalVar>[a-z0-9_]+(?:\.[a-z0-9_]+)*)|(?<mainLiteralVar>(?:\"[^\"]*\")|(?:\'[^\']*\')))[ \t]*' .
                '(?:\|\|[ \t]*(?:(?<alterScopeVar>\$[a-z_][a-z0-9_]*)|(?<alterGlobalVar>[a-z0-9_]+(?:\.[a-z0-9_]+)*)|(?<alterLiteralVar>(?:\"[^\"]*\")|(?:\'[^\']*\')))[ \t]*)?'
                . '(?<unesClose>\!)?\}~sui',
                function ($match) use (&$data) {
                    //var_dump($match);

                    $replacement = '';

                    if ($match['mainScopeVar']) {
                        if ($match['alterScopeVar'] ?? false) {
                            $replacement = "isset({$match['mainScopeVar']}) " .
                                           "? {$match['mainScopeVar']} " .
                                           ": '{$match['alterScopeVar']}'";
                        }
                        else if ($match['alterGlobalVar'] ?? false) {
                            $replacement = "isset({$match['mainScopeVar']}) " .
                                           "? {$match['mainScopeVar']} " .
                                           ": \Vxn\Helper\Arr::Get('{$match['alterGlobalVar']}', \$VXN_TPL_DATA, '$match[0]')";
                        }
                        else {
                            $replacement = "isset({$match['mainScopeVar']}) " .
                                           "? {$match['mainScopeVar']} " .
                                           ": '{$match[0]}'";
                        }
                    }
                    else if ($match['mainGlobalVar']) {
                        if ($match['alterScopeVar'] ?? false) {
                            $replacement = "\Vxn\Helper\Arr::Get('{$match['mainGlobalVar']}', " .
                                           "\$VXN_TPL_DATA, " .
                                           "isset({$match['alterScopeVar']}) ? {$match['alterScopeVar']} : '$match[0]')";
                        }
                        else if ($match['alterGlobalVar'] ?? false) {
                            $replacement = "\Vxn\Helper\Arr::Get('{$match['mainGlobalVar']}', " .
                                           "\$VXN_TPL_DATA, " .
                                           "\Vxn\Helper\Arr::Get('{$match['alterGlobalVar']}', \$VXN_TPL_DATA, '$match[0]'))";
                        }
                        else {
                            $replacement = "\Vxn\Helper\Arr::Get('{$match['mainGlobalVar']}', \$VXN_TPL_DATA, '$match[0]')";
                        }
                    }
                    else if ($match['mainLiteralVar']) {
                        preg_match_all('~\:(\$[a-z_][a-z0-9_]*)\:~i', $match['mainLiteralVar'], $vars);

                        if ($vars[1]) {
                            $scopeVars = "";
                            foreach ($vars[1] as $varName) {
                                $scopeVars .= "'{$varName}'=>$varName,";
                            }

                            $replacement = "\\Vxn\\Core\\I18n::Translate({$match['mainLiteralVar']}, \$VXN_TPL_DATA + [$scopeVars])";
                        }
                        else {
                            $replacement = "\\Vxn\\Core\\I18n::Translate({$match['mainLiteralVar']}, \$VXN_TPL_DATA)";
                        }
                    }

                    if (($match['unesOpen'] ?? false) && ($match['unesClose'] ?? false)) { // unescaped output
                        return "<?= {$replacement} ?>";
                    }

                    return "<?= htmlspecialchars({$replacement}) ?>";
                },
                $template,
                -1,
                $variablesCount
            );

            return $variablesCount;
        }

        public static function ProcessClauses(string &$template)
        {
            return $template;
        }

        public static function ProcessPhrases(string &$template)
        {
            return $template;
        }

        public static function Hydrate(string $template, array $data = []) :string
        {
            // used in template as variable to store all hydration data
            $VXN_TPL_DATA = array_replace_recursive(self::$globalData, $data);

            ob_start();
            eval("\n?>" . self::Get($template) . "<?\n");

            $template = ob_get_contents();

            ob_end_clean();

            return $template;
        }

        public static function Exists(string $name) :bool
        {
            return (bool)self::Get($name);
        }
    }