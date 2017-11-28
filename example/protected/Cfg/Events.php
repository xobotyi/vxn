<?php
    /**
     * @Author : Anton Zinovyev
     */

    $cfg = [];

    $cfg['App']['events']['maxListeners'] = 10;

    // EXAMPLE
    //    $cfg['App']['events']['events'] = [
    //        'EventMediator:selfTest' => [
    //            [
    //                'listener' => ["\Vxn\Core\EventMediator", "SelfTest"],
    //                'once'     => true,
    //            ],
    //        ],
    //    ];

    return $cfg;