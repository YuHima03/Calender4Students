<?php

//////////////////////////////////////////////////
//いろいろ設定
include_once "./libs/C4S_main.php";

$page = new page(0);

if($page->get_account_info()["login"]){
    //ログイン済みの場合は転送
    header("Location: /home/");
    exit();
}

$page->set_info([
    "TITLE" => "ようこそ"
]);

$page->set_gen_option([
    
]);

//////////////////////////////////////////////////
//出力
?>

<?php
$page->gen_page("HEAD");
?>

<?php
$page->gen_page("ALL");
?>