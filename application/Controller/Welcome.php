<?php
    /**
     * @ file Welcome.php
     * @ author yuxing@book.sina.com
     * @ date 20151130
     */

class Welcome extends Controller{
    public function __construct(){
//        echo 111;exit;
    }
    public function test(){
        var_dump($_GET);exit;
    }
}