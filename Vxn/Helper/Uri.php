<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Helper;


    class Uri
    {
        private $_all;
        private $_authority;
        private $_fragment;
        private $_hierarchical_part;
        private $_host;
        private $_path;
        private $_port;
        private $_protocol;
        private $_query;
        private $_scheme;
        private $_user_info;

        public function __construct(string $string)
        {
            $matches = [];
            preg_match('/^(([^:\/\?#]+):)?(\/\/((([^\/\?#]*)@)?([^\/\?#:]*)(:([^\/\?#]*))?))?([^\?#]*)(\?([^#]*))?(#(.*))?/i', $string, $matches);

            $this->_all               = Arr::Get(0, $matches);
            $this->_scheme            = Arr::Get(2, $matches);
            $this->_protocol          = Arr::Get(1, $matches) . '//';
            $this->_authority         = Arr::Get(4, $matches);
            $this->_user_info         = Arr::Get(6, $matches);
            $this->_host              = Arr::Get(7, $matches);
            $this->_port              = (int)Arr::Get(9, $matches);
            $this->_path              = Arr::Get(10, $matches);
            $this->_query             = Arr::Get(12, $matches);
            $this->_fragment          = Arr::Get(14, $matches);
            $this->_hierarchical_part = Arr::Get(4, $matches) . $this->_path;
        }

        public function GetAuthority() :string
        {
            return $this->_authority;
        }

        public function GetFragment() :string
        {
            return $this->_fragment;
        }

        public function GetHierarchicalPart() :string
        {
            return $this->_hierarchical_part;
        }

        public function GetHost() :string
        {
            return $this->_host;
        }

        public function GetPath() :string
        {
            return $this->_path;
        }

        public function GetPort() :int
        {
            return $this->_port;
        }

        public function GetProtocol() :string
        {
            return $this->_protocol;
        }

        public function GetQuery() :string
        {
            return $this->_query;
        }

        public function GetSchemeName() :string
        {
            return $this->_scheme;
        }

        public function GetString() :string
        {
            return $this->_all;
        }

        public function GetUserInfo() :string
        {
            return $this->_user_info;
        }
    }