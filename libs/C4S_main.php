<?php

class page{
    private $relPATH = "./";
    private $page_info = [
        "TITLE" => "Calender4Students",
        "DESC"  => "学生のためのカレンダー 名称は未定です...",
        "URL"   =>  "undecided",
        "IMAGE" =>  "undecided"
    ];
    private $account = null;
    private $account_info = [];
    private $gen_opt = [
        "header"        =>  true,
        "header_add"    =>  [],
        "footer"        =>  true,
    ];
    private $gen_flag = [
        "put_PHP_data"  =>  false,
        "head"  =>  false,
        "body"  =>  false,
        "html_start"    =>  false,
        "html_end"      =>  false
    ];

    function __construct($rPATHnum = 0){
        for($i = 1; $i <= $rPATHnum; $i++){
            $this->relPATH .= "../";
        }

        $this->account = new account($this->relPATH);
        $this->account_info = $this->account->getinfo();
    }

    public function set_info($arr){
        //ページ情報設定
        foreach($arr as $key => $value){
            $this->page_info[$key] = $value;
        }
    }

    public function get_info($key = null){
        return (isset($key)) ? $this->page_info[$key] : $this->page_info;
    }

    public function get_account_info(){
        return $this->account_info;
    }

    //ページ生成関連
    public function set_gen_option($arr){
        foreach($arr as $key => $value){
            $this->gen_opt[$key] = $value;
        }
        return true;
    }

    /**
     * ページ生成
     * @param string $mode モード``(_ALL,head,body)`` _オプションはスラッシュの後に_
     * @param string|array
     */
    public function gen_page($mode = "_ALL", $inner_html = null){
        $tmp = preg_split("/\//", $mode);
        $mode = $tmp[0];
        $option = (isset($tmp[1])) ? $tmp[1] : null;

        $mode_list = ["head", "body"];

        if(in_array($mode, $mode_list, true)){
            include "{$this->relPATH}include/{$mode}.php";
        }
        //print_all
        else if($mode === "_ALL"){
            foreach($key as $mode_list){
                $this->gen_page($key, ((isset($option[$key])) ? $option[$key] : null));
            }
        }

        return true;
    }

    //add_css
    public function add_css($href){
        if(is_array($href)){
            $ret_data = "";
            foreach($href as $v){
                $ret_data .= $this->add_css($v);
            }
            return $ret_data;
        }
        else{
            $href = $this->relPATH . $href;
            return "<link rel='stylesheet' href='{$href}' />";
        }

        return false;
    }

    //add_js
    public function add_js($href){
        if(is_array($href)){
            $ret_data = "";
            foreach($href as $v){
                $ret_data .= $this->add_js($v);
            }
            return $ret_data;
        }
        else{
            $href = $this->relPATH . $href;
            return "<script src='{$href}'></script>";
        }
    }

    /** jsに変数を渡す
     * @param array $data 
     * @return string
    */
    public function put_data($data, $force = false){
        if(!$this->gen_flag["put_PHP_data"] || $force){
            $ret = "<script>PHP_DATA = " . json_encode($data) . ";</script>";
            $this->gen_flag["put_PHP_data"] = true;
            return $ret;
        }
    }
}

class account{
    private $info = [
        "login"     =>  false,
        "id"        =>  null,
        "name"      =>  null,
        "errors"    =>  [],
        "unclaimed" =>  false
    ];
    private $relPATH = "./";

    function __construct($rPATH){
        $this->relPATH = $rPATH;
        $DB = new database($this->relPATH);

        if(isset($_SESSION['_token']) && isset($_COOKIE['_token'])){
            if($DB->connect()){
                //ログイン情報をDBと照合
                $token = [$_SESSION['_token'], $_COOKIE['_token']];

                $sql = "SELECT * FROM `account`, `login_session` WHERE `account`.`id` = `login_session`.`account_id` AND `login_session`.`session_token`=?";
                $stmt = $DB->getPDO()->prepare($sql);
                $stmt->execute([$token[0]]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($data["cookie_token"] == $token[1]){
                    //認証完了
                    $this->info["id"] = $data["id"];
                    $this->info["unclaimed"] = (bool)$data["unclaimed"];
                    $this->info["name"] = ($this->info["unclaimed"]) ? "Guest" : $data["name"];
                    $this->info["login"] = true;
                }
                else{
                    //不正ログイン？
                    $this->logout("force");
                    $this->info["errors"][] = "BAD_LOGIN_REQUEST";
                    echo "ログアウトしました";

                    //ページ再読み込み
                    header("Location: ./");
                }

                //切断
                $DB->disconnect();
            }
            else{
                $this->info["errors"][] = "DB_CONNECTION_ERROR";
                return;
            }
        }
    }

    /** 
     * @return array
     * @comment ログインしてるかは ``isset(obj名->getstatus()["id"])`` で確認可能
     * */
    public function getinfo(){
        return $this->info;
    }

    /**ログアウトする */
    public function logout($mode = "normal"){
        if(isset($this->info["id"]) || $mode == "force"){

            $DB = new database($this->relPATH);
            if($DB->connect()){
                //DBから削除
                $sql = "DELETE FROM `login_session` WHERE `session_token`=?";
                $stmt = $DB->getPDO()->prepare($sql);
                $stmt->execute([$_SESSION['_token']]);
                unset($stmt);

                if(isset($this->info["id"]) && $this->info["unclaimed"]){
                    //仮登録アカウントはアカウント情報もDBから削除
                    $sql = "DELETE FROM `account` WHERE `id`=?";
                    $stmt = $DB->getPDO()->prepare($sql);
                    $stmt->execute([$this->info["id"]]);
                }

                //セッション吹っ飛ばす
                unset($_SESSION['_token']);
                //クッキー吹っ飛ばす
                setcookie("__session", "", time()-1800, "/");
                setcookie("_token", "", time()-1800, "/");

                $DB->disconnect();
            }
        }
    }
}

//////////////////////////////////////////////////
//アカウント作成
class create_account{
    private $relPATH = "./";

