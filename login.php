<?php

include "./libs/C4S_main.php";

$page = new page(0);

if($page->get_account_info()["login"]){
    //ログイン済みの場合は転送
    header("Location: /home/");
    exit();
}

$page->set_info([
    "TITLE" =>  "ログイン"
]);

//////////////////////////////////////////////////
//POSTデータ受け取り
if(isset($_POST['id']) && isset($_POST['pass'])){

}

?>

<!DOCTYPE html>
<html lang="ja">
<?php $page->gen_page("head", $page->add_css(["style/main.css"])); ?>
<body>
    <main>
        <div id="container">
            <div class="title">
                <h2>ログイン</h2>
            </div>
        </div>
    </main>
</body>
</html>