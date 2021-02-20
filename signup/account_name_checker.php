<?php

include_once "../libs/C4S_main.php";

$DB = new database("../");
$result = [];

if(isset($_POST['name'])){
    $name = $_POST['name'];
    if($DB->connect()){
        $sql = "SELECT `name` FROM `account` WHERE `name`=?";
        $stmt = $DB->getPDO()->prepare($sql);
        $stmt->execute([$name]);
        
        $result[] = ($stmt->rowCount() == 0) ? true : false;

        $DB->disconnect();
    }
}
else{
    $result[] = false;
}

echo json_encode($result);

?>