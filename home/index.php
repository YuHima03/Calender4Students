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
<?php $page->gen_page("head", $page->add_css(["style/main.css", "style/home.css"]) . $page->add_js(["js/main.js", "js/home.js"]) . $page->sw_reg()); ?>
<body id="_home">
<div id="main_container">
    <!--header-->
    <?php $page->gen_page("body/header", ["login_info" => $page->get_account_info()]); ?>
    <!--main-->
    <main>
        <h1>HOME</h1>
        <div id="clock"></div>
        <div id="calender">
            <div></div>
        </div>
    </main>
    <!--footer-->
    <?php $page->gen_page("body/footer"); ?>
</div>
</body>
</html>