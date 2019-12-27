<?php
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL ^ E_WARNING);
//定义常量
define("DS",DIRECTORY_SEPARATOR); //目录分割符
define("ROOT_PATH",getcwd().DS); //网站根目录
define("APP_PATH",ROOT_PATH."Home".DS); //平台(应用)目录
//包含框架初始类文件
require_once(ROOT_PATH."Frame".DS."Frame.class.php");
//框架初始化
\Frame\Frame::run();