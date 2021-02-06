<?php

//ヘッダー
if($option == "header" || !isset($option)):


$account_info = $inner_html["login_info"];

?>

<header>
    <h1>Calender 4 Students</h1>
    <!--account-->
    <div>
        <p>ようこそ<?=$account_info["name"]?></p>
    </div>
</header>

<?php
//フッター
elseif($option == "footer" || !isset($option)): ?>

<footer>
    <div>
        <h4>FOOTER</h4>
    </div>
</footer>

<?php endif; ?>