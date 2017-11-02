<?php
    /**
     * @Author : Anton Zinovyev
     */

    use Vxn\Application\Routing\Middleware;

    // HERE SHOULD BE DESCRIBED APP MIDDLEWARES

    $globalPre  = [];
    $globalPost = [];

    $aliases = [];

    foreach ($globalPre as $pre) {
        Middleware::RegisterPre($pre);
    }
    foreach ($globalPost as $post) {
        Middleware::RegisterPre($post);
    }
    foreach ($aliases as $name => $alias) {
        Middleware::RegisterAlias($name, $alias);
    }