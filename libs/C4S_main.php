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
    private $account_info = [
        "login" => false
    ];
    private $gen_opt = [
        "header"        =>  true,
        "header_add"    =>  [],
        "footer"        =>  true,
    ];
    private $gen_flag = [
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
        $this->account_info += $this->account->getinfo();
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

    public function gen_page($mode = "_ALL", $inner_html = null){
        $tmp = preg_split("/\//", $mode);
        $mode = $tmp[0];
        $option = (isset($tmp[1])) ? $tmp[1] : null;

        $mode_list = ["head", "body"];

        if(in_array($mode, $mode_list, true)){
            include "{$this->relPATH}include/{$mode}.php";
        }
        //auto_setting
        else if($mode === "_AUTO" && gettype($mode) === "array"){
            foreach($data as $key => $inner_html){
                $this->gen_page($key, $value);
            }
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
    public function put_data($data){
        $ret = "<script>PHP_DATA = " . json_encode($data) . ";</script>";
        echo $ret;
        return $ret;
    }
}

class account{
    private $info = [
        "id"        =>  null,
        "name"      =>  null,
        "errors"    =>  []
    ];
    private $relPATH = "./";

    function __construct($rPATH){
        $this->relPATH = $rPATH;
        $DB = new database($this->relPATH);

        if(isset($_SESSION['token']) && isset($_COOKIE['token'])){
            if($DB->connect()){
                //ログイン情報をDBと照合
                $token = [$_SESSION['token'], $_COOKIE['token']];
                foreach($DB->execute("SELECT * FROM `login_session` WHERE `session_token`='{$token[0]}'") as $data){
                    if($data["cookie_token"] == $token[1]){
                        //認証完了
                    }
                    else{
                        //不正ログイン？
                        $DB->execute("DELETE FROM `login_session` WHERE `session_token`='{$token[0]}'");
                        unset($_SESSION['token']);
                        setcookie("__session", "", time()-1800, "/");
                        setcookie("_token", "", time()-1800, "/");
                        $this->info["errors"][] = "BAD_LOGIN_REQUEST";

                        //ページ再読み込み
                        header("Location: ./{$_SERVER[PHP_SELF]}");
                    }
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

    public function getinfo(){
        //ログインしてるかは isset(SAMPLE->getstatus()["id"]) で確認可能
        return $this->info;
    }

    public function logout($mode = "normal"){
        if(isset($this->info["id"] || $mode == "force"){
            $DB = new database($this->relPATH);
            if($DB->connect()){
                $DB->execute("DELETE FROM `login_session` WHERE `session_token`='{$_SESSION[token]}'");
                $DB->disconnect();
            }
        }
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
}

//////////////////////////////////////////////////
//ランダムな文字列を返す($lenの長さの文字列を$modeでハッシュ化する)
function rand_text($len = 128, $mode = "sha256"){
    $rand_b = openssl_random_pseudo_bytes($len);
    return ($mode == "none") ? bin2hex($rand_b) : hash($mode, $rand_b);
}

?>