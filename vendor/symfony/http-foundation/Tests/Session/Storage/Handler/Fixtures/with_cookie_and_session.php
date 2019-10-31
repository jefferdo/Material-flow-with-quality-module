<?php

require __DIR__.'/common.inc';

setcookie('abc', 'def');

session_set_save_handler(new TestSessionHandler('abc|i:123;'), false);
if (!isset($_SESSION)) {
            session_start();
        }
session_write_close();
if (!isset($_SESSION)) {
            session_start();
        }

$_SESSION['abc'] = 234;
unset($_SESSION['abc']);
