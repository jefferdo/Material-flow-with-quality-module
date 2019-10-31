<?php

require __DIR__.'/common.inc';

session_set_save_handler(new TestSessionHandler('abc|i:123;'), false);
if (!isset($_SESSION)) {
            session_start();
        }

setcookie('abc', 'def');
