<?php

include_once "../libs/C4S_main.php";

$page = new page(1);

$page->set_info([
    "TITLE" =>  "ホーム"
]);

if(!$page->get_account_info()["login"]){
    //ログインしてない
    header("Location: ../");
}

?>

<!DOCTYPE html>
<html lang="ja">
<?php $page->gen_page("head", $page->add_css(["style/main.css"]) . $page->add_js(["js/main.js"])); ?>
<body id="_home">
<!--header-->
<?php $page->gen_page("body/header", ["login_info" => $page->get_account_info()]); ?>
<!--main-->
<main>
    <h1>HOME</h1>
</main>
<!--footer-->
<?php $page->gen_page("body/footer"); ?>
</body>
</html>