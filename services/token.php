<?php

use Picqer\Barcode\BarcodeGeneratorHTML;

class Token
{
    public static function sh1salt($pwd)
    {
        $postPwd = '';
        $pat = array(2, 3, 5);
        for ($i = 0; $i < strlen($pwd); $i++) {
            $postPwd .= $pwd[$i];
            for ($j = 0; $j < sizeof($pat); $j++) {
                $asciiE = ord($pwd[$i]) * ord($pwd[$i]);
                if ($asciiE % $pat[$j] == 0) {
                    $postPwd .= "\46\152\64\x73";
                }
            }
        }
        $postPwd = sha1($postPwd);
        return $postPwd;
    }
    public static function getToken($length)
    {
        $token = '';
        $codeAlphabet = "\x41\x42\103\x44\x45\106\x47\110\111\112\x4b\114\115\x4e\117\x50\121\122\x53\x54\x55\126\127\130\x59\132";
        $codeAlphabet .= "\141\x62\143\x64\x65\x66\x67\150\x69\152\x6b\x6c\155\156\157\160\x71\162\x73\x74\x75\166\167\170\171\172";
        $codeAlphabet .= "\x30\61\62\x33\64\65\66\x37\70\71";
        $max = strlen($codeAlphabet);
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }
        return $token;
    }
    public static function getTokenNoOnly($length)
    {
        $token = '';
        $codeAlphabet = "\60\61\62\63\64\65\x36\67\x38\71";
        $max = strlen($codeAlphabet);
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }
        return $token;
    }
    public static function setcsrfk()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION["\143\x73\162\146\153"] = Token::getToken(100);
        return $_SESSION["\143\x73\162\x66\153"];
    }
    public static function chkcsrfk($key)
    {
        if (!isset($_SESSION)) {
            if (!isset($_SESSION)) {
                session_start();
            }
        }
        if (isset($_SESSION["\143\163\162\x66\x6b"])) {
            if ($_SESSION["\143\163\x72\146\x6b"] == $key) {
                unset($_SESSION["\x63\163\x72\146\153"]);
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    public static function getBarcode($code)
    {
        $bar = new BarcodeGeneratorHTML();
        return $bar->getBarcode($code, $bar::TYPE_CODE_128);
    }
}
