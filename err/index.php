<?php
/**
 * やばいエラーが発生した時の処理
 */

$errCode = (isset($_GET["errcode"])) ? $_GET["errcode"] : "Unknown";
$locateTo = (isset($_GET["to"])) ? $_GET["to"] : "/";

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エラーが発生しました</title>
</head>
<body>
    <main>
        <div id="msg">
            <div>
                <h3>エラーが発生しました</h3>
                <div class="detail">
                    <p class="err_code">エラーコード：<?=$errCode?></p>
                    <p>処理中に重大なエラーが発生しました</p>
                    <button onclick="location.href = '..<?=$locateTo?>'">続行<span style="margin-left: 0.5em;font-size: 12px;">(目的のページへ移動)</span></button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>