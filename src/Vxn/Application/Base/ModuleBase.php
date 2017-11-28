<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Application\Base;


    use Vxn\Application\Routing\Router;
    use Vxn\Helper\Str;

    class ModuleBase
    {
        protected $submodulesNS;

        public function __construct()
        {
            $this->setSubmodulesDir('Submodules');
        }

        public function setSubmodulesDir(string $dirName) :void
        {
            $this->submodulesNS = "\\" .
                                  substr(static::class, 0, strrpos(static::class, '\\')) .
                                  str_replace('/', '\\', $dirName);
        }

        protected function routeToSubmodule(string $name, array $parameters = [], array $data = [])
        {
            $moduleName = Str::UpFirstSymbol(trim($name), false);
            $actionName = Str::UpFirstSymbol(trim(array_shift($parameters)), true);
            $moduleNS   = "{$this->submodulesNS}\\{$moduleName}Submodule";

            return Router::CallModuleAction($moduleNS, $actionName, $parameters, $data);
        }
    }