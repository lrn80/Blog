<?php
//声明命名空间
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\CategoryModel;

//定义最终的分类控制器类，并继承基础控制器类
final class CategoryController extends BaseController
{
	//显示文章分类的列表
	public function index()
	{
		//(1)获取分类的原始数据
		$categorys = CategoryModel::getInstance()->fetchAll();
		//(2)获取无限级分类数据(对原始分类数组进行再次处理)
		//调用无限级分类的方法categoryList()
		$categorys = CategoryModel::getInstance()->categoryList($categorys);
		//(3)向视图赋值，并显示视图
		$this->smarty->assign("categorys",$categorys);
		$this->smarty->display("Category/index.html");
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
		$this->smarty->display("Category/add.html");
	}

	//插入数据
	public function insert()
	{
		//获取表单提交值
		$data['classname']	= $_POST['classname'];
		$data['orderby']	= $_POST['orderby'];
		$data['pid']		= $_POST['pid'];
		//插入数据
		if(CategoryModel::getInstance()->insert($data))
		{
			$this->jump("分类数据插入成功！","?c=Category");
		}
	}

	//显示修改的表单
	public function edit()
	{
		$id = $_GET['id'];
		//获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(
				CategoryModel::getInstance()->fetchAll()
			);
		//根据id获取指定的分类数据
		$arr = CategoryModel::getInstance()->fetchOne("id={$id}");
		//向视图赋值，并显示视图
		$this->smarty->assign("categorys",$categorys);
		$this->smarty->assign("arr",$arr);
		$this->smarty->display("Category/edit.html");
	}

	//更新数据
	public function update()
	{
		//获取表单提交数据
		$id = $_POST['id'];
		$data['classname'] 	= $_POST['classname'];
		$data['orderby']	= $_POST['orderby'];
		$data['pid']		= $_POST['pid'];
		//更新数据
		if(CategoryModel::getInstance()->update($data,$id))
		{
			$this->jump("id={$id}记录更新成功！","?c=Category");
		}
	}

	//删除记录
	public function delete()
	{
		$id = $_GET['id'];
		//判断是否删除成功
		if(CategoryModel::getInstance()->delete($id))
		{
			$this->jump("id={$id}记录删除成功！","?c=Category");
		}
	}
}
