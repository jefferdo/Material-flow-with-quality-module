<?php
goto S6UaX;
f5Qpb: ini_set("\144\151\163\x70\x6c\x61\x79\137\145\162\162\x6f\x72\163", 1);
goto aZJAD;
S6UaX: error_reporting(E_ALL);
goto f5Qpb;
GNCJa: include_once $_SERVER["\104\117\x43\125\x4d\105\116\124\137\x52\117\x4f\124"] . "\x2f\163\145\162\x76\151\x63\x65\163\57\144\141\x74\x61\142\x61\163\145\56\x70\150\x70";
goto W3Bza;
W3Bza: include_once $_SERVER["\x44\117\103\125\115\105\116\x54\x5f\122\117\117\x54"] . "\57\163\x65\162\166\x69\143\x65\x73\57\164\157\x6b\145\x6e\56\160\150\x70";
goto bNIhQ;
bNIhQ: class Secure
{
    public function secureLog()
    {
        $stat = 0;
        $db = new Database();
        if (isset($_COOKIE["\165\151\x64"]) && isset($_COOKIE["\x6b\145\171"])) {
            $stat = 1;
            $query = "\x73\145\154\x65\x63\164\x20\x2a\40\146\162\x6f\155\x20\x75\x6d\146\40\x77\150\145\x72\145\x20\x75\x69\144\x3d\x27{$_COOKIE["\x75\151\x64"]}\47";
            if ($result = $db->select($query)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($_COOKIE["\153\145\171"] == $row["\153\x65\x79\x52"]) {
                        $stat = 1;
                    } else {
                        $this->logout();
                        $stat = 0;
                    }
                }
            }
        } else {
            $stat = 0;
            $this->logout();
        }
        return $stat;
    }
    public function logout()
    {
        if (isset($_SERVER["\110\x54\124\x50\137\103\117\x4f\x4b\x49\105"])) {
            $cookies = explode("\x3b", $_SERVER["\x48\124\x54\x50\137\103\117\117\113\111\x45"]);
            foreach ($cookies as $cookie) {
                $parts = explode("\x3d", $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, "\x2f");
                header("\114\x6f\x63\x61\164\151\157\156\72" . "\57" . "\143\x6d\x73\57");
            }
        }
    }
}
goto J3y0K;
aZJAD: error_reporting(0);
goto GNCJa;
J3y0K:
