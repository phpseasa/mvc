<?php
    /**
     * @author yuxing@book.sina.com
     * @file sinamvc index.php
     */

if(version_compare(PHP_VERSION,'5.4.0','<')) exit('Need php ver 5.6 or later');


define('APP_PATH','./application/');
define('SYS_PATH','./sinamvc/');
//define('CONF_PATH','');
define('EXT','.php');

//echo APP_PATH;
//echo SYS_PATH;
define('BASE_PATH',dirname(__DIR__).DIRECTORY_SEPARATOR);
//define('SYS_PATH',BASE_PATH.'sinamvc');
//echo BASE_PATH;exit;
require_once SYS_PATH.'sinamvc'.EXT;