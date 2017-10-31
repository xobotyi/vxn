<?php
    /**
     * @Author : Anton Zinovyev
     */

    $cfg = [];

    $cfg['App']['host']  = gethostname();
    $cfg['App']['debug'] = true;

    $cfg['App']['path']['base']  = realpath(__DIR__ . '/../');
    $cfg['App']['path']['cfg']   = $cfg['App']['path']['base'] . '/Cfg';
    $cfg['App']['path']['log']   = $cfg['App']['path']['base'] . '/log';
    $cfg['App']['path']['cache'] = $cfg['App']['path']['base'] . '/cache';
    $cfg['App']['path']['tmp']   = $cfg['App']['path']['base'] . '/tmp';

    $cfg['App']['path']['app']      = $cfg['App']['path']['base'] . '/App';
    $cfg['App']['path']['i18n']     = $cfg['App']['path']['app'] . '/I18n';
    $cfg['App']['path']['template'] = $cfg['App']['path']['app'] . '/Template';

    // DATABASES
    $cfg['Vxn']['storage']['postgres']['host']     = '127.0.0.1';
    $cfg['Vxn']['storage']['postgres']['port']     = 5432;
    $cfg['Vxn']['storage']['postgres']['user']     = 'test';
    $cfg['Vxn']['storage']['postgres']['pass']     = 'test';
    $cfg['Vxn']['storage']['postgres']['dbname']   = 'test';
    $cfg['Vxn']['storage']['postgres']['encoding'] = 'UTF8';
    $cfg['Vxn']['storage']['postgres']['useCache'] = true;

    // CACHE
    $cfg['Vxn']['cache']['memcache']['host']           = '127.0.0.1';
    $cfg['Vxn']['cache']['memcache']['port']           = 11211;
    $cfg['Vxn']['cache']['memcache']['useTagsBuffer']  = true;
    $cfg['Vxn']['cache']['memcache']['ttl']['default'] = 60;
    $cfg['Vxn']['cache']['memcache']['ttl']['tags']    = 604800;
    $cfg['Vxn']['cache']['memcache']['ttl']['max']     = 2592000;

    return $cfg;