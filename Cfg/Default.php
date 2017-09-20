<?php
    /**
     * @Author : Anton Zinovyev
     */

    $cfg = [];

    $cfg['App']['host']  = gethostname();
    $cfg['App']['debug'] = true;

    $cfg['Vxn']['storage']['postgres']['host'] = '127.0.0.1';

    return $cfg;