    function __construct($_relPATH){
        $this->relPATH = $_relPATH;
    }

    /** 
     * アカウント作成（ログイン）
     * @param string $name アカウント名(他アカウントとの重複不可)
     * @param string $pass パスワード(ハッシュ化してないやつ)※垢名との重複不可
     * @param bool $login ログイン処理もするかどうか(true/false)
     * */
    public function create($name, $pass, $login = true){
        $DB = new database($this->relPATH);
        $pass = hash("sha512", $pass);
        $info = [
            "id"    =>  null,
            "name"  =>  null
        ];

        //アカウント登録
        if($DB->connect()){
            $sql = "INSERT INTO `account` (`name`, `password`, `uuid`, `unclaimed`) VALUES (?, ?, ?, 1)";
            $stmt = $DB->getPDO()->prepare($sql);
            do{
                $uuid = rand_text();
                if($pass == $uuid){
                    continue;
                }
                else{
                    $arr = [$name, $pass, $uuid];
                }
            }while(!$stmt->execute($arr));

            //ログイン処理
            if($login){
                //アカウントのIDを取得
                $sql = "SELECT `id` FROM `account` WHERE `name`=? AND `password`=?";
                $stmt = $DB->getPDO()->prepare($sql);
                $stmt->execute([$name, $pass]);
                $account_id = $stmt->fetch(PDO::FETCH_ASSOC)["id"];

                //セッションに登録
                $sql = "INSERT INTO `login_session` (`account_id`, `start_date`, `session_token`, `cookie_token`, `auto_login`) VALUES (?, ?, ?, ?, 1)";
                $stmt = $DB->getPDO()->prepare($sql);
                do{
                    $start_date = date("Y-m-d", time());
                    $session_token = rand_text();
                    $cookie_token = rand_text();
                    if($session_token == $cookie_token){
                        continue;
                    }
                    else{
                        $arr = [$account_id, $start_date, $session_token, $cookie_token];
                    }
                }while(!$stmt->execute($arr));

                //クッキー&セッションに登録
                $_SESSION['_token'] = $session_token;
                setcookie("_token", $cookie_token, time()+60*60*24*30*6, "/", "", false, true); //セッションは半年保持
            }

            $DB->disconnect();
        }
        else{
            return false;
        }

        return true;
    }
}

//////////////////////////////////////////////////
//データベース
class database{
    private $mysql = null;
    private $ini_data = null;

    function __construct($rPATH){
        $this->ini_data = parse_ini_file($rPATH . "libs/PDO_data.ini");
    }

    public function is_connected(){
        return isset($this->mysql);
    }

    public function connect(){
        //ini_dataはiniファイルから取得するデータベース情報ね
        if(isset($this->ini_data["user"]) && isset($this->ini_data["pass"])){
            try{
                $this->mysql = new PDO("mysql:dbname=C4S;host=localhost", $this->ini_data["user"], $this->ini_data["pass"]);
            }
            catch(Exception $e){
                return false;
            }
        }
        else{
            return false;
        }

        return true;
    }

    public function disconnect(){
        $this->mysql = null;
        return true;
    }

    public function execute($text){
        if($this->is_connected()){
            $fst = preg_split("/ /", $text)[0];
            $result = null;
            try{
                if ($fst == "SELECT" or $fst == "select"){ //select文
                    $result = $this->mysql->query($text)->fetchAll(PDO::FETCH_ASSOC);
                    return $result;
                    
                }
                if ($fst == "INSERT" or $fst == "insert"){ //insert文
                    try{
                        $this->mysql->query($text);
                    }
                    catch(PDOException $e){
                        return false;
                    }
                    return true;
                }
                else {
                    $this->mysql->query($text);
                    return true;
                }
            }
            catch (Exception $e){
                echo $e;
                $this->errors[] = "DB_PROC_ERROR";
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function getPDO(){
        return $this->mysql;
    }
}

//////////////////////////////////////////////////
//ランダムな文字列を返す($lenの長さの文字列を$modeでハッシュ化する)
function rand_text($len = 128, $mode = "sha256"){
    $rand_b = openssl_random_pseudo_bytes($len);
    return ($mode == "none") ? bin2hex($rand_b) : hash($mode, $rand_b);
}

?>