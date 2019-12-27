<?php
//声明命名空间
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\ArticleModel;
use \Admin\Model\CategoryModel;

//定义最终的文章控制器类，并继承基础控制器类
final class ArticleController extends BaseController{

	//显示文章列表数据
	public function index()
	{
		//(1)获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(
				CategoryModel::getInstance()->fetchAll()
			);

		//(2)构建查询的条件
		$where = "2>1";
		if(!empty($_REQUEST['category_id'])) $where .= " AND category_id=".$_REQUEST['category_id'];
		if(!empty($_REQUEST['keyword'])) $where .= " AND title like '%".$_REQUEST['keyword']."%'";

		//(3)构建分页的参数
		$pagesize 	= 5;
		$page 		= isset($_GET['page']) ? $_GET['page'] : 1;
		$startrow	= ($page-1)*$pagesize;
		$records 	= ArticleModel::getInstance()->rowCount($where);
		$params 	= array(
				'c'	=> CONTROLLER,
				'a'	=> ACTION,
			);
		//如果条件存在，则添加链接地址条件
		if(!empty($_REQUEST['category_id'])) $params['category_id'] = $_REQUEST['category_id'];
		if(!empty($_REQUEST['keyword'])) $params['keyword'] = $_REQUEST['keyword'];

		//(4)获取连表查询的文章分页数据
		$articles = ArticleModel::getInstance()->fetchAllWithJoin($where,$startrow,$pagesize);
		
		//(5)创建分页类对象
		$pageObj = new \Frame\Vendor\Pager($records,$pagesize,$page,$params);
		$pageStr = $pageObj->showPage();

		//(6)向视图赋值，并显示视图
		$this->smarty->assign(array(
				'categorys'	=> $categorys,
				'articles'	=> $articles,
				'pageStr'	=> $pageStr,
			));
		$this->smarty->display("Article/index.html");
	}

	//显示添加的表单
	public function add()
	{
		//获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(
				CategoryModel::getInstance()->fetchAll()
			);
		//向视图赋值，并显示视图
		$this->smarty->assign("categorys",$categorys);	
		$this->smarty->display("Article/add.html");	
	}

	//插入数据
	public function insert()
	{
		//获取表单提交数据
		$data['category_id']	= $_POST['category_id'];
		$data['user_id']		= $_SESSION['uid'];
		$data['title']			= $_POST['title'];
		$data['content']		= $_POST['content'];
		$data['orderby']		= $_POST['orderby'];
		$data['top']			= isset($_POST['top']) ? 1 : 0;
		$data['addate']			= time();
		//插入数据
		if(ArticleModel::getInstance()->insert($data))
		{
			$this->jump("文章添加成功！","?c=Article");
		}
	}

	//显示修改的表单
	public function edit()
	{
		//获取地址栏传递的id
		$id = $_GET['id'];
		//获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(
				CategoryModel::getInstance()->fetchAll()
			);
		//获取指定id的单行数据
		$article = ArticleModel::getInstance()->fetchOne("id={$id}");
		//向视图赋值，并显示视图
		$this->smarty->assign(array(
				'categorys'	=> $categorys,
				'article'	=> $article,
			));
		$this->smarty->display("Article/edit.html");
	}

	//更新表单数据
	public function update()
	{
		//获取表单数据
		$id = $_POST['id'];
		$data['category_id']	= $_POST['category_id'];
		$data['title']			= addslashes($_POST['title']);
		$data['orderby']		= $_POST['orderby'];
		if(isset($_POST['top'])) $data['top'] = 1;
		//过滤掉特殊符号：单引号、双引号、反斜杠
		$data['content']		= addslashes($_POST['content']);
		//更新数据
		if(ArticleModel::getInstance()->update($data,$id))
		{
			$this->jump("id={$id}的文章更新成功！","?c=Article");
		}
	}

	//删除文章
	public function delete()
	{
		//获取地址栏传递的id
		$id = $_GET['id'];
		//删除记录
		ArticleModel::getInstance()->delete($id);
		//跳转到列表页
		$this->jump("id={$id}文章删除成功！","?c=Article");
	}
}