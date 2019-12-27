<?php
//声明命名空间
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\LiuModel;
use \Admin\Model\CategoryModel;

//定义最终的文章控制器类，并继承基础控制器类
final class LiuController extends BaseController{

	//显示文章列表数据
	public function index()
	{

	
	
		//(3)构建分页的参数
		$pagesize 	= 5;
		$page 		= isset($_GET['page']) ? $_GET['page'] : 1;
		$startrow	= ($page-1)*$pagesize;
		$records 	= LiuModel::getInstance()->rowCount();
		$params 	= array(
				'c'	=> CONTROLLER,
				'a'	=> ACTION,
            );
            $Liu = LiuModel::getInstance()->fetchAllWithJoin($startrow,$pagesize);
		//(5)创建分页类对象
		$pageObj = new \Frame\Vendor\Pager($records,$pagesize,$page,$params);
		$pageStr = $pageObj->showPage();

		//(6)向视图赋值，并显示视图
		$this->smarty->assign(array(
                'Liu'	=> $Liu,
                'pageStr'	=> $pageStr,
			));
		$this->smarty->display("Liuyan/index.html");
	}

	//删除文章
	public function delete()
	{
		//获取地址栏传递的id
		$id = $_GET['id'];
		//删除记录
		LiuModel::getInstance()->delete($id);
		//跳转到列表页
		$this->jump("id={$id}留言删除成功！","?c=Liu");
	}
}