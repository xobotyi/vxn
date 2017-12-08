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
            preg_match('/^(([^:\/\?#]+):)?(\/\/((([^\/\?#]*)@)?([^\/\?#:]*)(:([^\/\?#]*))?))?([^\?#]*)(\?([^#]*))?(#(.*))?/i', $string,
                       $matches);

            $this->_all               = ($matches[0] ?? null);
            $this->_scheme            = ($matches[2] ?? null);
            $this->_protocol          = ($matches[1] ?? null) . '//';
            $this->_authority         = ($matches[4] ?? null);
            $this->_user_info         = ($matches[6] ?? null);
            $this->_host              = ($matches[7] ?? null);
            $this->_port              = (int)($matches[9] ?? null);
            $this->_path              = ($matches[10] ?? null);
            $this->_query             = ($matches[12] ?? null);
            $this->_fragment          = ($matches[14] ?? null);
            $this->_hierarchical_part = $this->_authority . $this->_path;
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