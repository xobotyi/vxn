<?php
    /**
     * @Author : Anton Zinovyev
     */

    $cfg = [];

    $cfg['App']['host']  = gethostname();
    $cfg['App']['debug'] = true;

    // DATABASES
    $cfg['Vxn']['storage']['postgres']['host']     = '127.0.0.1';
    $cfg['Vxn']['storage']['postgres']['port']     = 5432;
    $cfg['Vxn']['storage']['postgres']['user']     = '';
    $cfg['Vxn']['storage']['postgres']['pass']     = '';
    $cfg['Vxn']['storage']['postgres']['dbname']   = '';
    $cfg['Vxn']['storage']['postgres']['encoding'] = 'UTF8';

    // CACHE
    $cfg['Vxn']['cache']['memcache']['host']           = '127.0.0.1';
    $cfg['Vxn']['cache']['memcache']['port']           = 11211;
    $cfg['Vxn']['cache']['memcache']['ttl']['default'] = 60;
    $cfg['Vxn']['cache']['memcache']['ttl']['max']     = 2592000;

    return $cfg;