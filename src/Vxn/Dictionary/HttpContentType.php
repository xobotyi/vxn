<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Dictionary;

    final class HttpContentType
    {
        // application
        public const APPLICATION_ATOM         = 'application/atom+xml';
        public const APPLICATION_EDIX12       = 'application/EDI-X12';
        public const APPLICATION_EDIFACT      = 'application/EDIFACT';
        public const APPLICATION_JSON         = 'application/json';
        public const APPLICATION_JAVASCRIPT   = 'application/javascript';
        public const APPLICATION_OCTET_STREAM = 'application/octet-stream';
        public const APPLICATION_OGG          = 'application/ogg';
        public const APPLICATION_PDF          = 'application/pdf';
        public const APPLICATION_POSTSCRIPT   = 'application/postscript';
        public const APPLICATION_SOAP         = 'application/soap+xml';
        public const APPLICATION_WOFF         = 'application/font-woff';
        public const APPLICATION_XHTML        = 'application/xhtml+xml';
        public const APPLICATION_DTD          = 'application/xml-dtd';
        public const APPLICATION_XOP          = 'application/xop+xml';
        public const APPLICATION_ZIP          = 'application/zip';
        public const APPLICATION_GZIP         = 'application/gzip';
        public const APPLICATION_BITTORRENT   = 'application/x-bittorrent';
        public const APPLICATION_TEX          = 'application/x-tex';
        public const APPLICATION_XML          = 'application/xml';

        // audio
        public const AUDIO_BASIC     = 'audio/basic';
        public const AUDIO_L24       = 'audio/L24';
        public const AUDIO_MP4       = 'audio/mp4';
        public const AUDIO_AAC       = 'audio/aac';
        public const AUDIO_MP3       = 'audio/mpeg';
        public const AUDIO_OGG       = 'audio/ogg';
        public const AUDIO_VORBIS    = 'audio/vorbis';
        public const AUDIO_WMA       = 'audio/x-ms-wma';
        public const AUDIO_WAX       = 'audio/x-ms-wax';
        public const AUDIO_REALAUDIO = 'audio/vnd.rn-realaudio';
        public const AUDIO_WAV       = 'audio/vnd.wave';
        public const AUDIO_WEBM      = 'audio/webm';

        // image
        public const IMAGE_GIF   = 'image/gif';
        public const IMAGE_JPEG  = 'image/jpeg';
        public const IMAGE_PJPEG = 'image/pjpeg';
        public const IMAGE_PNG   = 'image/png';
        public const IMAGE_SVG   = 'image/svg+xml';
        public const IMAGE_TIFF  = 'image/tiff';
        public const IMAGE_ICO   = 'image/vnd.microsoft.icon';
        public const IMAGE_WEBMP = 'image/vnd.wap.wbmp';
        public const IMAGE_WEBP  = 'image/webp';

        // message
        public const MESSAGE_HTTP     = 'message/http';
        public const MESSAGE_IMDN     = 'message/imdn+xml';
        public const MESSAGE_EMAIL    = 'message/partial';
        public const MESSAGE_EMAIL822 = 'message/rfc822';

        // model
        public const MODEL_EXAMPLE = 'model/example';
        public const MODEL_IGES    = 'model/iges';
        public const MODEL_MESH    = 'model/mesh';
        public const MODEL_VRML    = 'model/vrml';
        public const MODEL_X3DB    = 'model/x3d+binary';
        public const MODEL_X3DV    = 'model/x3d+vrml';
        public const MODEL_X3D     = 'model/x3d+xml';

        // multipart
        public const MULTIPART_MIXED       = 'multipart/mixed';
        public const MULTIPART_ALTERNATIVE = 'multipart/alternative';
        public const MULTIPART_RELATED     = 'multipart/related';
        public const MULTIPART_FORM_DATA   = 'multipart/form-data';
        public const MULTIPART_SIGNED      = 'multipart/signed';
        public const MULTIPART_ENCRYPTED   = 'multipart/encrypted';

        // text
        public const TEXT_CDM        = 'text/cmd';
        public const TEXT_CSS        = 'text/css';
        public const TEXT_CSV        = 'text/csv';
        public const TEXT_HTML       = 'text/html';
        public const TEXT_JAVASCRIPT = 'text/javascript';
        public const TEXT_PLAIN      = 'text/plain';
        public const TEXT_PHP        = 'text/php';
        public const TEXT_XML        = 'text/xml';
        public const TEXT_MARKDOWN   = 'text/markdown';

        // video
        public const VIDEO_MPEG      = 'video/mpeg';
        public const VIDEO_MP4       = 'video/mp4';
        public const VIDEO_OGG       = 'video/ogg';
        public const VIDEO_QUICKTIME = 'video/quicktime';
        public const VIDEO_WEBM      = 'video/webm';
        public const VIDEO_WMV       = 'video/x-ms-wmv';
        public const VIDEO_FLV       = 'video/x-flv';
        public const VIDEO_3GP       = 'video/3gpp';
        public const VIDEO_3G2       = 'video/3gpp2';

        // vnd
        public const VND_OPENDOCUMENT_TEXT         = 'application/vnd.oasis.opendocument.text';
        public const VND_OPENDOCUMENT_SPREADSHEET  = 'application/vnd.oasis.opendocument.spreadsheet';
        public const VND_OPENDOCUMENT_PRESENTATION = 'application/vnd.oasis.opendocument.presentation';
        public const VND_OPENDOCUMENT_GRAPHICS     = 'application/vnd.oasis.opendocument.graphics';
        public const VND_MS_EXCEL                  = 'application/vnd.ms-excel';
        public const VND_MS_EXCEL2007              = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        public const VND_MS_POWERPOINT             = 'application/vnd.ms-powerpoint';
        public const VND_MS_POWERPOINT2007         = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        public const VND_MS_WORD                   = 'application/msword';
        public const VND_MS_WORD2007               = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        public const VND_MOZILLA_XUL               = 'application/vnd.mozilla.xul+xml';
        public const VND_GOOGLE_KML                = 'application/vnd.google-earth.kml+xml';

        // x
        public const X_FORM_ENCODED = 'application/x-www-form-urlencoded';
        public const X_DVI          = 'application/x-dvi';
        public const X_LATEX        = 'application/x-latex';
        public const X_TTF          = 'application/x-font-ttf';
        public const X_FLASH        = 'application/x-shockwave-flash';
        public const X_STUFFIT      = 'application/x-stuffit';
        public const X_RAR          = 'application/x-rar-compressed';
        public const X_TAR          = 'application/x-tar';
        public const X_TMPL         = 'text/x-jquery-tmpl';
        public const X_JAVASCRIPT   = 'application/x-javascript';

        // x-pkcs
        public const X_PKCS12             = 'application/x-pkcs12';
        public const X_PKCS7_CERTIFICATES = 'application/x-pkcs7-certificates';
        public const X_PKCS7_CERTREQRESP  = 'application/x-pkcs7-certreqresp';
        public const X_PKCS7_MIME         = 'application/x-pkcs7-mime';
        public const X_PKCS7_SIGNATURE    = 'application/x-pkcs7-signature';

        private static $typesList = [
            self::APPLICATION_ATOM,
            self::APPLICATION_EDIX12,
            self::APPLICATION_EDIFACT,
            self::APPLICATION_JSON,
            self::APPLICATION_JAVASCRIPT,
            self::APPLICATION_OCTET_STREAM,
            self::APPLICATION_OGG,
            self::APPLICATION_PDF,
            self::APPLICATION_POSTSCRIPT,
            self::APPLICATION_SOAP,
            self::APPLICATION_WOFF,
            self::APPLICATION_XHTML,
            self::APPLICATION_DTD,
            self::APPLICATION_XOP,
            self::APPLICATION_ZIP,
            self::APPLICATION_GZIP,
            self::APPLICATION_BITTORRENT,
            self::APPLICATION_TEX,
            self::APPLICATION_XML,
            self::AUDIO_BASIC,
            self::AUDIO_L24,
            self::AUDIO_MP4,
            self::AUDIO_AAC,
            self::AUDIO_MP3,
            self::AUDIO_OGG,
            self::AUDIO_VORBIS,
            self::AUDIO_WMA,
            self::AUDIO_WAX,
            self::AUDIO_REALAUDIO,
            self::AUDIO_WAV,
            self::AUDIO_WEBM,
            self::IMAGE_GIF,
            self::IMAGE_JPEG,
            self::IMAGE_PJPEG,
            self::IMAGE_PNG,
            self::IMAGE_SVG,
            self::IMAGE_TIFF,
            self::IMAGE_ICO,
            self::IMAGE_WEBMP,
            self::IMAGE_WEBP,
            self::MESSAGE_HTTP,
            self::MESSAGE_IMDN,
            self::MESSAGE_EMAIL,
            self::MESSAGE_EMAIL822,
            self::MODEL_EXAMPLE,
            self::MODEL_IGES,
            self::MODEL_MESH,
            self::MODEL_VRML,
            self::MODEL_X3DB,
            self::MODEL_X3DV,
            self::MODEL_X3D,
            self::MULTIPART_MIXED,
            self::MULTIPART_ALTERNATIVE,
            self::MULTIPART_RELATED,
            self::MULTIPART_FORM_DATA,
            self::MULTIPART_SIGNED,
            self::MULTIPART_ENCRYPTED,
            self::TEXT_CDM,
            self::TEXT_CSS,
            self::TEXT_CSV,
            self::TEXT_HTML,
            self::TEXT_JAVASCRIPT,
            self::TEXT_PLAIN,
            self::TEXT_PHP,
            self::TEXT_XML,
            self::TEXT_MARKDOWN,
            self::VIDEO_MPEG,
            self::VIDEO_MP4,
            self::VIDEO_OGG,
            self::VIDEO_QUICKTIME,
            self::VIDEO_WEBM,
            self::VIDEO_WMV,
            self::VIDEO_FLV,
            self::VIDEO_3GP,
            self::VIDEO_3G2,
            self::VND_OPENDOCUMENT_TEXT,
            self::VND_OPENDOCUMENT_SPREADSHEET,
            self::VND_OPENDOCUMENT_PRESENTATION,
            self::VND_OPENDOCUMENT_GRAPHICS,
            self::VND_MS_EXCEL,
            self::VND_MS_EXCEL2007,
            self::VND_MS_POWERPOINT,
            self::VND_MS_POWERPOINT2007,
            self::VND_MS_WORD,
            self::VND_MS_WORD2007,
            self::VND_MOZILLA_XUL,
            self::VND_GOOGLE_KML,
            self::X_FORM_ENCODED,
            self::X_DVI,
            self::X_LATEX,
            self::X_TTF,
            self::X_FLASH,
            self::X_STUFFIT,
            self::X_RAR,
            self::X_TAR,
            self::X_TMPL,
            self::X_JAVASCRIPT,
            self::X_PKCS12,
            self::X_PKCS7_CERTIFICATES,
            self::X_PKCS7_CERTREQRESP,
            self::X_PKCS7_MIME,
            self::X_PKCS7_SIGNATURE,
        ];

        public static function IsSupported($type) :bool
        {
            return in_array($type, self::$typesList);
        }
    }