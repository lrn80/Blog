<?php
//声明命名空间
namespace Frame;

//定义最终的框架初始类
final class Frame{
	//初始化方法
	public static function run()
	{
		self::initCharset();	//初始化字符集设置
		self::initConfig();		//初始化配置文件
		self::initRoute();		//初始化路由参数
		self::initConst();		//初始化常量目录设置
		self::initAutoLoad();	//初始化类的自动加载
		self::initDispatch();	//初始化请求分发
	}

	//私有的静态的字符集设置
	private static function initCharset()
	{
		header("content-type:text/html;charset=utf-8");
		//开启SESSION会话
		session_start();
	}

	//私有的静态的初始化配置文件
	private static function initConfig()
	{
		//例如配置文件路径：./Home/Conf/Config.php
		$GLOBALS['config'] = require_once(APP_PATH."Conf".DS."Config.php");
	}

	//私有的静态的初始路由参数
	private static function initRoute()
	{
		$p = $GLOBALS['config']['default_platform'];	//平台参数
		$c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller'];	//控制器参数
		$a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action'];	//动作参数
		define("PLAT",$p);
		define("CONTROLLER",$c);
		define("ACTION",$a);
	}

	//私有的静态的设置目录常量方法
	private static function initConst()
	{
		define("VIEW_PATH",APP_PATH."View".DS); //./Admin/View/
		define("FRAME_PATH",ROOT_PATH."Frame".DS); //Frame目录
	}

	//私有的静态的类的自动加载
	private static function initAutoLoad()
	{
		spl_autoload_register(function($className){
			//将空间中的类名，转成真实的类文件路径
			//空类中的类名：\Home\Controller\StudentController
			//真实的类文件：./Home/Controller/StudentController.class.php
			$filename = ROOT_PATH.str_replace("\\",DS,$className).".class.php";
			//如果类文件存在，则包含
			if(file_exists($filename)) require_once($filename);
		});
	}

	//私有的静态的请求分发：创建哪个控制器类的对象？调用控制器对象的哪个方法
	private static function initDispatch()
	{
		//构建控制器类名：\Home\Controller\StudentController
		$className = "\\".PLAT."\\"."Controller"."\\".CONTROLLER."Controller";
		//创建控制器类的对象
		$controllerObj = new $className();
		//调用控制器对象的方法
		$actionName = ACTION;
		$controllerObj->$actionName(); //index()
	}
}