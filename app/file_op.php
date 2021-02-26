<?php
/**
 * file_op.php
 * 
 * ファイル操作
 * 
 * @author YuHima <Twitter:@YuHima_03>
 * @copyright (C)2021 YuHima
 * @version 1.0.0 (2021-02-26)
 */

class file_op{
    private $handler = null;

    function __construct(string $filename, string $mode) {
        $this->handler = fopen($filename, $mode);
    }

    public function getLines($lineNumber){

    }
}

?>