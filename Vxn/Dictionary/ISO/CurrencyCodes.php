<?php

    namespace Vxn\Dictionary\ISO;


    use Vxn\Helper\Arr;

    final class CurrencyCodes
    {
        private static $codes = [
            "all" => "Lek",
            "dzd" => "Algerian Dinar",
            "ars" => "Argentine Peso",
            "aud" => "Australian Dollar",
            "bsd" => "Bahamian Dollar",
            "bhd" => "Bahraini Dinar",
            "bdt" => "Taka",
            "amd" => "Armenian Dram",
            "bbd" => "Barbados Dollar",
            "bmd" => "Bermudian Dollar",
            "btn" => "Ngultrum",
            "bob" => "Boliviano",
            "bwp" => "Pula",
            "bzd" => "Belize Dollar",
            "sbd" => "Solomon Islands Dollar",
            "bnd" => "Brunei Dollar",
            "mmk" => "Kyat",
            "bif" => "Burundi Franc",
            "khr" => "Riel",
            "cad" => "Canadian Dollar",
            "cve" => "Cabo Verde Escudo",
            "kyd" => "Cayman Islands Dollar",
            "lkr" => "Sri Lanka Rupee",
            "clp" => "Chilean Peso",
            "cny" => "Yuan Renminbi",
            "cop" => "Colombian Peso",
            "kmf" => "Comoro Franc",
            "crc" => "Costa Rican Colon",
            "hrk" => "Kuna",
            "cup" => "Cuban Peso",
            "czk" => "Czech Koruna",
            "dkk" => "Danish Krone",
            "dop" => "Dominican Peso",
            "svc" => "El Salvador Colon",
            "etb" => "Ethiopian Birr",
            "ern" => "Nakfa",
            "fkp" => "Falkland Islands Pound",
            "fjd" => "Fiji Dollar",
            "djf" => "Djibouti Franc",
            "gmd" => "Dalasi",
            "gip" => "Gibraltar Pound",
            "gtq" => "Quetzal",
            "gnf" => "Guinea Franc",
            "gyd" => "Guyana Dollar",
            "htg" => "Gourde",
            "hnl" => "Lempira",
            "hkd" => "Hong Kong Dollar",
            "huf" => "Forint",
            "isk" => "Iceland Krona",
            "inr" => "Indian Rupee",
            "idr" => "Rupiah",
            "irr" => "Iranian Rial",
            "iqd" => "Iraqi Dinar",
            "ils" => "New Israeli Sheqel",
            "jmd" => "Jamaican Dollar",
            "jpy" => "Yen",
            "kzt" => "Tenge",
            "jod" => "Jordanian Dinar",
            "kes" => "Kenyan Shilling",
            "kpw" => "North Korean Won",
            "krw" => "Won",
            "kwd" => "Kuwaiti Dinar",
            "kgs" => "Som",
            "lak" => "Kip",
            "lbp" => "Lebanese Pound",
            "lsl" => "Loti",
            "lrd" => "Liberian Dollar",
            "lyd" => "Libyan Dinar",
            "mop" => "Pataca",
            "mwk" => "Kwacha",
            "myr" => "Malaysian Ringgit",
            "mvr" => "Rufiyaa",
            "mro" => "Ouguiya",
            "mur" => "Mauritius Rupee",
            "mxn" => "Mexican Peso",
            "mnt" => "Tugrik",
            "mdl" => "Moldovan Leu",
            "mad" => "Moroccan Dirham",
            "omr" => "Rial Omani",
            "nad" => "Namibia Dollar",
            "npr" => "Nepalese Rupee",
            "ang" => "Netherlands Antillean Guilder",
            "awg" => "Aruban Florin",
            "vuv" => "Vatu",
            "nzd" => "New Zealand Dollar",
            "nio" => "Cordoba Oro",
            "ngn" => "Naira",
            "nok" => "Norwegian Krone",
            "pkr" => "Pakistan Rupee",
            "pab" => "Balboa",
            "pgk" => "Kina",
            "pyg" => "Guarani",
            "pen" => "Nuevo Sol",
            "php" => "Philippine Peso",
            "qar" => "Qatari Rial",
            "rub" => "Russian Ruble",
            "rwf" => "Rwanda Franc",
            "shp" => "Saint Helena Pound",
            "std" => "Dobra",
            "sar" => "Saudi Riyal",
            "scr" => "Seychelles Rupee",
            "sll" => "Leone",
            "sgd" => "Singapore Dollar",
            "vnd" => "Dong",
            "sos" => "Somali Shilling",
            "zar" => "Rand",
            "ssp" => "South Sudanese Pound",
            "szl" => "Lilangeni",
            "sek" => "Swedish Krona",
            "chf" => "Swiss Franc",
            "syp" => "Syrian Pound",
            "thb" => "Baht",
            "top" => "Pa’anga",
            "ttd" => "Trinidad and Tobago Dollar",
            "aed" => "UAE Dirham",
            "tnd" => "Tunisian Dinar",
            "ugx" => "Uganda Shilling",
            "mkd" => "Denar",
            "egp" => "Egyptian Pound",
            "gbp" => "Pound Sterling",
            "tzs" => "Tanzanian Shilling",
            "usd" => "US Dollar",
            "uyu" => "Peso Uruguayo",
            "uzs" => "Uzbekistan Sum",
            "wst" => "Tala",
            "yer" => "Yemeni Rial",
            "twd" => "New Taiwan Dollar",
            "cuc" => "Peso Convertible",
            "zwl" => "Zimbabwe Dollar",
            "tmt" => "Turkmenistan New Manat",
            "ghs" => "Ghana Cedi",
            "vef" => "Bolivar",
            "sdg" => "Sudanese Pound",
            "uyi" => "Uruguay Peso en Unidades Indexadas (URUIURUI)",
            "rsd" => "Serbian Dinar",
            "mzn" => "Mozambique Metical",
            "azn" => "Azerbaijanian Manat",
            "ron" => "Romanian Leu",
            "che" => "WIR Euro",
            "chw" => "WIR Franc",
            "try" => "Turkish Lira",
            "xaf" => "CFA Franc BEAC",
            "xcd" => "East Caribbean Dollar",
            "xof" => "CFA Franc BCEAO",
            "xpf" => "CFP Franc",
            "xba" => "Bond Markets Unit European Composite Unit (EURCO)",
            "xbb" => "Bond Markets Unit European Monetary Unit (E.M.U.-6)",
            "xbc" => "Bond Markets Unit European Unit of Account 9 (E.U.A.-9)",
            "xbd" => "Bond Markets Unit European Unit of Account 17 (E.U.A.-17)",
            "xau" => "Gold",
            "xdr" => "SDR (Special Drawing Right)",
            "xag" => "Silver",
            "xpt" => "Platinum",
            "xts" => "Codes specifically reserved for testing purposes",
            "xpd" => "Palladium",
            "xua" => "ADB Unit of Account",
            "zmw" => "Zambian Kwacha",
            "srd" => "Surinam Dollar",
            "mga" => "Malagasy Ariary",
            "cou" => "Unidad de Valor Real",
            "afn" => "Afghani",
            "tjs" => "Somoni",
            "aoa" => "Kwanza",
            "byr" => "Belarussian Ruble",
            "bgn" => "Bulgarian Lev",
            "cdf" => "Congolese Franc",
            "bam" => "Convertible Mark",
            "eur" => "Euro",
            "mxv" => "Mexican Unidad de Inversion (UDI)",
            "uah" => "Hryvnia",
            "gel" => "Lari",
            "bov" => "Mvdol",
            "pln" => "Zloty",
            "brl" => "Brazilian Real",
            "clf" => "Unidad de Fomento",
            "xsu" => "Sucre",
            "usn" => "US Dollar (Next day)",
        ];

        public static function Get($code)
        {
            return Arr::Get($code, self::$codes);
        }

        public static function GetList()
        {
            return Arr::Get(null, self::$codes);
        }

        public static function IsSupported($code)
        {
            return Arr::Check($code, self::$codes);
        }
    }