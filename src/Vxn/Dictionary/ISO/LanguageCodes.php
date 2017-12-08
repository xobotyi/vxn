<?php

    namespace Vxn\Dictionary\ISO;

    final class LanguageCodes
    {
        private static $codes = [
            'ab'      => 'Abkhazian',
            'aa'      => 'Afar',
            'af'      => 'Afrikaans',
            'sq'      => 'Albanian',
            'am'      => 'Amharic',
            'ar'      => 'Arabic',
            'an'      => 'Aragonese',
            'hy'      => 'Armenian',
            'as'      => 'Assamese',
            'ay'      => 'Aymara',
            'az'      => 'Azerbaijani',
            'ba'      => 'Bashkir',
            'eu'      => 'Basque',
            'bn'      => 'Bengali (Bangla)',
            'dz'      => 'Bhutani',
            'bh'      => 'Bihari',
            'bi'      => 'Bislama',
            'br'      => 'Breton',
            'bg'      => 'Bulgarian',
            'my'      => 'Burmese',
            'be'      => 'Byelorussian (Belarusian)',
            'km'      => 'Cambodian',
            'ca'      => 'Catalan',
            'zh'      => 'Chinese',
            'zh-Hans' => 'Chinese (Simplified)',
            'zh-Hant' => 'Chinese (Traditional)',
            'co'      => 'Corsican',
            'hr'      => 'Croatian',
            'cs'      => 'Czech',
            'da'      => 'Danish',
            'nl'      => 'Dutch',
            'en'      => 'English',
            'eo'      => 'Esperanto',
            'et'      => 'Estonian',
            'fo'      => 'Faeroese',
            'fa'      => 'Farsi',
            'fj'      => 'Fiji',
            'fi'      => 'Finnish',
            'fr'      => 'French',
            'fy'      => 'Frisian',
            'gl'      => 'Galician',
            'gd'      => 'Gaelic (Scottish)',
            'gv'      => 'Gaelic (Manx)',
            'ka'      => 'Georgian',
            'de'      => 'German',
            'el'      => 'Greek',
            'kl'      => 'Greenlandic',
            'gn'      => 'Guarani',
            'gu'      => 'Gujarati',
            'ht'      => 'Haitian Creole',
            'ha'      => 'Hausa',
            'he'      => 'Hebrew',
            'iw'      => 'Hebrew',
            'hi'      => 'Hindi',
            'hu'      => 'Hungarian',
            'is'      => 'Icelandic',
            'io'      => 'Ido',
            'id'      => 'Indonesian',
            'in'      => 'Indonesian',
            'ia'      => 'Interlingua',
            'ie'      => 'Interlingue',
            'iu'      => 'Inuktitut',
            'ik'      => 'Inupiak',
            'ga'      => 'Irish',
            'it'      => 'Italian',
            'ja'      => 'Japanese',
            'jv'      => 'Javanese',
            'kn'      => 'Kannada',
            'ks'      => 'Kashmiri',
            'kk'      => 'Kazakh',
            'rw'      => 'Kinyarwanda (Ruanda)',
            'ky'      => 'Kirghiz',
            'rn'      => 'Kirundi (Rundi)',
            'ko'      => 'Korean',
            'ku'      => 'Kurdish',
            'lo'      => 'Laothian',
            'la'      => 'Latin',
            'lv'      => 'Latvian (Lettish)',
            'li'      => 'Limburgish ( Limburger)',
            'ln'      => 'Lingala',
            'lt'      => 'Lithuanian',
            'mk'      => 'Macedonian',
            'mg'      => 'Malagasy',
            'ms'      => 'Malay',
            'ml'      => 'Malayalam',
            'mt'      => 'Maltese',
            'mi'      => 'Maori',
            'mr'      => 'Marathi',
            'mo'      => 'Moldavian',
            'mn'      => 'Mongolian',
            'na'      => 'Nauru',
            'ne'      => 'Nepali',
            'no'      => 'Norwegian',
            'oc'      => 'Occitan',
            'or'      => 'Oriya',
            'om'      => 'Oromo (Afaan Oromo)',
            'ps'      => 'Pashto (Pushto)',
            'pl'      => 'Polish',
            'pt'      => 'Portuguese',
            'pa'      => 'Punjabi',
            'qu'      => 'Quechua',
            'rm'      => 'Rhaeto-Romance',
            'ro'      => 'Romanian',
            'ru'      => 'Russian',
            'sm'      => 'Samoan',
            'sg'      => 'Sangro',
            'sa'      => 'Sanskrit',
            'sr'      => 'Serbian',
            'sh'      => 'Serbo-Croatian',
            'st'      => 'Sesotho',
            'tn'      => 'Setswana',
            'sn'      => 'Shona',
            'ii'      => 'Sichuan Yi',
            'sd'      => 'Sindhi',
            'si'      => 'Sinhalese',
            'ss'      => 'Siswati',
            'sk'      => 'Slovak',
            'sl'      => 'Slovenian',
            'so'      => 'Somali',
            'es'      => 'Spanish',
            'su'      => 'Sundanese',
            'sw'      => 'Swahili (Kiswahili)',
            'sv'      => 'Swedish',
            'tl'      => 'Tagalog',
            'tg'      => 'Tajik',
            'ta'      => 'Tamil',
            'tt'      => 'Tatar',
            'te'      => 'Telugu',
            'th'      => 'Thai',
            'bo'      => 'Tibetan',
            'ti'      => 'Tigrinya',
            'to'      => 'Tonga',
            'ts'      => 'Tsonga',
            'tr'      => 'Turkish',
            'tk'      => 'Turkmen',
            'tw'      => 'Twi',
            'ug'      => 'Uighur',
            'uk'      => 'Ukrainian',
            'ur'      => 'Urdu',
            'uz'      => 'Uzbek',
            'vi'      => 'Vietnamese',
            'vo'      => 'Volapük',
            'wa'      => 'Wallon',
            'cy'      => 'Welsh',
            'wo'      => 'Wolof',
            'xh'      => 'Xhosa',
            'yi'      => 'Yiddish',
            'ji'      => 'Yiddish',
            'yo'      => 'Yoruba',
            'zu'      => 'Zulu',
        ];

        public static function GetName($code) :?string
        {
            return self::$codes[$code] ?? null;
        }

        public static function IsSupported($code) :bool
        {
            return isset(self::$codes[$code]);
        }

        public static function GetList() :array
        {
            return self::$codes;
        }
    }