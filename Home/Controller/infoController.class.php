<?php
//声明命名空间
namespace Home\Controller;
use \Frame\Libs\BaseController;
use \Home\Model\LinksModel;
use \Home\Model\CategoryModel;
use \Home\Model\ArticleModel;

//定义最终的内容页控制器类，并继承基础控制器类
final class infoController extends BaseController{
    public function info()
	{

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
		$pagesize	= 5;
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
		//点击排行
		$PaiHang=ArticleModel::getInstance()->fetchAllWithPaiHang();
		//(8)向视图赋值，并显示视图
		$this->smarty->assign(array(
			'categorys'	=> $categorys,
			'months'	=> $months,
			'articles'	=> $articles,
			'pageStr'	=> $pageStr,
			'PaiHang'	=> $PaiHang,
		));
	$this->smarty->display("Index/infopic.html");
	}


	public function info_point_title(){

			$id = $_GET['id'];	
			ArticleModel::getInstance()->updateRead($id);
			//(2)获取指定id的连表查询的文章数据
			$article = ArticleModel::getInstance()->fetchOneWithJoin("article.id=$id");
	
			//(3)获取上一条和下一条文章
			$arr[] = ArticleModel::getInstance()->fetchOneWithJoin("article.id<$id","article.id DESC");
			$arr[] = ArticleModel::getInstance()->fetchOneWithJoin("article.id>$id","article.id ASC");
			//(2)获取无限级分类的数据
		$categorys = CategoryModel::getInstance()->categoryList(
			//获取带文章数的原始分类数据
			CategoryModel::getInstance()->fetchAllWithCount()
		);
			//(3)获取文章按月份归档数据
			$months = ArticleModel::getInstance()->fetchAllWithMonth();
			$PaiHang=ArticleModel::getInstance()->fetchAllWithPaiHang();
			$this->smarty->assign(array(
				'categorys'	=> $categorys,
				'months'	=> $months,
				'article'	=> $article,
				'arr'		=> $arr,
				'PaiHang'	=> $PaiHang,
			));
			$this->smarty->display("Index/info.html");
			
	}


	public function praise()
	{
		$id = $_GET["id"];
		//判断用户是否登录
		if(isset($_SESSION['username']))
		{
			//判断当前文章是否点赞过
			if(!isset($_SESSION['praise'][$id]))
			{
				//更改当前ID状态
				$_SESSION['praise'][$id] = 1;
				//更新点赞数
				ArticleModel::getInstance()->updatePraise($id);
				//跳回到原地方
				$this->jump("点赞成功！","?c=info&a=info_point_title&id=$id");
			}else
			{
				//如果点赞过，不能再次点赞
				$this->jump("同一篇文章只能点赞一次！","?c=info&a=info_point_title&id=$id");
			}
		}else
		{
			//跳转到后台登录
			$this->jump("必须是登录用户才能点赞！","./admin.php?c=User&a=login");
		}
	}
}
