<?php

include_once "../libs/C4S_main.php";

$page = new page(1);
$page->set_info([
    "TITLE" =>  "ログアウト中"
]);

$account = new account("../");
$account->logout("force");

header("Location: ../");

?>