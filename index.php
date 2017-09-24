<?php
    /**
     * @Author : Anton Zinovyev
     */

    include_once __DIR__ . '/vxn.init.php';

    // Loading Events config and initialising EventMediator
    \Vxn\Core\Cfg::LoadPHP(__DIR__ . '/Cfg/Events.php');
    \Vxn\Core\EventMediator::Init();