<?php

class Token
{
    public static function sh1salt($pwd)
    {
        $postPwd = "";
        $pat = array(2, 3, 5);
        for ($i = 0; $i < strlen($pwd); $i++) {
            $postPwd .= $pwd[$i];
            for ($j = 0; $j < sizeof($pat); $j++) {
                $asciiE = ord($pwd[$i]) * ord($pwd[$i]);
                if ($asciiE % $pat[$j] == 0) {
                    $postPwd .= "&j4s";
                }
            }
        }
        $postPwd = sha1($postPwd);
        return $postPwd;
    }

    public static function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return $token;
    }

    public static function getTokenNoOnly($length)
    {
        $token = "";
        $codeAlphabet = "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return $token;
    }

    public static function setcsrfk()
    {
        session_start();
        $_SESSION['csrfk'] = Token::getToken(100);
        return $_SESSION['csrfk'];
    }

    public static function chkcsrfk($key)
    {
        session_start();
        if ($_SESSION['csrfk'] == $key) {
            return 1;
        } else {
            return 0;
        }
    }
}
