<?php
/**
 * @ author yuxing@sina.book.com
 * @ file common function
 */
if(!function_exists('C')){
    function C($name=null,$value=null,$default=null){

        $_config = include(APP_PATH.'config'.EXT);

        if(empty($name)){
            return $_config;
        }
        //优先执行设置获取或赋值
        if(is_string($name)){
            if(!strpos($name,'.')){
//                $name = strtoupper($name);
                if(is_null($value)){
                    return isset($_config[$name]) ? $_config[$name] :$default;
                }
                $_config[$name] = $value;
                return null;
            }
            //二维数组设置和获取支持
            $name = explode('.',$name);
            $name[0] = strtoupper($name[0]);
            if(is_null($value))
                return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
            $_config[$name[0]][$name[1]] = $value;
            return null;
        }
        // 批量设置
        if(is_array($name)){
            $_config = array_merge($_config,array_change_key_case($name,CASE_UPPER));
            return null;
        }
        return null; //避免非法参数
    }
}

//获取输入参数
function I($name,$default='',$filter=null,$datas=null){
    static $_PUT=null;
    if(strpos($name,'/')){ //指定修饰符
        list($name,$type) = explode('/',$name,2);
    }elseif(C('VAR_AUTO_STRING')){//默认强制转换为字符串
        $type='s';
    }
    if(strpos($name,'.')) { // 指定参数来源
        list($method,$name) =   explode('.',$name,2);
    }else{ // 默认为自动判断
        $method =   'param';
    }
    switch(strtolower($method)) {
        case 'get'     :
            $input =& $_GET;
            break;
        case 'post'    :
            $input =& $_POST;
            break;
        case 'put'     :
            if(is_null($_PUT)){
                parse_str(file_get_contents('php://input'), $_PUT);
            }
            $input 	=	$_PUT;
            break;
        case 'param'   :
            switch($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $input  =  $_POST;
                    break;
                case 'PUT':
                    if(is_null($_PUT)){
                        parse_str(file_get_contents('php://input'), $_PUT);
                    }
                    $input 	=	$_PUT;
                    break;
                default:
                    $input  =  $_GET;
            }
            break;
        case 'path'    :
            $input  =   array();
            if(!empty($_SERVER['PATH_INFO'])){
                $depr   =   C('URL_PATHINFO_DEPR');
                $input  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));
            }
            break;
        case 'request' :
            $input =& $_REQUEST;
            break;
        case 'session' :
            $input =& $_SESSION;
            break;
        case 'cookie'  :
            $input =& $_COOKIE;
            break;
        case 'server'  :
            $input =& $_SERVER;
            break;
        case 'globals' :
            $input =& $GLOBALS;
            break;
        case 'data'    :
            $input =& $datas;
            break;
        default:
            return null;
    }
    if(''==$name) { // 获取全部变量
        $data       =   $input;
        $filters    =   isset($filter)?$filter:C('DEFAULT_FILTER');
        if($filters) {
            if(is_string($filters)){
                $filters    =   explode(',',$filters);
            }
            foreach($filters as $filter){
                $data   =   array_map_recursive($filter,$data); // 参数过滤
            }
        }
    }elseif(isset($input[$name])) { // 取值操作
        $data       =   $input[$name];
        $filters    =   isset($filter)?$filter:C('DEFAULT_FILTER');
        if($filters) {
            if(is_string($filters)){
                if(0 === strpos($filters,'/')){
                    if(1 !== preg_match($filters,(string)$data)){
                        // 支持正则验证
                        return   isset($default) ? $default : null;
                    }
                }else{
                    $filters    =   explode(',',$filters);
                }
            }elseif(is_int($filters)){
                $filters    =   array($filters);
            }

            if(is_array($filters)){
                foreach($filters as $filter){
                    if(function_exists($filter)) {
                        $data   =   is_array($data) ? array_map_recursive($filter,$data) : $filter($data); // 参数过滤
                    }else{
                        $data   =   filter_var($data,is_int($filter) ? $filter : filter_id($filter));
                        if(false === $data) {
                            return   isset($default) ? $default : null;
                        }
                    }
                }
            }
        }
        if(!empty($type)){
            switch(strtolower($type)){
                case 'a':	// 数组
                    $data 	=	(array)$data;
                    break;
                case 'd':	// 数字
                    $data 	=	(int)$data;
                    break;
                case 'f':	// 浮点
                    $data 	=	(float)$data;
                    break;
                case 'b':	// 布尔
                    $data 	=	(boolean)$data;
                    break;
                case 's':   // 字符串
                default:
                    $data   =   (string)$data;
            }
        }
    }else{ // 变量默认值
        $data       =    isset($default)?$default:null;
    }
    is_array($data) && array_walk_recursive($data,'think_filter');
    return $data;
}
function array_map_recursive($filter, $data) {
    $result = array();
    foreach ($data as $key => $val) {
        $result[$key] = is_array($val)
            ? array_map_recursive($filter, $val)
            : call_user_func($filter, $val);
    }
    return $result;
}

function think_filter(&$value){
    // TODO 其他安全过滤

    // 过滤查询特殊字符
    if(preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i',$value)){
        $value .= ' ';
    }
}