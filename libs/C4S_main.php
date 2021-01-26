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

    ];

    function __construct(){
        $DB = new database();
    }

    public function getinfo(){
        return $this->info;
    }
}

class database{
    private $mysql = null;
    private $ini_data = null;

    function __construct(){
        
    }

    public function is_connected(){
        return isset($this->mysql);
    }

    public function connect($name){
        if(isset($this->ini_data)){
            try{
                $this->mysql = new PDO();
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
}

?>