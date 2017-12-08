<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Application\Base;


    use Vxn\Application\Routing\Router;
    use Vxn\Dictionary\HttpContentType;
    use Vxn\Helper\Str;
    use Vxn\Http\Response;

    class ModuleBase
    {
        public const RESPONSE_TYPE_JSON  = 'json';
        public const RESPONSE_TYPE_XML   = 'xml';
        public const RESPONSE_TYPE_HTML  = 'html';
        public const RESPONSE_TYPE_TEXT  = 'text';
        public const RESPONSE_TYPES_LIST = [
            self::RESPONSE_TYPE_JSON,
            self::RESPONSE_TYPE_XML,
            self::RESPONSE_TYPE_HTML,
            self::RESPONSE_TYPE_TEXT,
        ];

        protected $contentType = null;
        protected $isCORS      = false;

        protected $submodulesNS;

        public function __construct()
        {
            $this->setSubmodulesDir('Submodules');
            $this->setContentType(self::RESPONSE_TYPE_HTML);
        }

        public function setSubmodulesDir(string $dirName) :self
        {
            $this->submodulesNS = "\\" .
                                  substr(static::class, 0, strrpos(static::class, '\\')) .
                                  str_replace('/', '\\', $dirName);

            return $this;
        }

        protected function setContentType(string $contentType) :self
        {
            if (!in_array($contentType, self::RESPONSE_TYPES_LIST)) {
                throw new \Error("Unsupported response type [{$contentType}]");
            }

            if ($this->contentType !== $contentType) {
                $this->contentType = $contentType;

                switch ($contentType) {
                    case self::RESPONSE_TYPE_HTML:
                        Response::SetContentType(HttpContentType::TEXT_HTML);
                        break;

                    case self::RESPONSE_TYPE_TEXT:
                        Response::SetContentType(HttpContentType::TEXT_PLAIN);
                        break;

                    case self::RESPONSE_TYPE_JSON:
                        Response::SetContentType(HttpContentType::APPLICATION_JSON);
                        break;

                    case self::RESPONSE_TYPE_XML:
                        Response::SetContentType(HttpContentType::APPLICATION_XML);
                        break;
                }
            }

            return $this;
        }

        protected function setCORS(bool $enabled) :self
        {
            if ($enabled !== $this->isCORS) {
                $this->isCORS = $enabled;

                if ($this->isCORS) {
                    Response::AddHeader('Access-Control-Allow-Origin', '*');
                    Response::AddHeader('Access-Control-Allow-Credentials', 'true');
                    Response::AddHeader('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
                    Response::AddHeader('Access-Control-Max-Age', '1000');
                    Response::AddHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token , Authorization');
                }
                else {
                    Response::RemoveHeader('Access-Control-Allow-Origin');
                    Response::RemoveHeader('Access-Control-Allow-Credentials');
                    Response::RemoveHeader('Access-Control-Allow-Methods');
                    Response::RemoveHeader('Access-Control-Max-Age');
                    Response::RemoveHeader('Access-Control-Allow-Headers');
                }
            }

            return $this;
        }

        protected function routeToSubmodule(string $name, array $parameters = [], array $data = [])
        {
            $moduleName = Str::UpFirstSymbol(trim($name), false);
            $actionName = Str::UpFirstSymbol(trim(array_shift($parameters)), true);
            $moduleNS   = "{$this->submodulesNS}\\{$moduleName}Submodule";

            return Router::CallModuleAction($moduleNS, $actionName, $parameters, $data);
        }
    }