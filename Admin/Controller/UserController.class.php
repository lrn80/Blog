<?php
//声明命名空间
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\UserModel;

//定义最终的用户控制器类，并继承基础控制器
final class UserController extends BaseController{

	//用户管理首页
	public function index()
	{
		//判断用户是否存在
		$this->denyAccess();
		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//获取多行数据
		$users = $modelObj->fetchAll();
		//向视图赋值，并显示视图
		$this->smarty->assign("users",$users);
		$this->smarty->display("User/index.html");
	}

	//显示添加用户的表单
	public function add()
	{
		//判断用户是否存在
		$this->denyAccess();
		$this->smarty->display("User/add.html");
	}

	//插入用户数据
	public function insert()
	{
		//判断用户是否存在
		$this->denyAccess();
		//获取表单提交值
		$data['username']	= $_POST['username'];
		$data['password']	= md5(md5($_POST['password']));
		$data['name']		= $_POST['name'];
		$data['tel']		= $_POST['tel'];
		$data['status']		= $_POST['status'];
		$data['role']		= $_POST['role'];
		$data['addate']		= time();
		
		//判断两次输入的密码是否一致
		if($data['password'] != md5(md5(($_POST['confirmpwd']))))	{
			$this->jump("两次输入的密码不一致！","?c=User");
		}

		//创建模型类对象
		$modelObj = UserModel::getInstance();

		//判断用户是否已经注册
		if($modelObj->rowCount("username='{$data['username']}'")){
			$this->jump("用户名{$data['username']}已经被注册了！","?c=User");
		}

		//判断用户是否插入成功
		if($modelObj->insert($data)){
			$this->jump("用户添加成功！","?c=User");
		}else{
			$this->jump("用户添加失败！","?c=User");
		}
	}

	//显示编编的表单
	public function edit()
	{
		//判断用户是否存在
	$this->denyAccess();
		//获取地址栏传递的id
		$id = $_GET['id'];
		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//获取指定id的数据
		$user = $modelObj->fetchOne("id={$id}");
		//向视图赋值，并显示视图
		$this->smarty->assign("user",$user);
		$this->smarty->display("User/edit.html");
	}

	//更新用户数据
	public function update()
	{
		//判断用户是否存在
		$this->denyAccess();
		//获取表单提交值
		$id = $_POST['id'];
		$data['name']	= $_POST['name'];
		$data['tel']	= $_POST['tel'];
		$data['status']	= $_POST['status'];
		$data['role']	= $_POST['role'];
		//判断密码是否为空
		if(!empty($_POST['password']) && !empty($_POST['confirmpwd'])){
			//判断两次输入的密码是否一致
			if($_POST['password']==$_POST['confirmpwd']){
				$data['password'] = md5(md5($_POST['password']));
			}
		}
		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//判断数据是否更新成功
		if($modelObj->update($data,$id)){
			$this->jump("id={$id}用户更新成功！","?c=User");
		}else{
			$this->jump("id={$id}用户更新失败！","?c=User");
		}
	}

	//删除用户
	public function delete()
	{
		//判断用户是否存在
		$this->denyAccess();
		//获取地址栏传递的id
		$id = $_GET['id'];
		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//判断是否删除成功
		if($modelObj->delete($id)){
			$this->jump("id={$id}用户删除成功！","?c=User");
		}else{
			$this->jump("id={$id}用户删除失败！","?c=User");
		}
	}

	//用户登录表单
	public function login()
	{
		$this->smarty->display("User/login.html");
	}

	//用户登录验证方法
	public function loginCheck()
	{
		//(1)获取表单提交值
		$username = $_POST["username"];
		$password = md5(md5($_POST["password"]));
//        $password = $_POST["password"];
		$verify   = $_POST["verify"];

		//(2)判断验证输入的是否正确(不区分大小写)
		if(strtolower($verify)!=strtolower($_SESSION['captcha']))
		{
			$this->jump("验证码不正确！","?c=User&a=login");
		}

		//(3)判断用户的账号是否正确
		$user = UserModel::getInstance()->fetchOne("username='$username' and password='$password'");
		if(empty($user))
		{
			$this->jump("用户名或密码不正确！","?c=User&a=login");
		}

		//(4)更新用户资料：最后登录的IP、最后登录时间、登录总次数
		$data['last_login_ip']		= $_SERVER['REMOTE_ADDR'];
		$data['last_login_time'] 	= time();
		$data['login_times']		= $user['login_times']+1;
		if(!UserModel::getInstance()->update($data,$user['id']))
		{
			$this->jump("用户信息更新失败！","?c=User&a=login");
		}

		//(5)将用户状态信息写入SESSION
		$_SESSION['uid']		= $user['id'];
		$_SESSION['username'] 	= $username;

		//(6)跳转到网站后台管理首页
		$this->jump("恭喜！{$username}用户登录成功，正在跳转...","?c=Index&a=index");
	}

	//用户退出方法
	public function logout()
	{
		//删除SESSION数据
		unset($_SESSION['username']);
		unset($_SESSION['uid']);
		//删除SESSION文件
		session_destroy();
		//删除SESSION对应的COOKIE数据
		setcookie(session_name(),false);
		//跳转到后台登录页面
		$this->jump("用户退出成功！","?c=User&a=login");
	}

	//获取验证码
	public function captcha()
	{
		//创建验证码类的对象
		$captchaObj = new \Frame\Vendor\Captcha();
	}

}
