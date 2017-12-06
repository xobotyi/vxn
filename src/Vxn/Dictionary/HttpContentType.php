<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Dictionary;

    final class HttpContentType
    {
        // application
        public const TYPE_APPLICATION_ATOM         = 'application/atom+xml';
        public const TYPE_APPLICATION_EDIX12       = 'application/EDI-X12';
        public const TYPE_APPLICATION_EDIFACT      = 'application/EDIFACT';
        public const TYPE_APPLICATION_JSON         = 'application/json';
        public const TYPE_APPLICATION_JAVASCRIPT   = 'application/javascript';
        public const TYPE_APPLICATION_OCTET_STREAM = 'application/octet-stream';
        public const TYPE_APPLICATION_OGG          = 'application/ogg';
        public const TYPE_APPLICATION_PDF          = 'application/pdf';
        public const TYPE_APPLICATION_POSTSCRIPT   = 'application/postscript';
        public const TYPE_APPLICATION_SOAP         = 'application/soap+xml';
        public const TYPE_APPLICATION_WOFF         = 'application/font-woff';
        public const TYPE_APPLICATION_XHTML        = 'application/xhtml+xml';
        public const TYPE_APPLICATION_DTD          = 'application/xml-dtd';
        public const TYPE_APPLICATION_XOP          = 'application/xop+xml';
        public const TYPE_APPLICATION_ZIP          = 'application/zip';
        public const TYPE_APPLICATION_GZIP         = 'application/gzip';
        public const TYPE_APPLICATION_BITTORRENT   = 'application/x-bittorrent';
        public const TYPE_APPLICATION_TEX          = 'application/x-tex';
        public const TYPE_APPLICATION_XML          = 'application/xml';

        // audio
        public const TYPE_AUDIO_BASIC     = 'audio/basic';
        public const TYPE_AUDIO_L24       = 'audio/L24';
        public const TYPE_AUDIO_MP4       = 'audio/mp4';
        public const TYPE_AUDIO_AAC       = 'audio/aac';
        public const TYPE_AUDIO_MP3       = 'audio/mpeg';
        public const TYPE_AUDIO_OGG       = 'audio/ogg';
        public const TYPE_AUDIO_VORBIS    = 'audio/vorbis';
        public const TYPE_AUDIO_WMA       = 'audio/x-ms-wma';
        public const TYPE_AUDIO_WAX       = 'audio/x-ms-wax';
        public const TYPE_AUDIO_REALAUDIO = 'audio/vnd.rn-realaudio';
        public const TYPE_AUDIO_WAV       = 'audio/vnd.wave';
        public const TYPE_AUDIO_WEBM      = 'audio/webm';

        // image
        public const TYPE_IMAGE_GIF   = 'image/gif';
        public const TYPE_IMAGE_JPEG  = 'image/jpeg';
        public const TYPE_IMAGE_PJPEG = 'image/pjpeg';
        public const TYPE_IMAGE_PNG   = 'image/png';
        public const TYPE_IMAGE_SVG   = 'image/svg+xml';
        public const TYPE_IMAGE_TIFF  = 'image/tiff';
        public const TYPE_IMAGE_ICO   = 'image/vnd.microsoft.icon';
        public const TYPE_IMAGE_WEBMP = 'image/vnd.wap.wbmp';
        public const TYPE_IMAGE_WEBP  = 'image/webp';

        // message
        public const TYPE_MESSAGE_HTTP     = 'message/http';
        public const TYPE_MESSAGE_IMDN     = 'message/imdn+xml';
        public const TYPE_MESSAGE_EMAIL    = 'message/partial';
        public const TYPE_MESSAGE_EMAIL822 = 'message/rfc822';

        // model
        public const TYPE_MODEL_EXAMPLE = 'model/example';
        public const TYPE_MODEL_IGES    = 'model/iges';
        public const TYPE_MODEL_MESH    = 'model/mesh';
        public const TYPE_MODEL_VRML    = 'model/vrml';
        public const TYPE_MODEL_X3DB    = 'model/x3d+binary';
        public const TYPE_MODEL_X3DV    = 'model/x3d+vrml';
        public const TYPE_MODEL_X3D     = 'model/x3d+xml';

        // multipart
        public const TYPE_MULTIPART_MIXED       = 'multipart/mixed';
        public const TYPE_MULTIPART_ALTERNATIVE = 'multipart/alternative';
        public const TYPE_MULTIPART_RELATED     = 'multipart/related';
        public const TYPE_MULTIPART_FORM_DATA   = 'multipart/form-data';
        public const TYPE_MULTIPART_SIGNED      = 'multipart/signed';
        public const TYPE_MULTIPART_ENCRYPTED   = 'multipart/encrypted';

        // text
        public const TYPE_TEXT_CDM        = 'text/cmd';
        public const TYPE_TEXT_CSS        = 'text/css';
        public const TYPE_TEXT_CSV        = 'text/csv';
        public const TYPE_TEXT_HTML       = 'text/html';
        public const TYPE_TEXT_JAVASCRIPT = 'text/javascript';
        public const TYPE_TEXT_PLAIN      = 'text/plain';
        public const TYPE_TEXT_PHP        = 'text/php';
        public const TYPE_TEXT_XML        = 'text/xml';
        public const TYPE_TEXT_MARKDOWN   = 'text/markdown';

        // video
        public const TYPE_VIDEO_MPEG      = 'video/mpeg';
        public const TYPE_VIDEO_MP4       = 'video/mp4';
        public const TYPE_VIDEO_OGG       = 'video/ogg';
        public const TYPE_VIDEO_QUICKTIME = 'video/quicktime';
        public const TYPE_VIDEO_WEBM      = 'video/webm';
        public const TYPE_VIDEO_WMV       = 'video/x-ms-wmv';
        public const TYPE_VIDEO_FLV       = 'video/x-flv';
        public const TYPE_VIDEO_3GP       = 'video/3gpp';
        public const TYPE_VIDEO_3G2       = 'video/3gpp2';

        // vnd
        public const TYPE_VND_OPENDOCUMENT_TEXT         = 'application/vnd.oasis.opendocument.text';
        public const TYPE_VND_OPENDOCUMENT_SPREADSHEET  = 'application/vnd.oasis.opendocument.spreadsheet';
        public const TYPE_VND_OPENDOCUMENT_PRESENTATION = 'application/vnd.oasis.opendocument.presentation';
        public const TYPE_VND_OPENDOCUMENT_GRAPHICS     = 'application/vnd.oasis.opendocument.graphics';
        public const TYPE_VND_MS_EXCEL                  = 'application/vnd.ms-excel';
        public const TYPE_VND_MS_EXCEL2007              = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        public const TYPE_VND_MS_POWERPOINT             = 'application/vnd.ms-powerpoint';
        public const TYPE_VND_MS_POWERPOINT2007         = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        public const TYPE_VND_MS_WORD                   = 'application/msword';
        public const TYPE_VND_MS_WORD2007               = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        public const TYPE_VND_MOZILLA_XUL               = 'application/vnd.mozilla.xul+xml';
        public const TYPE_VND_GOOGLE_KML                = 'application/vnd.google-earth.kml+xml';

        // x
        public const TYPE_X_FORM_ENCODED = 'application/x-www-form-urlencoded';
        public const TYPE_X_DVI          = 'application/x-dvi';
        public const TYPE_X_LATEX        = 'application/x-latex';
        public const TYPE_X_TTF          = 'application/x-font-ttf';
        public const TYPE_X_FLASH        = 'application/x-shockwave-flash';
        public const TYPE_X_STUFFIT      = 'application/x-stuffit';
        public const TYPE_X_RAR          = 'application/x-rar-compressed';
        public const TYPE_X_TAR          = 'application/x-tar';
        public const TYPE_X_TMPL         = 'text/x-jquery-tmpl';
        public const TYPE_X_JAVASCRIPT   = 'application/x-javascript';

        // x-pkcs
        public const TYPE_X_PKCS12             = 'application/x-pkcs12';
        public const TYPE_X_PKCS7_CERTIFICATES = 'application/x-pkcs7-certificates';
        public const TYPE_X_PKCS7_CERTREQRESP  = 'application/x-pkcs7-certreqresp';
        public const TYPE_X_PKCS7_MIME         = 'application/x-pkcs7-mime';
        public const TYPE_X_PKCS7_SIGNATURE    = 'application/x-pkcs7-signature';

        private static $typesList = [
            self::TYPE_APPLICATION_ATOM,
            self::TYPE_APPLICATION_EDIX12,
            self::TYPE_APPLICATION_EDIFACT,
            self::TYPE_APPLICATION_JSON,
            self::TYPE_APPLICATION_JAVASCRIPT,
            self::TYPE_APPLICATION_OCTET_STREAM,
            self::TYPE_APPLICATION_OGG,
            self::TYPE_APPLICATION_PDF,
            self::TYPE_APPLICATION_POSTSCRIPT,
            self::TYPE_APPLICATION_SOAP,
            self::TYPE_APPLICATION_WOFF,
            self::TYPE_APPLICATION_XHTML,
            self::TYPE_APPLICATION_DTD,
            self::TYPE_APPLICATION_XOP,
            self::TYPE_APPLICATION_ZIP,
            self::TYPE_APPLICATION_GZIP,
            self::TYPE_APPLICATION_BITTORRENT,
            self::TYPE_APPLICATION_TEX,
            self::TYPE_APPLICATION_XML,
            self::TYPE_AUDIO_BASIC,
            self::TYPE_AUDIO_L24,
            self::TYPE_AUDIO_MP4,
            self::TYPE_AUDIO_AAC,
            self::TYPE_AUDIO_MP3,
            self::TYPE_AUDIO_OGG,
            self::TYPE_AUDIO_VORBIS,
            self::TYPE_AUDIO_WMA,
            self::TYPE_AUDIO_WAX,
            self::TYPE_AUDIO_REALAUDIO,
            self::TYPE_AUDIO_WAV,
            self::TYPE_AUDIO_WEBM,
            self::TYPE_IMAGE_GIF,
            self::TYPE_IMAGE_JPEG,
            self::TYPE_IMAGE_PJPEG,
            self::TYPE_IMAGE_PNG,
            self::TYPE_IMAGE_SVG,
            self::TYPE_IMAGE_TIFF,
            self::TYPE_IMAGE_ICO,
            self::TYPE_IMAGE_WEBMP,
            self::TYPE_IMAGE_WEBP,
            self::TYPE_MESSAGE_HTTP,
            self::TYPE_MESSAGE_IMDN,
            self::TYPE_MESSAGE_EMAIL,
            self::TYPE_MESSAGE_EMAIL822,
            self::TYPE_MODEL_EXAMPLE,
            self::TYPE_MODEL_IGES,
            self::TYPE_MODEL_MESH,
            self::TYPE_MODEL_VRML,
            self::TYPE_MODEL_X3DB,
            self::TYPE_MODEL_X3DV,
            self::TYPE_MODEL_X3D,
            self::TYPE_MULTIPART_MIXED,
            self::TYPE_MULTIPART_ALTERNATIVE,
            self::TYPE_MULTIPART_RELATED,
            self::TYPE_MULTIPART_FORM_DATA,
            self::TYPE_MULTIPART_SIGNED,
            self::TYPE_MULTIPART_ENCRYPTED,
            self::TYPE_TEXT_CDM,
            self::TYPE_TEXT_CSS,
            self::TYPE_TEXT_CSV,
            self::TYPE_TEXT_HTML,
            self::TYPE_TEXT_JAVASCRIPT,
            self::TYPE_TEXT_PLAIN,
            self::TYPE_TEXT_PHP,
            self::TYPE_TEXT_XML,
            self::TYPE_TEXT_MARKDOWN,
            self::TYPE_VIDEO_MPEG,
            self::TYPE_VIDEO_MP4,
            self::TYPE_VIDEO_OGG,
            self::TYPE_VIDEO_QUICKTIME,
            self::TYPE_VIDEO_WEBM,
            self::TYPE_VIDEO_WMV,
            self::TYPE_VIDEO_FLV,
            self::TYPE_VIDEO_3GP,
            self::TYPE_VIDEO_3G2,
            self::TYPE_VND_OPENDOCUMENT_TEXT,
            self::TYPE_VND_OPENDOCUMENT_SPREADSHEET,
            self::TYPE_VND_OPENDOCUMENT_PRESENTATION,
            self::TYPE_VND_OPENDOCUMENT_GRAPHICS,
            self::TYPE_VND_MS_EXCEL,
            self::TYPE_VND_MS_EXCEL2007,
            self::TYPE_VND_MS_POWERPOINT,
            self::TYPE_VND_MS_POWERPOINT2007,
            self::TYPE_VND_MS_WORD,
            self::TYPE_VND_MS_WORD2007,
            self::TYPE_VND_MOZILLA_XUL,
            self::TYPE_VND_GOOGLE_KML,
            self::TYPE_X_FORM_ENCODED,
            self::TYPE_X_DVI,
            self::TYPE_X_LATEX,
            self::TYPE_X_TTF,
            self::TYPE_X_FLASH,
            self::TYPE_X_STUFFIT,
            self::TYPE_X_RAR,
            self::TYPE_X_TAR,
            self::TYPE_X_TMPL,
            self::TYPE_X_JAVASCRIPT,
            self::TYPE_X_PKCS12,
            self::TYPE_X_PKCS7_CERTIFICATES,
            self::TYPE_X_PKCS7_CERTREQRESP,
            self::TYPE_X_PKCS7_MIME,
            self::TYPE_X_PKCS7_SIGNATURE,
        ];

        public static function IsSupported($type) :bool
        {
            return in_array($type, self::$typesList);
        }
    }