<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Dictionary;

    /*
     * IMPORTANT!
     * the list is actual for PostgreSQL 10
     *
     * */

    final class PgsqlTypeOids
    {
        private static $oids = [
            16   => 'bool',
            18   => 'char',
            19   => 'name',
            20   => 'int8',
            21   => 'int2',
            22   => 'int2vector',
            23   => 'int4',
            25   => 'text',
            26   => 'oid',
            30   => 'oidvector',
            114   => 'json',
            143  => '_xml',
            194  => 'pg_node_tree',
            199  => '_json',
            600  => 'point',
            601  => 'lseg',
            602  => 'path',
            603  => 'box',
            604  => 'polygon',
            628  => 'line',
            629  => '_line',
            650  => 'cidr',
            651  => '_cidr',
            700  => 'float4',
            701  => 'float8',
            702  => 'abstime',
            703  => 'reltime',
            704  => 'tinterval',
            718  => 'circle',
            719  => '_circle',
            775  => '_macaddr8',
            790  => 'money',
            791  => '_money',
            869  => 'inet',
            1000 => '_bool',
            1001 => '_bytea',
            1002 => '_char',
            1003 => '_name',
            1005 => '_int2',
            1006 => '_int2vector',
            1007 => '_int4',
            1009 => '_text',
            1010 => '_tid',
            1011 => '_xid',
            1012 => '_cid',
            1013 => '_oidvector',
            1014 => '_bpchar',
            1015 => '_varchar',
            1016 => '_int8',
            1017 => '_point',
            1018 => '_lseg',
            1019 => '_path',
            1020 => '_box',
            1021 => '_float4',
            1022 => '_float8',
            1023 => '_abstime',
            1024 => '_reltime',
            1025 => '_tinterval',
            1027 => '_polygon',
            1028 => '_oid',
            1034 => '_aclitem',
            1040 => '_macaddr',
            1041 => '_inet',
            1042 => 'bpchar',
            1043 => 'varchar',
            1082 => 'date',
            1083 => 'time',
            1114 => 'timestamp',
            1115 => '_timestamp',
            1182 => '_date',
            1183 => '_time',
            1184 => 'timestamptz',
            1185 => '_timestamptz',
            1186 => 'interval',
            1187 => '_interval',
            1231 => '_numeric',
            1263 => '_cstring',
            1266 => 'timetz',
            1270 => '_timetz',
            1560 => 'bit',
            1561 => '_bit',
            1562 => 'varbit',
            1563 => '_varbit',
            1700 => 'numeric',
            2201 => '_refcursor',
            2949 => '_txid_snapshot',
            2951 => '_uuid',
            3221 => '_pg_lsn',
            3361 => 'pg_ndistinct',
            3402 => 'pg_dependencies',
            3643 => '_tsvector',
            3644 => '_gtsvector',
            3645 => '_tsquery',
            3802 => 'jsonb',
            3807 => '_jsonb',
            3904 => 'int4range',
            3905 => '_int4range',
            3906 => 'numrange',
            3907 => '_numrange',
            3908 => 'tsrange',
            3909 => '_tsrange',
            3910 => 'tstzrange',
            3911 => '_tstzrange',
            3912 => 'daterange',
            3913 => '_daterange',
            3926 => 'int8range',
            3927 => '_int8range',
        ];

        public static function GetType($oid) :?string
        {
            return self::$oids[$oid];
        }

        public static function GetList() :array
        {
            return self::$oids ?: [];
        }

        public static function IsSupported($oid) :bool
        {
            return in_array($oid, self::$oids);
        }
    }