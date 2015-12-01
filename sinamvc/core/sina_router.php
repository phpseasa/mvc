<?php
    /**
     * @ file router.php
     * @ dauthor yuxing@sina.book.com
     * @ date 20151230
     */

class sina_router{

    public function __construct(){
        $res = $this->_get_URI();  //取出pathinfo的信息
//        var_dump($res);exit;
//        list($class,$func) =$res;
        $this->_uri_to_path($res);
    }

    public function _get_URI(){

        if(!isset($_SERVER['REQUEST_URI']) || ! isset($_SERVER['SCRIPT_NAME'])){
            return '';
        }
        $uri = $_SERVER['REQUEST_URI'];

//        var_dump($_SERVER['QUERY_STRING']);exit;
        if(strpos($_SERVER['REQUEST_URI'],$_SERVER['SCRIPT_NAME'])===0){
            $uri = substr($uri,strlen($_SERVER['SCRIPT_NAME']));
        }
        $patt = '/\?/';
        $part = preg_split($patt,$uri,2);
//        var_dump($part);exit;
        $uri = $part[0];

//        var_dump($uri);exit;
        if(isset($part[1])){
            $_SERVER['QUERY_STRING'] = $part[1];
            parse_str($_SERVER['QUERY_STRING'],$_GET);
        }else{
            $_SERVER['QUERY_STRING'] = $part[0];
            $_GET = array();
        }
//        var_dump($_GET);exit;
        if ($uri == '/' || empty($uri))
        {
            return '/';
        }
//        var_dump($uri);exit();
//        var_dump($uri);exit;
        $uri = parse_url($uri,PHP_URL_PATH);
//        var_dump($uri);exit();
//        var_dump(PHP_URL_PATH);exit();
        return str_replace(array('//', '../'), '/', trim($uri, '/'));
    }

    public function _uri_to_path($path){
        $arr = explode('/',$path);
//        echo count($arr);
        $class = array_shift($arr);
        $method = array_shift($arr);
//        var_dump($class);
//        var_dump($method);
//        var_dump($arr);
        $cnt = count($arr);
//        if($cnt && count($arr)%2===0){
//            for($i=0;$i<=count($arr);$i+=2){
//                var_dump($arr[$i]);
//                var_dump($arr[$i+1]);
//                $_GET[$arr[$i]]=$arr[$i+1];
//                echo 111;exit;
//                echo $i;
//            }
        $i=0;
        while(($i<$cnt) && count($arr)%2===0){
            $_GET[$arr[$i]]=$arr[$i+1];
//            var_dump($_GET);
            $i+=2;
        }

//        echo APP_PATH.'Controller'.DIRECTORY_SEPARATOR.$class.EXT;exit;
        if(!file_exists(APP_PATH.'Controller'.DIRECTORY_SEPARATOR.$class.EXT)){
            exit('类文件不存在');
        }
        require_once(APP_PATH.'Controller'.DIRECTORY_SEPARATOR.$class.EXT);
        $obj =new $class;
        if(!method_exists($obj,$method)){
            exit('方法不存在!');
        }
        $obj->$method();
        $obj->$method();
    }
}