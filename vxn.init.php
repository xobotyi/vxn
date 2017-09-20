<?php
    /**
     * @Author : Anton Zinovyev
     */

    // loading vxn autoloader
    include_once __DIR__ . '/Vxn/Core/Autoloader.php';

    \Vxn\Core\Autoloader::Init();

    \Vxn\Core\Cfg::LoadPHP(__DIR__ . '/Cfg/Default.php');

    error_reporting(-1);
    ini_set('display_errors', \Vxn\Core\Cfg::Get('App.debug') ? 'On' : 'Off');