<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Application\Routing;


    use Vxn\Core\Autoloader;
    use Vxn\Helper\Arr;
    use Vxn\Helper\Str;
    use Vxn\Http\Request;

    class Router
    {
        private static $routes  = [];
        private static $aliases = [];

        private static $reflections = [];

        private static $resolved;

        public static function Add(string $pathFrom, string $pathTo, array $parameters = []) :void
        {
            $pathFrom = array_filter(explode('/', trim($pathFrom, '/ ')), function ($item) {
                return (bool)$item;
            });

            $pathTo = array_filter(explode('/', trim($pathTo, '/ ')), function ($item) {
                return (bool)$item;
            });

            $route = [
                'from'       => &$pathFrom,
                'fromSize'   => count($pathFrom),
                'to'         => &$pathTo,
                'method'     => trim(strtoupper($parameters['method'] ?? 'all')),
                'alias'      => $parameters['alias'] ?? null,
                'regexp'     => $parameters['regexp'] ?? [],
                'middleware' => $parameters['middleware'] ?? [],
            ];

            self::$routes[] = &$route;

            if ($parameters['alias'] ?? null) {
                if (self::$aliases[$parameters['alias']]) {
                    trigger_error("Route with alias `{$parameters['alias']}` already exists");

                    return;
                }
                self::$aliases[$parameters['alias']] = &$route;
            }
        }

        public static function Resolve($forced = false)
        {
            if (self::$resolved || !$forced) {
                return null;
            }

            self::$resolved = true;

            $endpoint = self::GetRoute(Request::Path(true));

            if (!Middleware::FireAlias($endpoint['middleware'], Middleware::STAGE_PRE)) {
                return false;
            }

            $moduleNS = self::GetModuleNS($endpoint['module']);

            if (!$moduleNS) {
                return null;
            }

            if (self::CallModuleAction($moduleNS, $endpoint['action'], $endpoint['parameters'], $endpoint['data'])) {
                Middleware::FireAlias($endpoint['middleware'], Middleware::STAGE_POST);

                return true;
            }

            return false;
        }

        public static function GetModuleNS(string $name) :?string
        {
            if (!($name = trim($name))) {
                return null;
            }

            $name = Str::UpFirstSymbol($name, true);

            return "\\App\\Module\\{$name}\\{$name}Module";
        }

        public static function CallModuleAction(string $moduleNS, string $actionName = 'Default', array &$parameters = [], array &$data = []) :?bool
        {
            $obj = self::$reflections[$moduleNS] ?? Autoloader::GetNewInstance($moduleNS);

            if (!$obj) {
                return null;
            }

            $currentMethod = Request::GetRequestMethod();

            $actionName         = 'Action' . Str::UpFirstSymbol($actionName);
            $actionNameAll      = 'all' . $actionName;
            $actionNameStraight = strtolower($currentMethod) . $actionName;

            if (method_exists($obj, $actionNameStraight)) {
                return $obj->$actionNameStraight($parameters, $data);
            }
            else if (method_exists($obj, $actionName)) {
                return $obj->$actionName($parameters, $data);
            }
            else if (method_exists($obj, $actionNameAll)) {
                return $obj->$actionNameAll($parameters, $data);
            }

            return null;
        }

        public static function GetRoute($path = null) :array
        {
            if (is_string($path)) {
                $path = array_filter(explode('/', trim($path, '/ ')), function ($item) {
                    return (bool)$item;
                });
            }

            $pathSize = count($path);

            foreach (self::$routes as &$route) {
                if ($route['method'] !== 'ALL') {
                    $route['method'] = explode(' ', $route['method']);
                    $currentMethod   = Request::GetRequestMethod();

                    if (!Arr::Any($route['method'], function (&$method) use (&$currentMethod) {
                        var_dump($method, $currentMethod);

                        return $method === $currentMethod;
                    })) {
                        continue;
                    }
                }

                $rules = self::PrepareRouteRules($route['from'], $route['regexp']);

                $wildcard = &$rules['wildcard'];
                $dataPos  = &$rules['dataPos'];
                $rules    = &$rules['rules'];

                if ((!$wildcard && $pathSize !== $route['fromSize']) || ($wildcard && $pathSize < ($route['fromSize'] - 1))) {
                    continue;
                }

                $steps   = 0;
                $matches = true;
                $data    = [];

                foreach ($path as $i => &$param) {
                    $rule = $rules[$i] ?? null;

                    if ($rule === '*') {
                        break;
                    }
                    else if ($rule) {
                        if (!preg_match($rule, $param)) {
                            $matches = false;
                            break;
                        }

                        if (isset($dataPos[(string)$i])) {
                            $data[$dataPos[(string)$i]] = $param;
                        }
                    }

                    $steps++;
                }

                if ($matches) {
                    if ($route['to'][0] === '*') {
                        return [
                            'module'     => $path[0] ?? 'Index',
                            'action'     => $path[1] ?? 'Default',
                            'middleware' => $route['middleware'],
                            'parameters' => array_slice($path, $steps),
                            'data'       => $data,
                        ];
                    }

                    $stepsTo       = 0;
                    $wildcardRoute = false;

                    foreach ($route['to'] as &$param) {
                        if ($param === '*') {
                            $wildcardRoute = true;
                            break;
                        }

                        $dataName = trim($param, ':');
                        if (isset($data[$dataName])) {
                            $param = &$data[$dataName];
                        }

                        $stepsTo++;
                    }

                    if ($wildcard && $wildcardRoute) {
                        $right = array_slice($path, $steps);
                        $left  = array_slice($route['to'], 0, $stepsTo);

                        $route['to'] = array_merge($left, $right);
                    }

                    return [
                        'module'     => $route['to'][0] ?? 'Index',
                        'action'     => $route['to'][1] ?? 'Default',
                        'middleware' => $route['middleware'],
                        'parameters' => array_slice($route['to'], 2),
                        'data'       => $data,
                    ];
                }
            }

            if ($pathSize >= 2) {
                return [
                    'module'     => $path[0] ?? 'Index',
                    'action'     => $path[1] ?? 'Default',
                    'middleware' => [],
                    'parameters' => array_slice($path, 2),
                    'data'       => [],
                ];
            }
            else {
                return [
                    'module'     => $path[0] ?? 'Index',
                    'action'     => $path[1] ?? 'Default',
                    'middleware' => [],
                    'parameters' => [],
                    'data'       => [],
                ];
            }
        }

        private static function PrepareRouteRules(array $from, array $regexps = []) :array
        {
            $wildcard    = false;
            $regexpParam = [];
            $dataPos     = [];

            if ($from[count($from) - 1] === '*') {
                $wildcard                      = true;
                $regexpParam[count($from) - 1] = '*';

                array_pop($from);
            }

            foreach ($from as $i => $part) {
                if (isset($regexps[$part])) {
                    $regexpParam[$i] = "/^{$regexps[$part]}$/i";
                    $part            = trim($part, ':');

                    $dataPos[(string)$i] = $dataPos[(string)$i] ?? $part;

                    continue;
                }

                $regexpParam[$i] = "/{$part}/i";
            }

            return [
                'wildcard' => $wildcard,
                'rules'    => $regexpParam,
                'dataPos'  => $dataPos,
            ];
        }
    }