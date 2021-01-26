<?php

class page{
    private $incl_lib = "./";
    private $page_info = [
        "TITLE" => "Untitled",
        "DESC" => "Calender for students",
    ];
    private $account = null;
    private $account_info = [
        "login" => false,
    ];

    function __construct($rPATHnum = 0){
        for($i = 1; $i <= $rPATHnum; $i++){
            $this->incl_lib .= "../";
        }

        $this->account = new account();
        $this->account_info = $this->account->getinfo();
    }

    public function setinfo($arr){
        //ページ情報設定
        foreach($arr as $key => $value){
            $this->page_info[$key] = $value;
        }
    }

    public function gen_page($main_txt){

    }
}

class account{
    private $info = [
        "id" => null,
        "name" => null
    ];

    function __construct(){
        $DB = new database();
    }

    public function getstatus(){
        //ログインしてるかは isset(SAMPLE->getstatus()["id"]) で確認可能
        return $this->status;
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

    public function connect($name){
        if(isset($this->ini_data["user"]) && isset($this->init_data["pass"])){
            try{
                $this->mysql = new PDO("mysql:dbname=C4S;host=localhost", $this->ini_data["user"], $this->ini_data["pass"]);
                return true;
            }
            catch(Exception $e){
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function disconnect(){
        $this->mysql = null;
        return true;
    }
}

?>