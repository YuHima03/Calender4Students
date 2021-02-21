<?php

include_once "../libs/C4S_main.php";

$account = new account("../");

if(isset($_POST["form_token"]) === isset($_SESSION["form_token"]) && !isset($account->getinfo()["id"])):
    $DB = new database("../");
    
    if(!$DB->connect()):
        echo "ERROR";
        exit();
    else:
?>

<!--ここからHTML-->
<!DOCTYPE html>
<html lang="ja">
<head>
    <title>アカウント無しで利用</title>
</head>
<body id="_no_signup">
    <p>ご利用のための準備をしています...</p>
    <p>少々お待ちください。</p>
</body>
</html>
<!--HTMLここまで-->

<?php
    //アカウント作成処理
    $new_account = new create_account("../");
    do{
        $name = "UNCLAIMED_" . rand_text();
        $pass = rand_text();
    }while(!$new_account->create($name, $pass, true, true));

    header("Location: ../home/");
    
    endif;
else:
    //トップページに飛ばす
    header("Location: ../");
endif;

?>