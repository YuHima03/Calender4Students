<?php
/**
 * proc_req.php
 * 
 * カレンダー操作のリクエストの処理
 * 
 * @author YuHima <Twitter:@YuHima_03>
 * @copyright (C)2021 YuHima
 * @version 1.0.0 (2021-02-11)
 */

include_once "../libs/C4S_main.php";

/**
 * ==<< 予定等の保存方法に就いて >>==
 * 
 * UUID_sch.json
 *  {
 *      //参照しやすいようにindexListとscheduleで分ける
 *      indexList      :   {
 *      },
 *      schedule    :   [
 *      ]
 *  }
 */

/**
 * 結果
 */
$result = [
    "result"    =>  false
];

/**
 * 要求は全てPOSTでの受け取り
 */
$post_data = [
    "_TOKEN"   =>  null,   //トークン(照合用)
    "_MODE"         =>  null,   //モード
    "_TIMESTAMP"    =>  null,   //リクエスト送信時間
    "_DATETIME"     =>  null,   //対象日
    "_DATA"         =>  []      //モードに応じたデータ
];

/**
 * POSTで受け取ったデータの解析
 */
foreach($post_data as $key => $value){
    if(!isset($_POST[$key])){
        //必要な情報がない時はfalse返す
        exit(json_encode($result));
    }
    else{
        $post_data[$key] = $_POST[$key];
    }
}

$account = new account("../");
$DB = new database("../");

/** 
 * ログイン済み & データベース接続完了 & アカウントID一致
*/
if($account->getinfo()["login"] && $DB->connect() && $account->getinfo()["id"] = $post_data["_ACCOUNT_ID"]){
    $result["result"] = true;
}

exit(json_encode($result));

?>