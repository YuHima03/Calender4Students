<?php

class page{
    private $top_lib = "./";
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
        "body"  =>  false
    ];

    function __construct($rPATHnum = 0){
        for($i = 1; $i <= $rPATHnum; $i++){
            $this->top_lib .= "../";
        }

        $this->account = new account($this->top_lib);
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

    public function gen_page($mode = "ALL", $inner_html = null){
        if(isset($mode)){
            include "{$this->top_lib}include/template.html";
            return true;
        }
        else{
            return false;
        }
    }
}

class account{
    private $info = [
        "id" => null,
        "name" => null
    ];

    function __construct($rPATH){
        $DB = new database($rPATH);
        if($DB->connect()){
            $DB->disconnect();
        }
    }

    public function getinfo(){
        //ログインしてるかは isset(SAMPLE->getstatus()["id"]) で確認可能
        return $this->info;
    }
}

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
        $this->mysql->query($text);
    }
}

?>