<?php

$result = [];

function strToAscii(string $str, bool $hex = false) :string{
    $result = "";
    for($i = 0; $i < strlen($str); $i++){
        if($hex)    $result .= dechex(ord($str[$i]));
        else        $result .= ord($str[$i]);
    }
    return $result;
}

function asciiToStr(string $ascii) :string{
    $result = "";
    for($i = 0; $i < strlen($ascii) / 2; $i++){
        $hex = $ascii[$i*2] . $ascii[$i*2+1];
        $result .= chr(hexdec($hex));
    }
    return $result;
}

if(isset($_POST["text"]) && isset($_POST["key"]) && isset($_POST["mode"]) && $_POST["key"] != "" && $_POST["text"] != ""){
    $key = strToAscii($_POST["key"], true);
    $text = $_POST["text"];

    if($_POST["mode"] == "encrypt"){
        $text = strToAscii($_POST["text"], true);
        //暗号化
        //ASCIIの16進数表記に
        $result[] = $text;
        $result[] = $key;
        $keyHex = str_split(str_repeat($key, ceil(strlen($text)/strlen($key))), strlen($text))[0];
        $result["result"] = base64_encode(asciiToStr(bin2hex($text & $keyHex)));
    }
    else{
        //復号
        $keyHex = str_split(str_repeat($key, ceil(strlen($text)/strlen($key))), strlen($text))[0];
        $result["result"] = hex2bin(strToAscii(base64_decode($text)) & $keyHex);
    }
}
else{
    $result[] = false;
}

exit(json_encode($result, JSON_UNESCAPED_UNICODE));

?>