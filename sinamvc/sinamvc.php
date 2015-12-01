<?php
    /**
     * @ file mvc index
     * @ author yuxing@sina.book.com
     */
define('MVC_VER','1.0.0');
//echo SYS_PATH.'core'.DIRECTORY_SEPARATOR.'common'.EXT;exit;
require_once(SYS_PATH.'core'.DIRECTORY_SEPARATOR.'common'.EXT);  //引入公共函数文件

require_once(APP_PATH.'config'.EXT);    //引入项目配置文件
//echo APP_PATH.'config'.EXT;exit;
//set_error_handler()
//echo SYS_PATH.'core'.DIRECTORY_SEPARATOR.'URI'.EXT;
//require_once(SYS_PATH.'core'.DIRECTORY_SEPARATOR.'URI'.EXT); //引入路由处理类
//echo SYS_PATH.'core'.DIRECTORY_SEPARATOR.'sina_router'.EXT;exit;
require_once(SYS_PATH.'core'.DIRECTORY_SEPARATOR.'sina_router'.EXT);

$RTR = new sina_router();
//echo C('test');
// Load the base controller class
//require_once(BASEPATH.'core/Controller.php');
//
//function &get_instance()
//{
//    return CI_Controller::get_instance();
//}
//
//
//if (file_exists(APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php'))
//{
//    require APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php';
//}

// Load the local application controller
// Note: The Router class automatically validates the controller path using the router->_validate_request().
// If this include fails it means that the default controller in the Routes.php file is not resolving to something valid.
//if ( ! file_exists(APPPATH.'controllers/'.$RTR->fetch_directory().$RTR->fetch_class().'.php'))
//{
//    show_error('Unable to load your default controller. Please make sure the controller specified in your Routes.php file is valid.');
//}
//
//include(APPPATH.'controllers/'.$RTR->fetch_directory().$RTR->fetch_class().'.php');
