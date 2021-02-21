<?php

include "../libs/C4S_main.php";

$page = new page(1);

if($page->get_account_info()["login"]){
    //ログイン済みの場合は転送
    header("Location: ../home/");
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
            $account_name = $_POST['account_id'];
            $pass = hash("sha512", $_POST['pass']);
            $auto_login = (int)(isset($_POST['auto_login']) && $_POST['auto_login'] == "on");

            //れっつら照合
            $sql = "SELECT `uuid` FROM `account` WHERE `name`=? AND `password`=?";
            $stmt = $DB->getPDO()->prepare($sql);

            if($stmt->execute([$account_name, $pass])){
                $uuid = $stmt->fetch(PDO::FETCH_ASSOC)["uuid"];

                $sql = "INSERT INTO `login_session` (`uuid`, `start_date`, `session_token`, `cookie_token`, `auto_login`) VALUES (?, ?, ?, ?, ?)";
                $stmt = $DB->getPDO()->prepare($sql);
                do{
                    $now = date("Y-m-d", time());
                    $token = [rand_text(), rand_text()];
                    //セッションをDBに登録
                }while(!$stmt->execute([$uuid, $now, $token[0], $token[1], $auto_login]));

                //セッションとクッキーにそれぞれトークンを保存
                $_SESSION['_token'] = $token[0];
                setcookie("_token", $token[1], time()+60*60*24*30, "/", "", false, true);

                //転送
                header("Location: ../home/");
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
<body id="_login">
    <main>
        <div id="container">
            <div class="title">
                <h2>ログイン</h2>
            </div>
            <div>
                <form action="" method="POST">
                    <p>アカウント名</p>
                    <input type="text" name="account_id" value="<?=(isset($_POST['account_id']))?"{$_POST['account_id']}":"";?>" />
                    <p>パスワード</p>
                    <input type="password" name="pass" />
                    <input type="hidden" name="form_token" value="<?=$form_token?>" />
                    <input type="submit" value="ログイン" />
                    <input type="checkbox" name="auto_login"/><p>自動ログイン</p>
                </form>
            </div>
        </div>
    </main>
</body>
</html>