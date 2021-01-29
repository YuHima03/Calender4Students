<?php
//ヘッダー
if($option == "header" || !isset($option)): ?>

<header>
    <h1>Calender 4 Students</h1>
    <!--additional-->
    <?=$inner_html?>
</header>

<?php
//フッター
elseif($option == "footer" || !isset($option)): ?>

<footer>
    <div>
        <h4>FOOTER</h4>
    </div>
    <!--additional-->
    <?=$inner_html?>
</footer>

<?php endif; ?>