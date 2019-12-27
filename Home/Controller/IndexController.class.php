<?php
//声明命名空间
namespace Home\Controller;
use \Frame\Libs\BaseController;
use \Home\Model\LinksModel;
use \Home\Model\CategoryModel;
use \Home\Model\ArticleModel;

//定义最终的首页控制器类，并继承基础控制器类
final class IndexController extends BaseController{

	//显示前端首页
	public function index()
	{
		$links = LinksModel::getInstance()->fetchAll();
		//(2)获取无限级分类的数据
		$categorys = CategoryModel::getInstance()->categoryList(
			//获取带文章数的原始分类数据
			CategoryModel::getInstance()->fetchAllWithCount()
		);
			//(3)获取文章按月份归档数据
			$months = ArticleModel::getInstance()->fetchAllWithMonth();
		//(4)构建搜索的条件
		$where = "2>1";
		if(!empty($_REQUEST['title'])) $where .= " AND title like '%".$_REQUEST['title']."%'";
		if(!empty($_GET['category_id'])) $where .= " AND category_id=".$_GET['category_id'];
		//(5)构建分页的参数
		$pagesize	= 4;
		$page 		= isset($_GET['page']) ? $_GET['page'] : 1;
		$startrow	= ($page-1)*$pagesize;
		$records	= ArticleModel::getInstance()->rowCount();
		$params		= array(
				'c'	=> CONTROLLER,
				'a'	=> ACTION,
			);

		//(6)获取分页的字符串
		$pageObj = new \Frame\Vendor\Pager($records,$pagesize,$page,$params);
		$pageStr = $pageObj->showPageKuai();
			//(7)获取文章连表查询的分页数据
			$articles = ArticleModel::getInstance()->fetchAllWithJoin($where,$startrow,$pagesize);
		//(8)向视图赋值，并显示视图
		$this->smarty->assign(array(
			'links'		=> $links,
			'categorys'	=> $categorys,
			'months'	=> $months,
			'articles'	=> $articles,
			'pageStr'	=> $pageStr,
		));
	/*	echo '<pre>';
		print_r($pageStr);
		echo '</pre>';
		die();*/
	$this->smarty->display("Index/index.html");
	}
	public function about(){
		$this->smarty->display("Index/about.html");
	}
	public function  gbook()
	{
		$this->smarty->display("Index/gbook.html");
	}
}