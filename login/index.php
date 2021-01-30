<?php

include "../libs/C4S_main.php";

$page = new page(1);

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
if(isset($_POST['account_id']) && isset($_POST['pass']) && isset($_POST['form_token'])){
    //送信元確認のやつ照合
    if($_SESSION['form_token'] == $_POST['form_token']){
        unset($_SESSION['form_token']);

        $DB = new database("../");
        if($DB->connect()){
            //パスワードはハッシュ化したもので照合
            $account_id = $_POST['account_id'];
            $pass = hash("sha512", $_POST['pass']);

            //れっつら照合
            if($DB->execute("SELECT * FROM `account` WHERE `name`='{$account_id}' AND `password`='{$pass}'")){
                echo "成功";
            }
            else{
                echo "IDまたはパスワードが違います、もう一度ご確認ください";
            }

            //接続解除
            $DB->disconnect();
        }
        else{
            echo "エラーが発生しました、時間を空けてもう一度お試しください。";
        }
    }
}

//送信元確認用のトークン生成
$form_token = rand_text();
$_SESSION['form_token'] = $form_token;

?>

<!DOCTYPE html>
<html lang="ja">
<?php $page->gen_page("head", $page->add_css(["style/main.css"]) . $page->add_js(["js/main.js", "js/login.js"])); ?>
<body>
    <main>
        <div id="container">
            <div class="title">
                <h2>ログイン</h2>
            </div>
            <div>
                <form action="" method="POST">
                    <p>ID</p>
                    <input type="text" name="account_id" value="<?=(isset($_POST['account_id']))?"{$_POST['account_id']}":"";?>" />
                    <p>パスワード</p>
                    <input type="password" name="pass" />
                    <input type="hidden" name="form_token" value="<?=$form_token?>" />
                    <input type="submit" value="ログイン" />
                </form>
            </div>
        </div>
    </main>
</body>
</html>