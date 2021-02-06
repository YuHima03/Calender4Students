<?php

include_once "../libs/C4S_main.php";

$page = new page(1);

$page->set_info([
    "TITLE" =>  "ホーム"
]);

?>

<!DOCTYPE html>
<html lang="ja">
<?php $page->gen_page("head"); ?>
<body>
<!--header-->
<?php $page->gen_page("body/header"); ?>
<!--main-->
<main>
    <h1>HOME</h1>
</main>
<!--footer-->
<?php $page->gen_page("body/footer"); ?>
</body>
</html>