<?php

require __DIR__.'/../vendor/autoload.php';

try {
    $app = new \BladeBuilder\App(
        __DIR__.'/../views',
        __DIR__.'/../cache'
    );
    echo $app->renderView(trim($_SERVER['REQUEST_URI'], '/'));
} catch (Exception $e) {
    echo 'Oops... Server made a boo boo';
}
